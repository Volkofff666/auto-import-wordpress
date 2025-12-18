<?php
namespace AIC\Taxonomies;

class CarTaxonomies {
    
    public static function register() {
        self::register_brand();
        self::register_model();
        self::register_body();
        self::register_fuel();
        self::register_transmission();
        self::register_drive();
        self::register_status();
        self::register_location();
    }
    
    private static function register_brand() {
        register_taxonomy('car_brand', 'car', [
            'labels' => [
                'name' => __('Марки', 'auto-import-core'),
                'singular_name' => __('Марка', 'auto-import-core'),
                'search_items' => __('Искать марки', 'auto-import-core'),
                'all_items' => __('Все марки', 'auto-import-core'),
                'edit_item' => __('Редактировать марку', 'auto-import-core'),
                'add_new_item' => __('Добавить марку', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'brand'],
        ]);
    }
    
    private static function register_model() {
        register_taxonomy('car_model', 'car', [
            'labels' => [
                'name' => __('Модели', 'auto-import-core'),
                'singular_name' => __('Модель', 'auto-import-core'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'model'],
        ]);
    }
    
    private static function register_body() {
        register_taxonomy('car_body', 'car', [
            'labels' => [
                'name' => __('Типы кузова', 'auto-import-core'),
                'singular_name' => __('Тип кузова', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'body'],
        ]);
    }
    
    private static function register_fuel() {
        register_taxonomy('car_fuel', 'car', [
            'labels' => [
                'name' => __('Топливо', 'auto-import-core'),
                'singular_name' => __('Топливо', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => false,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'fuel'],
        ]);
    }
    
    private static function register_transmission() {
        register_taxonomy('car_transmission', 'car', [
            'labels' => [
                'name' => __('КПП', 'auto-import-core'),
                'singular_name' => __('КПП', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'transmission'],
        ]);
    }
    
    private static function register_drive() {
        register_taxonomy('car_drive', 'car', [
            'labels' => [
                'name' => __('Привод', 'auto-import-core'),
                'singular_name' => __('Привод', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'drive'],
        ]);
    }
    
    private static function register_status() {
        register_taxonomy('car_status', 'car', [
            'labels' => [
                'name' => __('Статус', 'auto-import-core'),
                'singular_name' => __('Статус', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'status'],
        ]);
    }
    
    private static function register_location() {
        register_taxonomy('car_location', 'car', [
            'labels' => [
                'name' => __('Локация', 'auto-import-core'),
                'singular_name' => __('Локация', 'auto-import-core'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'location'],
        ]);
    }
}