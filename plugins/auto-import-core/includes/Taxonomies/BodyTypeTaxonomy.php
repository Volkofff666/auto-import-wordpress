<?php
/**
 * Body Type Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class BodyTypeTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Body Types', 'auto-import-core'),
            'singular_name' => __('Body Type', 'auto-import-core'),
            'search_items' => __('Search Body Types', 'auto-import-core'),
            'all_items' => __('All Body Types', 'auto-import-core'),
            'edit_item' => __('Edit Body Type', 'auto-import-core'),
            'update_item' => __('Update Body Type', 'auto-import-core'),
            'add_new_item' => __('Add New Body Type', 'auto-import-core'),
            'new_item_name' => __('New Body Type Name', 'auto-import-core'),
            'menu_name' => __('Body Types', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
            'rest_base' => 'body-types',
            'rewrite' => [
                'slug' => 'cars/body-type',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('body_type', 'car', $args);
    }
}
