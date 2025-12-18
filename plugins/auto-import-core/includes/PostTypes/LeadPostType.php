<?php
namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class LeadPostType {
    
    public static function init() {
        add_action('init', [self::class, 'register']);
    }
    
    public static function register() {
        $labels = [
            'name' => __('Leads', 'auto-import-core'),
            'singular_name' => __('Lead', 'auto-import-core'),
            'menu_name' => __('Leads', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Lead', 'auto-import-core'),
            'edit_item' => __('Edit Lead', 'auto-import-core'),
            'new_item' => __('New Lead', 'auto-import-core'),
            'view_item' => __('View Lead', 'auto-import-core'),
            'search_items' => __('Search Leads', 'auto-import-core'),
            'not_found' => __('No leads found', 'auto-import-core'),
            'not_found_in_trash' => __('No leads found in trash', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 26,
            'menu_icon' => 'dashicons-email',
            'supports' => ['title'],
            'show_in_rest' => false,
        ];
        
        register_post_type('lead', $args);
    }
}