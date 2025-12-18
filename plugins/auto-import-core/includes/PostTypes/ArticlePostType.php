<?php
/**
 * Article Post Type
 *
 * @package AutoImportCore
 */

namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class ArticlePostType {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
        add_action('init', [$this, 'register_taxonomies']);
    }
    
    /**
     * Register post type
     */
    public function register() {
        $labels = [
            'name' => __('Articles', 'auto-import-core'),
            'singular_name' => __('Article', 'auto-import-core'),
            'menu_name' => __('Articles', 'auto-import-core'),
            'name_admin_bar' => __('Article', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Article', 'auto-import-core'),
            'new_item' => __('New Article', 'auto-import-core'),
            'edit_item' => __('Edit Article', 'auto-import-core'),
            'view_item' => __('View Article', 'auto-import-core'),
            'all_items' => __('All Articles', 'auto-import-core'),
            'search_items' => __('Search Articles', 'auto-import-core'),
            'not_found' => __('No articles found.', 'auto-import-core'),
            'not_found_in_trash' => __('No articles found in Trash.', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'blog', 'with_front' => false],
            'capability_type' => 'post',
            'has_archive' => 'blog',
            'hierarchical' => false,
            'menu_position' => 7,
            'menu_icon' => 'dashicons-admin-post',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author'],
            'show_in_rest' => true,
            'rest_base' => 'articles',
        ];
        
        register_post_type('article', $args);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Categories
        register_taxonomy('article_category', 'article', [
            'labels' => [
                'name' => __('Categories', 'auto-import-core'),
                'singular_name' => __('Category', 'auto-import-core'),
                'menu_name' => __('Categories', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'blog/category'],
            'show_in_rest' => true,
        ]);
        
        // Tags
        register_taxonomy('article_tag', 'article', [
            'labels' => [
                'name' => __('Tags', 'auto-import-core'),
                'singular_name' => __('Tag', 'auto-import-core'),
                'menu_name' => __('Tags', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'blog/tag'],
            'show_in_rest' => true,
        ]);
    }
}
