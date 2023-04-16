<?php
/*
Plugin Name: Post Thumbnails Admin
Plugin URI: https://github.com/itsmeit268/thumbnail-post-admin
Description: Show post image in Admin management page.
Version: 1.0.0
Author: itsmeit.co
Author URI: https://itsmeit.co/
Network: true
Text Domain: thumbnail-post-admin

Copyright 2023 itsmeit.co (email: buivanloi.2010@gmail.com)
*/

class thumbnailPostAdmin
{
    /**
     * @var array
     */
    private $excluded_posttypes = [];

    /**
     * thumbnailPostAdmin constructor.
     */
    public function __construct()
    {
        add_filter('manage_posts_columns', array($this, 'add_thumbnail_column'), 10, 1);
        add_action('manage_posts_custom_column', array($this, 'manage_posts_custom_column'), 10, 2);
    }

    /**
     * @return null
     */
    private function get_current_admin_post_type() {
        global $post, $typenow, $current_screen;
        return $post && $post->post_type ? $post->post_type : ($typenow ? $typenow : ($current_screen && $current_screen->post_type ? $current_screen->post_type : (isset($_REQUEST['post_type']) ? sanitize_key($_REQUEST['post_type']) : null)));
    }


    /**
     * @return array
     */
    private function get_excluded_post_types()
    {
        if (empty($this->excluded_posttypes)) {
            $this->excluded_posttypes = (array) apply_filters('thumbnail/exclude_posttype', $this->excluded_posttypes);
        }
        return $this->excluded_posttypes;
    }
    
    /**
     * @param $columns
     * @return array|mixed
     */
    public function add_thumbnail_column($columns)
    {
        $new_columns = array();
        $new_columns['thumbnail'] = __('Thumbnail', 'text-domain');
        if (!wp_is_mobile() && !in_array($this->get_current_admin_post_type(), $this->get_excluded_post_types())) {
            $columns = array_merge($new_columns, $columns);
        }
        return $columns;
    }


    /**
     * @param $column
     * @param $post_id
     */
    public function manage_posts_custom_column($column, $post_id)
    {
        if ($column === 'thumbnail' && has_post_thumbnail( $post_id ) && !in_array($this->get_current_admin_post_type(), $this->get_excluded_post_types()))
            echo get_the_post_thumbnail($post_id, array(120, 9999));
    }
}

new thumbnailPostAdmin();