<?php
/**
 * Transmission Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class TransmissionTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Transmissions', 'auto-import-core'),
            'singular_name' => __('Transmission', 'auto-import-core'),
            'search_items' => __('Search Transmissions', 'auto-import-core'),
            'all_items' => __('All Transmissions', 'auto-import-core'),
            'edit_item' => __('Edit Transmission', 'auto-import-core'),
            'update_item' => __('Update Transmission', 'auto-import-core'),
            'add_new_item' => __('Add New Transmission', 'auto-import-core'),
            'new_item_name' => __('New Transmission Name', 'auto-import-core'),
            'menu_name' => __('Transmissions', 'auto-import-core'),
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
            'rest_base' => 'transmissions',
            'rewrite' => [
                'slug' => 'cars/transmission',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('transmission', 'car', $args);
    }
}
