<?php
/**
 * Location Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class LocationTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Locations', 'auto-import-core'),
            'singular_name' => __('Location', 'auto-import-core'),
            'search_items' => __('Search Locations', 'auto-import-core'),
            'all_items' => __('All Locations', 'auto-import-core'),
            'edit_item' => __('Edit Location', 'auto-import-core'),
            'update_item' => __('Update Location', 'auto-import-core'),
            'add_new_item' => __('Add New Location', 'auto-import-core'),
            'new_item_name' => __('New Location Name', 'auto-import-core'),
            'menu_name' => __('Locations', 'auto-import-core'),
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
            'rest_base' => 'locations',
            'rewrite' => [
                'slug' => 'cars/location',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('location', 'car', $args);
    }
}
