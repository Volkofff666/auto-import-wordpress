<?php
namespace AIC\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class BlocksManager {
    
    public static function init() {
        add_action('init', [self::class, 'register_blocks'], 20);
        add_filter('block_categories_all', [self::class, 'register_category'], 10, 2);
    }
    
    public static function register_category($categories, $context) {
        return array_merge(
            [
                [
                    'slug' => 'auto-import',
                    'title' => __('Auto Import Blocks', 'auto-import-core'),
                    'icon' => null,
                ],
            ],
            $categories
        );
    }
    
    public static function register_blocks() {
        if (!function_exists('register_block_type')) {
            return;
        }
        
        $blocks = [
            'hero',
            'trust-bar',
            'car-grid',
            'lead-form',
            'articles-grid',
            'faq',
        ];
        
        foreach ($blocks as $block) {
            $block_dir = AIC_PLUGIN_DIR . 'blocks/' . $block;
            $block_json = $block_dir . '/block.json';
            
            // Check if block.json exists
            if (file_exists($block_json)) {
                register_block_type($block_dir);
            } else {
                // Fallback: register without block.json
                register_block_type('aic/' . $block, [
                    'render_callback' => function($attributes) use ($block) {
                        $render_file = AIC_PLUGIN_DIR . 'blocks/' . $block . '/render.php';
                        
                        if (file_exists($render_file)) {
                            ob_start();
                            include $render_file;
                            return ob_get_clean();
                        }
                        
                        return '';
                    },
                ]);
            }
        }
    }
}