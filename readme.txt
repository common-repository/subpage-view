=== Subpage List ===
Contributors: codework
Plugin homepage: https://urlund.com
Tags: subpage, view, list, page, mce, gutenberg, post, custom, shortcode
Requires at least: 4.7
Tested up to: 5.5.1
Stable tag: 1.3.3

Using widgets, shortcodes and a built-in WordPress method, this plugin enables you to efficiently show a table of contents or a list of subpages to a given page. View <a href="http://codex.wordpress.org/Template_Tags/wp_list_pages">WordPress Codex</a> for configuration details.

== Description ==

If you ever needs to place a table of contents or list of subpages on your website, this is the plugin you need.

Using widgets, shortcodes and a built-in WordPress method, this plugin enables you to efficiently show a table of contents or a list of subpages to a given page. View <a href="http://codex.wordpress.org/Template_Tags/wp_list_pages">WordPress Codex</a> for configuration details.

= Widget features =

- Limit to display on specific pages
- Fallback to parent (If current page has no subpages, subpages of the parent page will be listed instead - usefull for sidebar menus)
- Subpage List configuration (<a href="http://codex.wordpress.org/Template_Tags/wp_list_pages">wp_list_pages</a>)

= Shortcode examples =

- [subpages]
- [subpages depth="1"]
- [subpages title_li="Subpages:" child_of="24"]
- [subpages walker="SubpageListExcerptWalker"]
- [subpages depth="1" _parent_fallback="true"]

= Note =

Plugin is renamed from "Subpage View" to "Subpage List" - if you used this plugin before, you are still able to use the `subpage-view` and `subpage-list` shortcodes.

== Installation ==

Easy installation:<br />
1. Upload `subpage-view.php` to the `/wp-content/plugins/` directory<br />
2. Activate the plugin through the 'Plugins' menu in WordPress<br />
3. Place [subpage-view] (or any valid shortcode) in your editor (see Description).<br />
