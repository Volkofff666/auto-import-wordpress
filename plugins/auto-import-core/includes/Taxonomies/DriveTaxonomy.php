<?php
/**
 * Drive Type Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class DriveTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Drive Types', 'auto-import-core'),
            'singular_name' => __('Drive Type', 'auto-import-core'),
            'search_items' => __('Search Drive Types', 'auto-import-core'),
            'all_items' => __('All Drive Types', 'auto-import-core'),
            'edit_item' => __('Edit Drive Type', 'auto-import-core'),
            'update_item' => __('Update Drive Type', 'auto-import-core'),
            'add_new_item' => __('Add New Drive Type', 'auto-import-core'),
            'new_item_name' => __('New Drive Type Name', 'auto-import-core'),
            'menu_name' => __('Drive Types', 'auto-import-core'),
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
            'rest_base' => 'drive-types',
            'rewrite' => [
                'slug' => 'cars/drive',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('drive', 'car', $args);
    }
}
