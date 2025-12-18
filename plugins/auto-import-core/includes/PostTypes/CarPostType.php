<?php
namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class CarPostType {
    
    public static function init() {
        add_action('init', [self::class, 'register']);
    }
    
    public static function register() {
        $labels = [
            'name' => __('Cars', 'auto-import-core'),
            'singular_name' => __('Car', 'auto-import-core'),
            'menu_name' => __('Cars', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Car', 'auto-import-core'),
            'edit_item' => __('Edit Car', 'auto-import-core'),
            'new_item' => __('New Car', 'auto-import-core'),
            'view_item' => __('View Car', 'auto-import-core'),
            'search_items' => __('Search Cars', 'auto-import-core'),
            'not_found' => __('No cars found', 'auto-import-core'),
            'not_found_in_trash' => __('No cars found in trash', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'cars'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-car',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest' => true,
        ];
        
        register_post_type('car', $args);
    }
}