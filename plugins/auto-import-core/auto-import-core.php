<?php
/**
 * Plugin Name: Auto Import Core
 * Plugin URI: https://github.com/Volkofff666/auto-import-wordpress
 * Description: Core functionality for auto import business: custom post types, taxonomies, admin interface
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Auto Import Team
 * License: MIT
 * Text Domain: auto-import-core
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AIC_VERSION', '1.0.0');
define('AIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIC_PLUGIN_URL', plugin_dir_url(__FILE__));

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
    load_plugin_textdomain('auto-import-core', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Register custom post types
    AIC\PostTypes\Car::register();
    AIC\PostTypes\Lead::register();
    AIC\PostTypes\Article::register();
    
    // Register taxonomies
    AIC\Taxonomies\CarTaxonomies::register();
    
    // Initialize admin
    if (is_admin()) {
        AIC\Admin\CarAdmin::init();
        AIC\Admin\LeadAdmin::init();
        AIC\Admin\Settings::init();
    }
    
    // Register Gutenberg blocks
    AIC\Blocks\BlocksManager::init();
}
add_action('init', 'aic_init');

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
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('aic-admin', AIC_PLUGIN_URL . 'assets/css/admin.css', [], AIC_VERSION);
    wp_enqueue_script('aic-admin', AIC_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], AIC_VERSION, true);
    
    wp_localize_script('aic-admin', 'aicAdmin', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aic_admin_nonce')
    ]);
});