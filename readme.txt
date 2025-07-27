=== Content Expiry Notice ===
Contributors: junie
Tags: expiry, expiration, notice, content, date, time-sensitive
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add expiration dates to your posts and pages and display customizable notices when content is approaching expiration or has expired.

== Description ==

Content Expiry Notice is a lightweight and user-friendly plugin that allows you to set expiration dates for your WordPress content and display customizable notices to your visitors when that content is approaching expiration or has already expired.

= Features =

* Add expiration dates to posts and pages
* Choose which post types support expiration dates
* Customize how many days before expiration to show notices
* Select where to display notices (above or below content)
* Choose from different notice styles (info, warning, error)
* Customize the text for both upcoming and expired notices
* Use shortcode [content_expiry_notice] for manual placement
* Responsive design that looks great on all devices
* Lightweight with minimal impact on page load time

= Use Cases =

* Event announcements that need to be marked as expired after the event date
* Time-limited offers or promotions
* Content that requires regular review or updates
* Legal notices that have an expiration date
* Seasonal content that becomes irrelevant after a certain date

== Installation ==

1. Upload the `content-expiry-notice` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Content Expiry Notice to configure the plugin
4. Edit any post or page and set an expiration date using the "Content Expiry Date" meta box

== Frequently Asked Questions ==

= How do I set an expiration date for a post or page? =

When editing a post or page, you'll find a "Content Expiry Date" meta box in the sidebar. Simply select a date using the date picker. Leave it empty if you don't want the content to expire.

= Can I control where the notices appear? =

Yes, you can choose to display the notices above your content or below your content in the plugin settings.

= Can I use a shortcode to place the notice manually? =

Yes, you can use the `[content_expiry_notice]` shortcode to manually place the notice anywhere in your content or in widget areas that support shortcodes. By default, it will use the current post's ID, but you can specify a different post with `[content_expiry_notice id="123"]`.

= Will notices show for all visitors or just administrators? =

Notices will show for all visitors to your site when viewing content that has an expiration date set and is either approaching expiration or has already expired.

= Can I customize the appearance of the notices? =

Yes, you can choose from three different notice styles: Info (Blue), Warning (Yellow), or Error (Red). You can also customize the appearance further by adding custom CSS to your theme.

= Does this plugin slow down my website? =

No, Content Expiry Notice is designed to be lightweight and fast. It only loads a small CSS file on the frontend and doesn't use any JavaScript.

== Screenshots ==

1. Setting an expiration date in the post editor
2. Plugin settings page
3. Example of an upcoming expiry notice
4. Example of an expired notice

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release