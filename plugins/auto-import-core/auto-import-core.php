<?php
/**
 * Plugin Name: Auto Import Core
 * Plugin URI: https://github.com/Volkofff666/auto-import-wordpress
 * Description: Core functionality for Auto Import business: cars catalog, leads management, Gutenberg blocks
 * Version: 1.0.1
 * Author: Auto Import Team
 * Author URI: https://github.com/Volkofff666
 * Text Domain: auto-import-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('AIC_VERSION', '1.0.1');
define('AIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIC_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Simple autoloader without namespace issues
spl_autoload_register(function ($class) {
    // Only load AIC classes
    if (strpos($class, 'AIC\\') !== 0) {
        return;
    }
    
    // Remove namespace prefix
    $class = str_replace('AIC\\', '', $class);
    
    // Convert to file path
    $file = AIC_PLUGIN_DIR . 'includes/' . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Check if classes exist before initialization
function aic_classes_exist() {
    $required_files = [
        AIC_PLUGIN_DIR . 'includes/PostTypes/CarPostType.php',
        AIC_PLUGIN_DIR . 'includes/PostTypes/LeadPostType.php',
        AIC_PLUGIN_DIR . 'includes/PostTypes/ArticlePostType.php',
        AIC_PLUGIN_DIR . 'includes/Taxonomies/CarTaxonomies.php',
        AIC_PLUGIN_DIR . 'includes/Admin/CarAdmin.php',
        AIC_PLUGIN_DIR . 'includes/Admin/LeadAdmin.php',
        AIC_PLUGIN_DIR . 'includes/Admin/Settings.php',
        AIC_PLUGIN_DIR . 'includes/Blocks/BlocksManager.php',
        AIC_PLUGIN_DIR . 'includes/API/LeadAPI.php',
    ];
    
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            return false;
        }
    }
    
    return true;
}

// Initialize plugin
function aic_init() {
    // Load text domain
    load_plugin_textdomain('auto-import-core', false, dirname(AIC_PLUGIN_BASENAME) . '/languages');
    
    // Check if all required files exist
    if (!aic_classes_exist()) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo __('Auto Import Core: Some required files are missing. Please reinstall the plugin.', 'auto-import-core');
            echo '</p></div>';
        });
        return;
    }
    
    // Initialize components with error handling
    try {
        if (class_exists('AIC\PostTypes\CarPostType')) {
            AIC\PostTypes\CarPostType::init();
        }
        
        if (class_exists('AIC\PostTypes\LeadPostType')) {
            AIC\PostTypes\LeadPostType::init();
        }
        
        if (class_exists('AIC\PostTypes\ArticlePostType')) {
            AIC\PostTypes\ArticlePostType::init();
        }
        
        if (class_exists('AIC\Taxonomies\CarTaxonomies')) {
            AIC\Taxonomies\CarTaxonomies::init();
        }
        
        if (class_exists('AIC\Admin\CarAdmin')) {
            AIC\Admin\CarAdmin::init();
        }
        
        if (class_exists('AIC\Admin\LeadAdmin')) {
            AIC\Admin\LeadAdmin::init();
        }
        
        if (class_exists('AIC\Admin\Settings')) {
            AIC\Admin\Settings::init();
        }
        
        if (class_exists('AIC\Blocks\BlocksManager')) {
            AIC\Blocks\BlocksManager::init();
        }
        
        if (class_exists('AIC\API\LeadAPI')) {
            AIC\API\LeadAPI::init();
        }
    } catch (Exception $e) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error"><p>';
            echo __('Auto Import Core Error: ', 'auto-import-core') . esc_html($e->getMessage());
            echo '</p></div>';
        });
    }
}
add_action('plugins_loaded', 'aic_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    // Don't run aic_init here to avoid errors during activation
    // Just flush rewrite rules
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Enqueue admin assets
function aic_enqueue_admin_assets($hook) {
    // Only load on our plugin pages
    $screen = get_current_screen();
    if (!$screen) {
        return;
    }
    
    $our_post_types = ['car', 'lead', 'article'];
    $load_assets = false;
    
    // Check if we're on our post type pages
    if (in_array($screen->post_type, $our_post_types)) {
        $load_assets = true;
    }
    
    // Check if we're on our settings page
    if (strpos($hook, 'auto-import') !== false) {
        $load_assets = true;
    }
    
    if (!$load_assets) {
        return;
    }
    
    // Admin styles
    if (file_exists(AIC_PLUGIN_DIR . 'assets/css/admin.css')) {
        wp_enqueue_style('aic-admin', AIC_PLUGIN_URL . 'assets/css/admin.css', [], AIC_VERSION);
    }
    
    // Admin scripts
    if (file_exists(AIC_PLUGIN_DIR . 'assets/js/admin.js')) {
        wp_enqueue_script('aic-admin', AIC_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], AIC_VERSION, true);
        
        // Localize script
        wp_localize_script('aic-admin', 'aicAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aic_admin_nonce')
        ]);
    }
}
add_action('admin_enqueue_scripts', 'aic_enqueue_admin_assets');

// Enqueue block editor assets
function aic_enqueue_block_editor_assets() {
    // Main blocks script
    if (file_exists(AIC_PLUGIN_DIR . 'assets/js/blocks.js')) {
        wp_enqueue_script(
            'aic-blocks',
            AIC_PLUGIN_URL . 'assets/js/blocks.js',
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'],
            AIC_VERSION,
            true
        );
    }
    
    // Individual block scripts
    $blocks = ['hero', 'trust-bar', 'car-grid', 'lead-form', 'articles-grid', 'faq'];
    foreach ($blocks as $block) {
        $block_file = AIC_PLUGIN_DIR . 'blocks/' . $block . '/index.js';
        if (file_exists($block_file)) {
            wp_enqueue_script(
                'aic-block-' . $block,
                AIC_PLUGIN_URL . 'blocks/' . $block . '/index.js',
                ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'],
                AIC_VERSION,
                true
            );
        }
    }
}
add_action('enqueue_block_editor_assets', 'aic_enqueue_block_editor_assets');