# DMG Text Widget #
Contributors: dancoded
Tags: text widget, widget, css, shortcodes
Donate link: http://dancoded.com/wordpress-plugins/
Requires at least: 3.1
Tested up to: 4.6
Stable tag: 1.1
License: GPL-3.0 or later
License URI: http://www.gnu.org/licenses/

A simple widget to add custom text/ HTML to your theme. Advanced options allow you to apply shortcodes, make the title a link and add CSS classes.

## Description ##

DMG Text Widget replaces the standard text widget with one that includes advanced options to apply a CSS class, add paragraphs, apply shortcodes, hide the title and make the title a link.

Two hooks are available to filter the title and text:
* ```dmg_text_widget_title``` for the title
* ```dmg_text_widget_text``` for the text

For example, to change the title on a single page or post, you could add this to your functions.php file:


```function myTitleFilter( $title )
{
	if( is_singular() )
	{
		return "<strong>$title</strong>";
	}
	else
	{
		return $title;		
	}
}
add_filter( 'dmg_text_widget_title' , 'myTitleFilter');```

More information about this plugin can be found at <http://dancoded.com/tag/dmg-text-widget/>.

## Adding CSS Class(es) ##

Enter strings, either space or comma separated, which will be applied as CSS classes to the widget wrapper. These classes are sanitized using the ```sanitize_html_class()``` function built in to Wordpress.

## Make the Title a link ##

Enter a valid URL to make the title a link.

## Automatically add paragraphs ##

This setting automatically adds paragraph tags to any text. This uses the Wordpress ```wpautop()``` function.

## Apply shortcodes ##

This setting processes the text and applies any shortcodes found.

## Show the Title ##

This setting controls the visibility of the widget title. If unchecked, the title (including the ```before_title``` and ```after_title``` code defined when registering the sidebar) will not be displayed.

## Installation ##
1. Upload the plugin files to the ```/wp-content/plugins/dmg-text-widget``` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' page in the WordPress admin area
1. Drag onto any active sidebar on the 'Appearance > Widgets' page
1. Click on the 'Show Advanced' link in the widget to change advanced options.

## Changelog ##
###1.1 ###
* Added option to make the title a link by adding a URL
* Tested with Wordpress 4.6
* Corrected typo's in readme.txt
* Update readme.txt
###1.0.1 ###
* Corrected typos in readme.txt
###1.0 ###
* Initial version