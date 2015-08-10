=== Plugin Name ===
Contributors: tywayne
Donate link: http://www.tywayne.com/simple-instagram-widget
Tags: instagram, plugin, widget
Requires at least: 3.0
Tested up to: 4.3
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Really Simple Instagram widget that displays Instagram photos.

== Description ==

Really simple WordPress widget that displays Instagram photos. 
Uses [the Instagram jQuery Plugin](https://github.com/potomak/jquery-instagram) from [potomak](https://github.com/potomak/).

== Installation ==

1. Install/activate the widget on WordPress.
2. Register your Instagram application at the [Instagram Developer site](https://instagram.com/developer)
3. Insert your Client ID at Settings->Simple Instagram Widget
2. Add widget to desired widget area.
3. Choose whether you'd like to display by username or by hashtag.
4. Insert the Instagram Username of the user who's photos you'd like to display.  - or - 
5. Insert the hashtag of the search for photos you'd like to make.
6. Insert the number of how many photos you'd like to show. 

The plugin comes with very little styling. You can override and style as needed in your theme stylesheet. 

== Screenshots ==

1. WordPress Widget fields

== Changelog ==

= 1.3.0 =
Important Update!
This plugin no longer supplies an Instagram Client ID. You must supply your own and insert at Settings -> Simple Instagram Widget.
Client ID can be found by registering an application on the [Instagram Developer site](https://instagram.com/developer)
If you do not create and insert an Instagram Client ID, the plugin will no longer work. 

Also - 
Bug Fix: Use PHP5 style constructors for WP_Widget to fix notices for Wordpress 4.3


= 1.2.6 =
Bug Fix: make sure we're accurately looking up and converted a username to userid

= 1.2.5 =
Bug Fix: fix undefined index notice in shortcode
Testing for WordPress v4.0

= 1.2.4 =
Bug Fix: properly delegate change events in widget admin

= 1.2.3 =
Enqueue admin js script from correct location

= 1.2.2 =
Add missing styles for shortcode

= 1.2.1 =
Fix mistake where update to v1.2 didn't include correct files

= 1.2 =
Add support for shortcode [simple_instagram hashtag="" username="" count="" ]

= 1.1.3 =
Keep original wrapper class for back compat

= 1.1.2 =
Fix bug that caused issues when multiple widgets were added to a page.

= 1.1.1 =
Allow use of username instead of userID - please switch to using the username instead

= 1.1 =
Add support for hashtag, remove dependancy on Instagram ClientID

= 1.0.2 =
* Fixed bug where ClientID wasn't saving sometimes

= 1.0.1 =
* Remove undefined index notices on clean install

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.1.3 =
Keep original wrapper class for back compat

= 1.1.2 =
Fix bug that caused issues when multiple widgets were added to a page.

= 1.1.1 =
Allow use of username instead of userID - please switch to using the username instead

= 1.1 =
Add support for hashtag, remove dependancy on Instagram ClientID

= 1.0.2 =
  Fixed bug where ClientID wasn't saving sometimes

= 1.0.1 =
Remove undefined index notices on clean install

= 1.0 =
Initial Version.