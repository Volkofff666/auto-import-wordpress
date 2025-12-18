<?php
/**
 * Status Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class StatusTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Statuses', 'auto-import-core'),
            'singular_name' => __('Status', 'auto-import-core'),
            'search_items' => __('Search Statuses', 'auto-import-core'),
            'all_items' => __('All Statuses', 'auto-import-core'),
            'edit_item' => __('Edit Status', 'auto-import-core'),
            'update_item' => __('Update Status', 'auto-import-core'),
            'add_new_item' => __('Add New Status', 'auto-import-core'),
            'new_item_name' => __('New Status Name', 'auto-import-core'),
            'menu_name' => __('Statuses', 'auto-import-core'),
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
            'rest_base' => 'statuses',
            'rewrite' => [
                'slug' => 'cars/status',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('status', 'car', $args);
    }
}
