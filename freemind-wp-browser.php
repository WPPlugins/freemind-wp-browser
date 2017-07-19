<?php

/*
Plugin Name: Freemind WP Browser
Version: 1.2
Plugin URI: http://lakm.us/logit/
Description: Embed `*.mm` Freemind mind-map Flash Browser to a WordPress blog by `[freemind file="path-to-some-file.mm" /]`. This has been a modification of Joshua Eldridge' Flash Video Player. Powered by `visorFreemind.swf` from Flash Browser and `SWFObject` by Geoff Stearns.
Author: Arif Kusbandono
Author URI: http://lakm.us

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$videoid = 0;
$site_url = get_option('siteurl');

function FreemindMap_Parse($content) {
	$content = preg_replace_callback("/\[freemind ([^]]*)\/\]/i", "FreemindMap_Render", $content);
	return $content;
}

function FreemindMap_Render($matches) {
	global $videoid, $site_url;
	$output = '';
	$rss_output = '';
	$matches[1] = str_replace(array('&#8221;','&#8243;'), '', $matches[1]);
	preg_match_all('/([.\w]*)=(.*?) /i', $matches[1], $attributes);
	$arguments = array();

	foreach ( (array) $attributes[1] as $key => $value ) {
		// Strip out legacy quotes
		$arguments[$value] = str_replace('"', '', $attributes[2][$key]);
	}

	if ( !array_key_exists('filename', $arguments) && !array_key_exists('file', $arguments) ) {
		return '<div style="background-color:#ff9;padding:10px;"><p>Error: Required parameter "file" is missing!</p></div>';
		exit;
	}
	
	//Deprecate filename in favor of file. 
	if(array_key_exists('filename', $arguments)) {
		$arguments['file'] = $arguments['filename'];
	}

	$options = get_option('FreemindSettings');

	if(strpos($arguments['file'], 'http://') !== false || isset($arguments['streamer']) || strpos($arguments['file'], 'https://') !== false) {
		// This is a remote file, so leave it alone but clean it up a little
		$arguments['file'] = str_replace('&#038;','&',$arguments['file']);
	} else {
		$arguments['file'] = $site_url . '/' . $arguments['file'];
	}
	$output .= "\n" . '<span id="video' . $videoid . '" class="flashvideo">' . "\n";
   	$output .= '<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</span>' . "\n";
    	$output .= '<script type="text/javascript">' . "\n";
	$output .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/freemind-wp-browser/mediaplayer/visorFreemind.swf' . '","n' . $videoid . '","' . $options['width'] . '","' . $options['height'] . '","6");' . "\n";

		$output .= 's' . $videoid . '.addParam("quality", "high");' . "\n";
		$output .= 's' . $videoid . '.addParam("bgcolor", "#a0a0f0");' . "\n";
	$output .= 's' . $videoid . '.addVariable("CSSFile", "' . $site_url . '/wp-content/plugins/freemind-wp-browser/mediaplayer/flashfreemind.css' . '");' . "\n";
		$output .= 's' . $videoid . '.addVariable("openUrl", "_blank");' . "\n";
		$output .= 's' . $videoid . '.addVariable("startCollapsedToLevel","3");' . "\n";
		$output .= 's' . $videoid . '.addVariable("maxNodeWidth","200");' . "\n";
		$output .= 's' . $videoid . '.addVariable("mainNodeShape","elipse");' . "\n";
		$output .= 's' . $videoid . '.addVariable("justMap","false");' . "\n";
		$output .= 's' . $videoid . '.addVariable("initLoadFile","' . $arguments['file'] . '");' . "\n";
		$output .= 's' . $videoid . '.addVariable("defaultToolTipWordWrap",200);' . "\n";
		$output .= 's' . $videoid . '.addVariable("offsetX","left");' . "\n";
		$output .= 's' . $videoid . '.addVariable("offsetY","top");' . "\n";
		$output .= 's' . $videoid . '.addVariable("buttonsPos","top");' . "\n";
		$output .= 's' . $videoid . '.addVariable("min_alpha_buttons",20);' . "\n";
		$output .= 's' . $videoid . '.addVariable("max_alpha_buttons",100);' . "\n";
		$output .= 's' . $videoid . '.addVariable("scaleTooltips","false");' . "\n";


	$output .= 's' . $videoid . '.write("video' . $videoid . '");' . "\n";
	$output .= '</script>' . "\n";

	$videoid++;
	if(is_feed()) {
		return $rss_output;
	} else {
		return $output;
	}
}

function FreemindAddPage() {
	add_options_page('Freemind Options', 'Freemind', '8', 'freemind-wp-browser.php', 'FreemindOptions');
}

function FreemindOptions() {
	global $site_url;
	$message = '';
	$options = get_option('FreemindSettings');
	if ($_POST) {
		if (isset($_POST['width'])) {
			$options['width'] = $_POST['width'];
		}
		if (isset($_POST['height'])) {
			$options['height'] = $_POST['height'];
		}
		
		update_option('FreemindSettings', $options);
		$message = '<div class="updated"><p>' . $options['width'] . 'x' . $options['height'] .'<strong>&nbsp; Options saved.</strong></p></div>';	
	}
	echo '<div class="wrap">';
	echo '<h2>Freemind Options</h2>';
	echo $message;
	echo '<form method="post" action="options-general.php?page=freemind-wp-browser.php">';
	echo "<p>Welcome to the Freemind WP Browser plugin options menu! Here you can set all width-height variables for your website.</p>";
	echo '<h3>Width-Height</h3>' . "\n";
	echo '<table class="form-table">' . "\n";
	echo '<tr><th scope="row">width</th><td>' . "\n";
	echo '<input type="text" name="width" value="' . $options['width'] . '" />';
	echo '</td></tr>' . "\n";
	echo '<tr><th scope="row">height</th><td>' . "\n";
	echo '<input type="text" name="height" value="' . $options['height'] . '" />';
	echo '</td></tr>' . "\n";
	echo '</table>' . "\n";

	echo '<p class="submit"><input class="button-primary" type="submit" method="post" value="Update Options"></p>';
	echo '</form>';


	echo '</div>';
}	

function FreemindLoadDefaults() {
	$freemind_preset = array();
	$freemind_preset['width'] = '800';
	$freemind_preset['height'] = '400';
	return $freemind_preset;
}
function FreemindMap_head() {
	global $site_url;
	echo '<script type="text/javascript" src="' . $site_url . '/wp-content/plugins/freemind-wp-browser/swfobject.js"></script>' . "\n";
}


function Freemind_activate() {
	update_option('FreemindSettings', FreemindLoadDefaults());
}

register_activation_hook(__FILE__,'Freemind_activate');

function Freemind_deactivate() {
	delete_option('FreemindSettings');
}

register_deactivation_hook(__FILE__,'Freemind_deactivate');

add_action('wp_head', 'FreemindMap_head');

// CONTENT FILTER
add_filter('the_content', 'FreemindMap_Parse');

add_action('admin_menu', 'FreemindAddPage');

?>
