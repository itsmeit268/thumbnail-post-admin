<?php
/*
Plugin Name: Post Thumbnails Admin
Plugin URI: https://itsmeit.co/
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
    function __construct() {
        add_action('init', array($this, 'thumbnailPostAdmin'), 99);
    }

    public function thumbnailPostAdmin()
    {
        add_filter('manage_posts_columns', 'add_thumbnail_column', 10, 1);
        function add_thumbnail_column($columns)
        {
            $new_columns = array();
            $new_columns['thumbnail'] = __('Ảnh đại diện', 'text-domain');
            $columns = array_merge($new_columns, $columns);
            return $columns;
        }

        add_action('manage_posts_custom_column', 'display_thumbnail_column', 10, 2);
        function display_thumbnail_column($column, $post_id)
        {
            if ($column === 'thumbnail') {
                echo get_the_post_thumbnail($post_id, array(120, 9999));
            }
        }
    }
}

new thumbnailPostAdmin();

