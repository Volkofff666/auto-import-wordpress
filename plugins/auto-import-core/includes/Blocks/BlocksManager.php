<?php
namespace AIC\Blocks;

class BlocksManager {
    
    public static function init() {
        add_action('init', [self::class, 'register_blocks']);
        add_filter('block_categories_all', [self::class, 'register_category']);
    }
    
    public static function register_category($categories) {
        return array_merge(
            [
                [
                    'slug' => 'auto-import',
                    'title' => __('Auto Import Blocks', 'auto-import-core'),
                ],
            ],
            $categories
        );
    }
    
    public static function register_blocks() {
        $blocks = [
            'hero',
            'trust-bar',
            'car-grid',
            'lead-form',
            'articles-grid',
            'faq',
        ];
        
        foreach ($blocks as $block) {
            register_block_type(
                AIC_PLUGIN_DIR . 'blocks/' . $block
            );
        }
    }
}