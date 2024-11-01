<?php
/**
 * The main "Subpage List" class
 */
class SubpageList
{
    /**
     * Init shortcode and widget.
     * @return void
     */
    public static function init()
    {
        add_action('init', array('SubpageListShortcode', 'init'));

        if (!apply_filters('subpage_list_disable_widget', false)) {
            add_action('widgets_init', array('SubpageListWidget', 'init'));
        }
    }

    /**
     * Get configured or default attribute value.
     * @param string $name
     * @param array $config
     * @return mixed
     */
    public static function get_attr($name, $config = array())
    {
        // if config already exists - used by widget
        if (isset($config[$name]) && !empty($config[$name])) {
            return $config[$name];
        }

        // default values
        switch ($name) {
            case 'depth':
            case 'child_of':
                return 0;
            case 'post_type':
                return 'page';
            case 'post_status':
                return array('publish');
            case 'item_spacing':
                return 'preserve';
            case 'sort_column':
                return 'menu_order, post_title';
            case 'echo':
                return false;
            case '_fallback_parent':
                return 'false';
            default:
                return '';
        }
    }
}
