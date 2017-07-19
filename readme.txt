=== FreeMind WP Browser ===
Contributors: kusbandono
Donate link: http://lakm.us/
Tags: flash, mindmap, freemind
Requires at least: 2.9
Tested up to: 3.3.1
Stable tag: 1.2


This plugin embeds mindmaps created with FreeMind into WordPress post using FreeMind Flash Browser.

== Description ==

This has been a modification of [Joshua Eldridge' Flash Video Player](http://wordpress.org/extend/plugins/flash-video-player/). Powered by `visorFreemind.swf` from [FreeMind Flash Browser](http://freemind.sourceforge.net/wiki/index.php/Flash_browser) and `SWFObject` by [Geoff Stearns](www.geoffstearns.com). This plugin allows graphic visualization of mindmaps created with [FreeMind](http://freemind.sourceforge.net) by embedding them to WordPress posts using flash. It looks for `[freemind file="path-to-some-file.mm" /]` shortcode in post to embed it in a Flash presentation of the FreeMind mindmap file (`.mm` format).

== Usage ==

Use `[freemind file="path-to-some-file.mm" /]` shortcode to embed `*.mm` FreeMind mindmap file to a WordPress post.
An example is `[freemind file="../mind-map/example.mm" /]` where `mind-map` directory exists with `example.mm` inside it and the WordPress domain lies is in the same tree position (i.e.`some-wordpress-directory`) relative from the web root.

As the width of an actual FreeMind file is usually large, adjustment can be done from Freemind 'Options' panel to make it appear in a single-uniform size of width and height in the post flow.


== Installation ==

1. Upload the whole `freemind-wp-browser` directory and content to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I have activated the plugin, flash supported browser, but don't see the mindmap in the post. What do I do? =

Check and make sure that you have the appropriate path of the mindmap

== Screenshots ==

1. Example of a post showing embedded `.mm` file over flash
2. 'Options' menu to modify width and height for the whole mind map appearance

== Changelog ==

= 1.1 =
* First release.

= 1.2 =

Add two files due to 404 statistics showing that `mediaplayer/visorFreemind.swf` is making reference to them:

1. `index.mm` basically just a dummy FreeMind file (when no `.mm` file loaded and the flash projector is called)
2. `flashfreemind.css` which is also added to the `SWFObject` rendering variable as `CSS File`

These are minor workarounds. The best is of course to work directly with the flash sources and remove those references.

== Upgrade Notice == 

= 1.1 =
First release.

= 1.2 =
Add workaround for "404 not found" as a result of `mediaplayer/visorFreemind.swf` making references to two files not included in previous release.
