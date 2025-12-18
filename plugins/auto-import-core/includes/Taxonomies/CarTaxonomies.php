<?php
namespace AIC\Taxonomies;

if (!defined('ABSPATH')) {
    exit;
}

class CarTaxonomies {
    
    public static function init() {
        add_action('init', [self::class, 'register']);
    }
    
    public static function register() {
        // Brand
        register_taxonomy('car_brand', 'car', [
            'labels' => [
                'name' => __('Brands', 'auto-import-core'),
                'singular_name' => __('Brand', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-brand'],
            'show_in_rest' => true,
        ]);
        
        // Model
        register_taxonomy('car_model', 'car', [
            'labels' => [
                'name' => __('Models', 'auto-import-core'),
                'singular_name' => __('Model', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-model'],
            'show_in_rest' => true,
        ]);
        
        // Body Type
        register_taxonomy('car_body', 'car', [
            'labels' => [
                'name' => __('Body Types', 'auto-import-core'),
                'singular_name' => __('Body Type', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-body'],
            'show_in_rest' => true,
        ]);
        
        // Fuel Type
        register_taxonomy('car_fuel', 'car', [
            'labels' => [
                'name' => __('Fuel Types', 'auto-import-core'),
                'singular_name' => __('Fuel Type', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-fuel'],
            'show_in_rest' => true,
        ]);
        
        // Transmission
        register_taxonomy('car_transmission', 'car', [
            'labels' => [
                'name' => __('Transmissions', 'auto-import-core'),
                'singular_name' => __('Transmission', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-transmission'],
            'show_in_rest' => true,
        ]);
        
        // Drive Type
        register_taxonomy('car_drive', 'car', [
            'labels' => [
                'name' => __('Drive Types', 'auto-import-core'),
                'singular_name' => __('Drive Type', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-drive'],
            'show_in_rest' => true,
        ]);
        
        // Status
        register_taxonomy('car_status', 'car', [
            'labels' => [
                'name' => __('Statuses', 'auto-import-core'),
                'singular_name' => __('Status', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-status'],
            'show_in_rest' => true,
        ]);
        
        // Location
        register_taxonomy('car_location', 'car', [
            'labels' => [
                'name' => __('Locations', 'auto-import-core'),
                'singular_name' => __('Location', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'car-location'],
            'show_in_rest' => true,
        ]);
    }
}