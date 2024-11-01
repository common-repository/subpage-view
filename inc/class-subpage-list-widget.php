<?php
/**
 * The "Subpage List" widget
 */
class SubpageListWidget extends WP_Widget
{
    /**
     * The construct part
     */
    public function __construct()
    {
        parent::__construct('subpage_list_widget', __('Subpage List', 'subpage_list'), array(
            'description' => __('Add a list/menu of subpages.', 'subpage_list')
        ));
    }

    /**
     * Register widget and add attribute filters.
     * @return void
     */
    public static function init()
    {
        register_widget('SubpageListWidget');

        // sets the defualt of subpage_attr_{name}_options filters
        add_filter('subpage_list_show_date_options', array('SubpageListWidget', 'subpage_list_show_date_options'));
        add_filter('subpage_list_post_type_options', array('SubpageListWidget', 'subpage_list_post_type_options'));
        add_filter('subpage_list_post_status_options', array('SubpageListWidget', 'subpage_list_post_status_options'));
        add_filter('subpage_list__fallback_parent_options', array('SubpageListWidget', 'subpage_list__fallback_parent_options'));
    }

    /**
     * Creating widget frontend.
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget($args, $instance)
    {
        global $wp_query;

        // check for display options
        if (isset($instance['only_pages'])) {
            $only_pages = array_map('trim', explode(',', $instance['only_pages']));
            if (!in_array($wp_query->queried_object->ID, $only_pages)) {
                return;
            }

            unset($instance['only_pages']);
        }

        // display shortcode output
        $html = call_user_func(array('SubpageListShortcode', 'list_pages'), $instance);
        printf('%s%s%s', $args['before_widget'], $html, $args['after_widget']);
    }

    /**
     * Show date options.
     * @return array
     */
    public function subpage_list_show_date_options()
    {
        return array(
            ''          => __('None', 'subpage_list'),
            'published' => __('Published', 'subpage_list'),
            'modified'  => __('Modified', 'subpage_list'),
        );
    }

    /**
     * Post type options. Only public and hierarchical post types will be displayed.
     * @return array
     */
    public function subpage_list_post_type_options()
    {
        // ...
        $options = array();
        foreach (get_post_types([], 'objects') as $post_type) {
            if (!$post_type->public || !$post_type->hierarchical) {
                continue;
            }

            $options[$post_type->name] = $post_type->labels->singular_name;
        }

        return $options;
    }

    /**
     * Post status options.
     * @return array
     */
    public function subpage_list_post_status_options()
    {
        return get_post_statuses();
    }

    /**
     * Show date options.
     * @return array
     */
    public function subpage_list__fallback_parent_options()
    {
        return array(
            'false' => __('Off', 'subpage_list'),
            'true'  => __('On', 'subpage_list'),
        );
    }

