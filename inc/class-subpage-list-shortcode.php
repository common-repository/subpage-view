<?php
/**
 * The "original" plugin
 */
class SubpageListShortcode
{
    /**
     * Initialize plugin shortcodes
     */
    public static function init()
    {
        // preferred shortcode
        add_shortcode('subpages', array('SubpageListShortcode', 'list_pages'));

        // backwards compatibility
        add_shortcode('subpage-view', array('SubpageListShortcode', 'list_pages'));
        add_shortcode('subpage-list', array('SubpageListShortcode', 'list_pages'));
    }

    /**
     * Generate a list of subpages
     * @param array $atts
     * @return void
     */
    public static function list_pages($atts)
    {
        global $wp_query;

        // apply filter for backwards compatibility
        $default_atts = apply_filters('subpage_list_default_atts', array(
            'depth'        => SubpageList::get_attr('depth'),
            'show_date'    => SubpageList::get_attr('show_date'),
            'date_format'  => get_option('date_format'),
            'child_of'     => $wp_query->queried_object->ID,
            'exclude'      => SubpageList::get_attr('exclude'),
            'title_li'     => SubpageList::get_attr('title_li'),
            'authors'      => SubpageList::get_attr('authors'),
            'sort_column'  => SubpageList::get_attr('sort_column'),
            'link_before'  => SubpageList::get_attr('link_before'),
            'link_after'   => SubpageList::get_attr('link_after'),
            'post_type'    => SubpageList::get_attr('post_type'),
            'post_status'  => SubpageList::get_attr('post_status'),
            'item_spacing' => SubpageList::get_attr('item_spacing'),
            'walker'       => SubpageList::get_attr('walker'),
            'echo'         => SubpageList::get_attr('echo'),

            // ...
            '_fallback_parent' => false,
        ), 'subpages');

        // combine user attributes with known attributes
        $args = shortcode_atts($default_atts, $atts);

        // if walker is a string, create an instance, to use the named walker to render the menu items
        if (isset($args['walker']) && !empty($args['walker']) && is_string($args['walker'])) {
            $className = $args['walker'];
            if (!class_exists($className)) {
                return sprintf('<p style="font-style: italic; color: red;">%s</p>', sprintf(__('Error: Walker "%s" not found.', 'subpage_list'), $className));
            }

            $args['walker'] = new $className;
        }

        // if enabled, and current page has no subpages, subpages of the parent page will be shown instead
        if (isset($args['_fallback_parent']) && rest_sanitize_boolean($args['_fallback_parent'])) {
            $post_parent = $wp_query->queried_object->post_parent;
            if ($post_parent && self::has_subpages($args) === false) {
                $args['child_of'] = $post_parent;
            }

            unset($args['_fallback_parent']);
        }

        return sprintf(apply_filters('subpage_list_wrap', '<ul class="subpage_list">%s</ul>'),
            wp_list_pages($args)
        );
    }

    /**
     * Check if a page has children
     * @param array $args
     * @return boolean
     */
    public static function has_subpages($args)
    {
        // make sure we are dealing with an array
        if (!is_array($args)) {
            return;
        }

        // check for required array keys
        foreach (array( 'child_of', 'post_type' ) as $key) {
            if (!isset($args[$key])) {
                return;
            }
        }

        // we only need one, to check if post has children
        $query_args = array(
            'posts_per_page' => 1,
            'post_parent'    => $args['child_of'],
            'post_type'      => $args['post_type'],
        );

        // only include specific authors
        if (isset($args['authors']) && !empty($args['authors'])) {
            $query_args['author__in'] = $args['authors'];
        }

        // only include specific posts
        if (isset($args['include']) && !empty($args['include'])) {
            $query_args['post__in'] = $args['include'];
        }

        // exclude specific posts
        if (isset($args['exclude']) && !empty($args['exclude'])) {
            $query_args['post__not_in'] = $args['exclude'];
        }

        return (new WP_Query($query_args))->have_posts();
    }
}
