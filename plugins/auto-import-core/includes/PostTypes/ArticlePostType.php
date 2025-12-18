<?php
namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class ArticlePostType {
    
    public static function init() {
        add_action('init', [self::class, 'register']);
    }
    
    public static function register() {
        $labels = [
            'name' => __('Articles', 'auto-import-core'),
            'singular_name' => __('Article', 'auto-import-core'),
            'menu_name' => __('Articles', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Article', 'auto-import-core'),
            'edit_item' => __('Edit Article', 'auto-import-core'),
            'new_item' => __('New Article', 'auto-import-core'),
            'view_item' => __('View Article', 'auto-import-core'),
            'search_items' => __('Search Articles', 'auto-import-core'),
            'not_found' => __('No articles found', 'auto-import-core'),
            'not_found_in_trash' => __('No articles found in trash', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'blog'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-admin-post',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'comments'],
            'show_in_rest' => true,
        ];
        
        register_post_type('article', $args);
    }
}