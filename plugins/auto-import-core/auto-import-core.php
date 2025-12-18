<?php
/**
 * Plugin Name: Auto Import Core
 * Plugin URI: https://github.com/Volkofff666/auto-import-wordpress
 * Description: Core functionality for Auto Import business: cars catalog, leads management, Gutenberg blocks
 * Version: 1.0.0
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

define('AIC_VERSION', '1.0.0');
define('AIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIC_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'AIC\\';
    $base_dir = AIC_PLUGIN_DIR . 'includes/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize plugin
function aic_init() {
    // Load text domain
    load_plugin_textdomain('auto-import-core', false, dirname(AIC_PLUGIN_BASENAME) . '/languages');
    
    // Initialize components
    AIC\PostTypes\CarPostType::init();
    AIC\PostTypes\LeadPostType::init();
    AIC\PostTypes\ArticlePostType::init();
    
    AIC\Taxonomies\CarTaxonomies::init();
    
    AIC\Admin\CarAdmin::init();
    AIC\Admin\LeadAdmin::init();
    AIC\Admin\Settings::init();
    
    AIC\Blocks\BlocksManager::init();
    
    AIC\API\LeadAPI::init();
}
add_action('plugins_loaded', 'aic_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    aic_init();
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Enqueue admin assets
function aic_enqueue_admin_assets($hook) {
    // Admin styles and scripts
    wp_enqueue_style('aic-admin', AIC_PLUGIN_URL . 'assets/css/admin.css', [], AIC_VERSION);
    wp_enqueue_script('aic-admin', AIC_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], AIC_VERSION, true);
    
    // Localize script
    wp_localize_script('aic-admin', 'aicAdmin', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aic_admin_nonce')
    ]);
}
add_action('admin_enqueue_scripts', 'aic_enqueue_admin_assets');

// Enqueue block editor assets
function aic_enqueue_block_editor_assets() {
    // Main blocks script that registers all blocks
    wp_enqueue_script(
        'aic-blocks',
        AIC_PLUGIN_URL . 'assets/js/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'],
        AIC_VERSION,
        true
    );
    
    // Individual block scripts
    $blocks = ['hero', 'trust-bar', 'car-grid', 'lead-form', 'articles-grid', 'faq'];
    foreach ($blocks as $block) {
        wp_enqueue_script(
            'aic-block-' . $block,
            AIC_PLUGIN_URL . 'blocks/' . $block . '/index.js',
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'],
            AIC_VERSION,
            true
        );
    }
}
add_action('enqueue_block_editor_assets', 'aic_enqueue_block_editor_assets');