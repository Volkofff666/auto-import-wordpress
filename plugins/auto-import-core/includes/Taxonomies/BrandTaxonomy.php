<?php
/**
 * Brand Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class BrandTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Brands', 'auto-import-core'),
            'singular_name' => __('Brand', 'auto-import-core'),
            'search_items' => __('Search Brands', 'auto-import-core'),
            'all_items' => __('All Brands', 'auto-import-core'),
            'parent_item' => __('Parent Brand', 'auto-import-core'),
            'parent_item_colon' => __('Parent Brand:', 'auto-import-core'),
            'edit_item' => __('Edit Brand', 'auto-import-core'),
            'update_item' => __('Update Brand', 'auto-import-core'),
            'add_new_item' => __('Add New Brand', 'auto-import-core'),
            'new_item_name' => __('New Brand Name', 'auto-import-core'),
            'menu_name' => __('Brands', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
            'rest_base' => 'brands',
            'rewrite' => [
                'slug' => 'cars/brand',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('brand', 'car', $args);
    }
}
