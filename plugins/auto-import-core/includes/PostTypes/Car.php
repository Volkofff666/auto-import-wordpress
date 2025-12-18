<?php
namespace AIC\PostTypes;

class Car {
    
    public static function register() {
        register_post_type('car', [
            'labels' => [
                'name' => __('Автомобили', 'auto-import-core'),
                'singular_name' => __('Автомобиль', 'auto-import-core'),
                'add_new' => __('Добавить автомобиль', 'auto-import-core'),
                'add_new_item' => __('Добавить новый автомобиль', 'auto-import-core'),
                'edit_item' => __('Редактировать автомобиль', 'auto-import-core'),
                'new_item' => __('Новый автомобиль', 'auto-import-core'),
                'view_item' => __('Просмотреть автомобиль', 'auto-import-core'),
                'search_items' => __('Искать автомобили', 'auto-import-core'),
                'not_found' => __('Автомобили не найдены', 'auto-import-core'),
                'not_found_in_trash' => __('В корзине автомобилей не найдено', 'auto-import-core'),
                'menu_name' => __('Автомобили', 'auto-import-core'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'cars', 'with_front' => false],
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-car',
            'menu_position' => 5,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'taxonomies' => [
                'car_brand',
                'car_model',
                'car_body',
                'car_fuel',
                'car_transmission',
                'car_drive',
                'car_status',
                'car_location'
            ],
        ]);
        
        // Register meta fields
        self::register_meta_fields();
    }
    
    private static function register_meta_fields() {
        $meta_fields = [
            'price_rub' => ['type' => 'number', 'default' => 0],
            'year' => ['type' => 'number', 'default' => date('Y')],
            'mileage_km' => ['type' => 'number', 'default' => 0],
            'vin' => ['type' => 'string', 'default' => ''],
            'engine_volume' => ['type' => 'string', 'default' => ''],
            'engine_power_hp' => ['type' => 'number', 'default' => 0],
            'color' => ['type' => 'string', 'default' => ''],
            'steering' => ['type' => 'string', 'default' => 'left'],
            'owners' => ['type' => 'number', 'default' => 0],
            'condition' => ['type' => 'string', 'default' => ''],
            'customs_status' => ['type' => 'string', 'default' => 'not_cleared'],
            'documents' => ['type' => 'string', 'default' => ''],
            'equipment' => ['type' => 'array', 'default' => []],
            'description_long' => ['type' => 'string', 'default' => ''],
            'gallery' => ['type' => 'array', 'default' => []],
            'video_url' => ['type' => 'string', 'default' => ''],
            'publish_to_catalog' => ['type' => 'boolean', 'default' => true],
        ];
        
        foreach ($meta_fields as $key => $args) {
            register_post_meta('car', $key, [
                'show_in_rest' => true,
                'single' => true,
                'type' => $args['type'],
                'default' => $args['default'],
                'sanitize_callback' => [self::class, 'sanitize_meta_' . $args['type']],
            ]);
        }
    }
    
    public static function sanitize_meta_string($value) {
        return sanitize_text_field($value);
    }
    
    public static function sanitize_meta_number($value) {
        return absint($value);
    }
    
    public static function sanitize_meta_boolean($value) {
        return (bool) $value;
    }
    
    public static function sanitize_meta_array($value) {
        return is_array($value) ? array_map('sanitize_text_field', $value) : [];
    }
}