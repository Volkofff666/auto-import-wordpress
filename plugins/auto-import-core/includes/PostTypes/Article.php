<?php
namespace AIC\PostTypes;

class Article {
    
    public static function register() {
        register_post_type('article', [
            'labels' => [
                'name' => __('Статьи', 'auto-import-core'),
                'singular_name' => __('Статья', 'auto-import-core'),
                'add_new' => __('Добавить статью', 'auto-import-core'),
                'add_new_item' => __('Добавить новую статью', 'auto-import-core'),
                'edit_item' => __('Редактировать статью', 'auto-import-core'),
                'view_item' => __('Просмотреть статью', 'auto-import-core'),
                'search_items' => __('Искать статьи', 'auto-import-core'),
                'menu_name' => __('Блог', 'auto-import-core'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'blog', 'with_front' => false],
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-welcome-write-blog',
            'menu_position' => 7,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'],
            'taxonomies' => ['category', 'post_tag'],
        ]);
    }
}