<?php
/**
 * Car Post Type
 *
 * @package AutoImportCore
 */

namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class CarPostType {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
        add_action('init', [$this, 'register_meta']);
    }
    
    /**
     * Register post type
     */
    public function register() {
        $labels = [
            'name' => __('Cars', 'auto-import-core'),
            'singular_name' => __('Car', 'auto-import-core'),
            'menu_name' => __('Cars', 'auto-import-core'),
            'name_admin_bar' => __('Car', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Car', 'auto-import-core'),
            'new_item' => __('New Car', 'auto-import-core'),
            'edit_item' => __('Edit Car', 'auto-import-core'),
            'view_item' => __('View Car', 'auto-import-core'),
            'all_items' => __('All Cars', 'auto-import-core'),
            'search_items' => __('Search Cars', 'auto-import-core'),
            'parent_item_colon' => __('Parent Cars:', 'auto-import-core'),
            'not_found' => __('No cars found.', 'auto-import-core'),
            'not_found_in_trash' => __('No cars found in Trash.', 'auto-import-core'),
            'featured_image' => __('Car Image', 'auto-import-core'),
            'set_featured_image' => __('Set car image', 'auto-import-core'),
            'remove_featured_image' => __('Remove car image', 'auto-import-core'),
            'use_featured_image' => __('Use as car image', 'auto-import-core'),
            'archives' => __('Car archives', 'auto-import-core'),
            'insert_into_item' => __('Insert into car', 'auto-import-core'),
            'uploaded_to_this_item' => __('Uploaded to this car', 'auto-import-core'),
            'filter_items_list' => __('Filter cars list', 'auto-import-core'),
            'items_list_navigation' => __('Cars list navigation', 'auto-import-core'),
            'items_list' => __('Cars list', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'cars', 'with_front' => false],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-car',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'show_in_rest' => true,
            'rest_base' => 'cars',
        ];
        
        register_post_type('car', $args);
    }
    
    /**
     * Register post meta
     */
    public function register_meta() {
        // Basic info
        $basic_fields = [
            'price' => 'integer',
            'year' => 'integer',
            'mileage' => 'integer',
            'vin' => 'string',
            'color' => 'string',
            'steering_wheel' => 'string',
            'owners' => 'integer',
        ];
        
        foreach ($basic_fields as $field => $type) {
            register_post_meta('car', 'aic_' . $field, [
                'type' => $type,
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => $type === 'integer' ? 'absint' : 'sanitize_text_field',
            ]);
        }
        
        // Technical specs
        $tech_fields = [
            'engine_volume' => 'number',
            'engine_power' => 'integer',
            'transmission_type' => 'string',
            'drive_type' => 'string',
            'fuel_type' => 'string',
        ];
        
        foreach ($tech_fields as $field => $type) {
            register_post_meta('car', 'aic_' . $field, [
                'type' => $type,
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => $type === 'integer' ? 'absint' : 'sanitize_text_field',
            ]);
        }
        
        // Documents
        register_post_meta('car', 'aic_customs_cleared', [
            'type' => 'boolean',
            'single' => true,
            'show_in_rest' => true,
        ]);
        
        register_post_meta('car', 'aic_title_status', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        register_post_meta('car', 'aic_condition', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        
        // Gallery
        register_post_meta('car', 'aic_gallery', [
            'type' => 'array',
            'single' => true,
            'show_in_rest' => [
                'schema' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'integer'
                    ]
                ]
            ],
        ]);
        
        // Equipment (one per line string)
        register_post_meta('car', 'aic_equipment', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        
        // Show in catalog
        register_post_meta('car', 'aic_show_in_catalog', [
            'type' => 'boolean',
            'single' => true,
            'show_in_rest' => true,
            'default' => true,
        ]);
    }
}
