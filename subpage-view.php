<?php
/*
PLUGIN NAME: Subpage List
Plugin URI: https://wordpress.org/plugins/subpage-view/
DESCRIPTION: Using widgets, shortcodes and a built-in WordPress method, this plugin enables you to efficiently show a table of contents or a list of subpages to a given page. View <a href="http://codex.wordpress.org/Template_Tags/wp_list_pages">WordPress Codex</a> for configuration details.
AUTHOR: Henrik Urlund
AUTHOR URI: https://urlund.com/plugins/
VERSION: 1.3.3

Copyright 2007-2020 Henrik Urlund

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once('inc/class-subpage-list-excerpt-walker.php');
require_once('inc/class-subpage-list-shortcode.php');
require_once('inc/class-subpage-list-widget.php');
require_once('inc/class-subpage-list.php');

SubpageList::init();
