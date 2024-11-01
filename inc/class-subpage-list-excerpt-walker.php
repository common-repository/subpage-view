<?php
/**
 * Undocumented class
 */
class SubpageListExcerptWalker extends Walker_Page
{
    public function end_el(&$output, $page, $depth = 0, $args = array())
    {
        $output .= '<br>'. get_the_excerpt($page);
        parent::end_el($output, $page, $depth, $args);
    }
}
