<?php
/**
 * Fuel Type Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class FuelTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Fuel Types', 'auto-import-core'),
            'singular_name' => __('Fuel Type', 'auto-import-core'),
            'search_items' => __('Search Fuel Types', 'auto-import-core'),
            'all_items' => __('All Fuel Types', 'auto-import-core'),
            'edit_item' => __('Edit Fuel Type', 'auto-import-core'),
            'update_item' => __('Update Fuel Type', 'auto-import-core'),
            'add_new_item' => __('Add New Fuel Type', 'auto-import-core'),
            'new_item_name' => __('New Fuel Type Name', 'auto-import-core'),
            'menu_name' => __('Fuel Types', 'auto-import-core'),
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
            'rest_base' => 'fuel-types',
            'rewrite' => [
                'slug' => 'cars/fuel',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('fuel', 'car', $args);
    }
}
