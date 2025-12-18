<?php
/**
 * Model Taxonomy
 *
 * @package AutoImportCore
 */

namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class ModelTaxonomy {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
    }
    
    /**
     * Register taxonomy
     */
    public function register() {
        $labels = [
            'name' => __('Models', 'auto-import-core'),
            'singular_name' => __('Model', 'auto-import-core'),
            'search_items' => __('Search Models', 'auto-import-core'),
            'all_items' => __('All Models', 'auto-import-core'),
            'parent_item' => __('Parent Model', 'auto-import-core'),
            'parent_item_colon' => __('Parent Model:', 'auto-import-core'),
            'edit_item' => __('Edit Model', 'auto-import-core'),
            'update_item' => __('Update Model', 'auto-import-core'),
            'add_new_item' => __('Add New Model', 'auto-import-core'),
            'new_item_name' => __('New Model Name', 'auto-import-core'),
            'menu_name' => __('Models', 'auto-import-core'),
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
            'rest_base' => 'models',
            'rewrite' => [
                'slug' => 'cars/model',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy('model', 'car', $args);
    }
}