    /**
     * Render a text field.
     * @param array $attr
     * @param array $instance
     * @return void
     */
    private function text($attr, $instance)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($attr['name']); ?>"><?php echo $attr['label']; ?>:</label>
            <br><span style="font-size: 10px; color: #72777c;"><?php echo $attr['desc']; ?></span>
            <input class="widefat" id="<?php echo $this->get_field_id($attr['name']); ?>" name="<?php echo $this->get_field_name($attr['name']); ?>" type="text" value="<?php echo esc_attr(SubpageList::get_attr($attr['name'], $instance)); ?>" />
        </p>
        <?php
    }

    /**
     * Render a number field.
     * @param array $attr
     * @param array $instance
     * @return void
     */
    private function number($attr, $instance)
    {
        ?>
        <p>
			<label for="<?php echo $this->get_field_id($attr['name']); ?>"><?php echo $attr['label']; ?>:</label>
            <br><span style="font-size: 10px; color: #72777c;"><?php echo $attr['desc']; ?></span>
			<input class="widefat" id="<?php echo $this->get_field_id($attr['name']); ?>" name="<?php echo $this->get_field_name($attr['name']); ?>" type="number" step="1" min="0" value="<?php echo esc_attr(absint(SubpageList::get_attr($attr['name'], $instance))); ?>" size="3">
        </p>
        <?php
    }

    /**
     * Render a select field.
     * @param array $attr
     * @param array $instance
     * @return void
     */
    private function select($attr, $instance)
    {
        $options = apply_filters(sprintf('subpage_list_%s_options', $attr['name']), array());
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($attr['name']); ?>"><?php echo $attr['label']; ?>:</label>
            <br><span style="font-size: 10px; color: #72777c;"><?php echo $attr['desc']; ?></span>
            <select class="widefat" id="<?php echo $this->get_field_id($attr['name']); ?>" name="<?php echo $this->get_field_name($attr['name']); ?>">
                <?php
                foreach($options as $key => $value) {
                    printf('<option value="%s" %s>%s</option>', $key, selected(SubpageList::get_attr($attr['name'], $instance), $key), $value);
                }
                ?>
            </select>
        </p>
        <?php
    }

    /**
     * Render a checkbox list.
     * @param array $attr
     * @param array $instance
     * @return void
     */
    private function checklist($attr, $instance)
    {
        $options = apply_filters(sprintf('subpage_list_%s_options', $attr['name']), array());
        ?>
        <p>
            <label for="<?php echo $this->get_field_id($attr['name']); ?>"><?php echo $attr['label']; ?>:</label>
            <br><span style="font-size: 10px; color: #72777c; margin-bottom: 1em; display: block;"><?php echo $attr['desc']; ?></span>
            <?php
            foreach($options as $key => $value) {
                printf('<input class="checkbox" type="checkbox" id="%s" name="%s[]" value="%s" %s><label for="%s">%s</label><br>', $this->get_field_id($attr['name']), $this->get_field_name($attr['name']), $key, checked(true, in_array($key, SubpageList::get_attr($attr['name'], $instance)), false), $this->get_field_id($attr['name']), $value);
            }
            ?>
        </p>
        <?php
    }

    /**
     * Creating widget backend
     * @param array $instance
     * @return void
     */
    public function form($instance)
    {
        $this->text(array(
            'name'  => 'title_li',
            'label' => __('Title', 'subpage_list'),
            'desc'  => __('List heading. An empty value will result in no heading.', 'subpage_list'),
        ), $instance);

        $this->number(array(
            'name'  => 'depth',
            'label' => __('Depth', 'subpage_list'),
            'desc'  => __('Number of levels in the hierarchy of pages to include in the generated list. Default 0 (all).', 'subpage_list'),
        ), $instance);

        $this->select(array(
            'name'  => 'show_date',
            'label' => __('Show date', 'subpage_list'),
            'desc'  => __('Whether to display the page publish or modified date for each page.', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'date_format',
            'label' => __('Date format', 'subpage_list'),
            'desc'  => sprintf(__('PHP <a href="%s" target="_blank">date format</a> to use for the listed pages. Relies on the "Show date" parameter. Default empty (value of <a href="%s" target="blank">"date_format" option</a>).', 'subpage_list'), 
                'https://www.php.net/manual/en/datetime.format.php', 
                admin_url('/options-general.php')
            ),
        ), $instance);

        $this->number(array(
            'name'  => 'child_of',
            'label' => __('Child of', 'subpage_list'),
            'desc'  => __('Display only the sub-pages of a single page by ID. Default 0 (all).', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'exclude',
            'label' => __('Exclude', 'subpage_list'),
            'desc'  => __('Comma-separated list of page IDs to exclude. Default empty (none).', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'authors',
            'label' => __('Authors', 'subpage_list'),
            'desc'  => __('Comma-separated list of author IDs. Default empty (all).', 'subpage_list'),
        ), $instance);
        ?>
        <hr style="margin: 25px 0px 0px 0px;">
        <h3><?php _e('Advanced', 'subpage_list'); ?></h3>
        <?php
        $this->text(array(
            'name'  => 'only_pages',
            'label' => __('Display on pages', 'subpage_list'),
            'desc'  => __('Comma-separated list of page IDs. Default empty (all).', 'subpage_list'),
        ), $instance);

        $this->select(array(
            'name'  => '_fallback_parent',
            'label' => __('Fallback to parent', 'subpage_list'),
            'desc'  => __('If current page has no subpages, subpages of the parent page will be listed instead', 'subpage_list'),
        ), $instance);

        $this->select(array(
            'name'  => 'post_type',
            'label' => __('Post type', 'subpage_list'),
            'desc'  => __('Post type to query for. Default "page".<br>(Only post types that are both public and hierarchical is listed).', 'subpage_list'),
        ), $instance);

        $this->checklist(array(
            'name'  => 'post_status',
            'label' => __('Post status', 'subpage_list'),
            'desc'  => __('List of post statuses to include. Default "publish".', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'link_before',
            'label' => __('Before link', 'subpage_list'),
            'desc'  => __('Text or HTML to precede the link label. Default empty.', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'link_after',
            'label' => __('After link', 'subpage_list'),
            'desc'  => __('Text or HTML to follow the link label. Default empty.', 'subpage_list'),
        ), $instance);

        $this->text(array(
            'name'  => 'walker',
            'label' => __('Walker', 'subpage_list'),
            'desc'  => __('Walker instance to use for listing pages. Default empty.', 'subpage_list'),
        ), $instance);
    }

    /**
     * Updating widget replacing old instances with new
     * @param array $new_instance
     * @param array $old_instance
     * @return void
     */
    public function update($new_instance, $old_instance)
    {
        return array_filter(array(
            'title_li'    => SubpageList::get_attr('title_li', $new_instance),
            'depth'       => absint(SubpageList::get_attr('depth', $new_instance)),
            'show_date'   => strip_tags(SubpageList::get_attr('show_date', $new_instance)),
            'date_format' => strip_tags(SubpageList::get_attr('date_format', $new_instance)),
            'child_of'    => absint(SubpageList::get_attr('child_of', $new_instance)),
            'exclude'     => strip_tags(SubpageList::get_attr('exclude', $new_instance)),
            'authors'     => strip_tags(SubpageList::get_attr('authors', $new_instance)),
            'post_type'   => strip_tags(SubpageList::get_attr('post_type', $new_instance)),
            'post_status' => SubpageList::get_attr('post_status', $new_instance),
            'link_before' => SubpageList::get_attr('link_before', $new_instance),
            'link_after'  => SubpageList::get_attr('link_after', $new_instance),
            'walker'      => strip_tags(SubpageList::get_attr('walker', $new_instance)),
            'only_pages'  => strip_tags(SubpageList::get_attr('only_pages', $new_instance)),

            // ...
            '_fallback_parent'  => strip_tags(SubpageList::get_attr('_fallback_parent', $new_instance)),
        ));
    }
}
