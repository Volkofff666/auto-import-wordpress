<?php
namespace AIC\PostTypes;

class Lead {
    
    public static function register() {
        register_post_type('lead', [
            'labels' => [
                'name' => __('Заявки', 'auto-import-core'),
                'singular_name' => __('Заявка', 'auto-import-core'),
                'add_new' => __('Добавить заявку', 'auto-import-core'),
                'edit_item' => __('Редактировать заявку', 'auto-import-core'),
                'view_item' => __('Просмотреть заявку', 'auto-import-core'),
                'search_items' => __('Искать заявки', 'auto-import-core'),
                'not_found' => __('Заявки не найдены', 'auto-import-core'),
                'menu_name' => __('Заявки', 'auto-import-core'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-email',
            'menu_position' => 6,
            'supports' => ['title'],
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => 'manage_options',
            ],
            'map_meta_cap' => true,
        ]);
        
        self::register_meta_fields();
    }
    
    private static function register_meta_fields() {
        $meta_fields = [
            'name' => ['type' => 'string', 'default' => ''],
            'phone' => ['type' => 'string', 'default' => ''],
            'email' => ['type' => 'string', 'default' => ''],
            'city' => ['type' => 'string', 'default' => ''],
            'source_page' => ['type' => 'string', 'default' => ''],
            'preferred_brand' => ['type' => 'string', 'default' => ''],
            'preferred_model' => ['type' => 'string', 'default' => ''],
            'year_from' => ['type' => 'number', 'default' => 0],
            'year_to' => ['type' => 'number', 'default' => 0],
            'budget' => ['type' => 'number', 'default' => 0],
            'status' => ['type' => 'string', 'default' => 'new'],
            'manager_note' => ['type' => 'string', 'default' => ''],
        ];
        
        foreach ($meta_fields as $key => $args) {
            register_post_meta('lead', $key, [
                'show_in_rest' => true,
                'single' => true,
                'type' => $args['type'],
                'default' => $args['default'],
            ]);
        }
    }
}