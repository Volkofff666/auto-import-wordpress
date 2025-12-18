<?php
/**
 * Plugin Name: Auto Import Core
 * Plugin URI: https://github.com/Volkofff666/auto-import-wordpress
 * Description: Core functionality for Auto Import business: cars catalog, leads management, Gutenberg blocks
 * Version: 2.0.0
 * Author: Auto Import Team
 * Author URI: https://github.com/Volkofff666
 * Text Domain: auto-import-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: MIT
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Plugin constants
define('AIC_VERSION', '2.0.0');
define('AIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AIC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIC_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('AIC_PLUGIN_FILE', __FILE__);

/**
 * Main plugin class
 */
class AutoImportCore {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Register autoloader
        spl_autoload_register([$this, 'autoloader']);
        
        // Initialize plugin
        add_action('plugins_loaded', [$this, 'init']);
        
        // Activation/Deactivation hooks
        register_activation_hook(AIC_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(AIC_PLUGIN_FILE, [$this, 'deactivate']);
        
        // Enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
    }
    
    /**
     * PSR-4 Autoloader
     */
    public function autoloader($class) {
        // Project namespace prefix
        $prefix = 'AIC\\';
        
        // Does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        // Get the relative class name
        $relative_class = substr($class, $len);
        
        // Replace namespace separators with directory separators
        $file = AIC_PLUGIN_DIR . 'includes/' . str_replace('\\', '/', $relative_class) . '.php';
        
        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
        }
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('auto-import-core', false, dirname(AIC_PLUGIN_BASENAME) . '/languages');
        
        try {
            // Initialize Post Types
            $this->init_post_types();
            
            // Initialize Taxonomies
            $this->init_taxonomies();
            
            // Initialize Admin
            if (is_admin()) {
                $this->init_admin();
            }
            
            // Initialize Blocks
            $this->init_blocks();
            
            // Initialize REST API
            $this->init_api();
            
        } catch (Exception $e) {
            add_action('admin_notices', function() use ($e) {
                printf(
                    '<div class="notice notice-error"><p><strong>%s:</strong> %s</p></div>',
                    esc_html__('Auto Import Core Error', 'auto-import-core'),
                    esc_html($e->getMessage())
                );
            });
        }
    }
    
    /**
     * Initialize Post Types
     */
    private function init_post_types() {
        if (class_exists('AIC\\PostTypes\\CarPostType')) {
            new \AIC\PostTypes\CarPostType();
        }
        
        if (class_exists('AIC\\PostTypes\\LeadPostType')) {
            new \AIC\PostTypes\LeadPostType();
        }
        
        if (class_exists('AIC\\PostTypes\\ArticlePostType')) {
            new \AIC\PostTypes\ArticlePostType();
        }
    }
    
    /**
     * Initialize Taxonomies
     */
    private function init_taxonomies() {
        if (class_exists('AIC\\Taxonomies\\BrandTaxonomy')) {
            new \AIC\Taxonomies\BrandTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\ModelTaxonomy')) {
            new \AIC\Taxonomies\ModelTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\BodyTypeTaxonomy')) {
            new \AIC\Taxonomies\BodyTypeTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\FuelTaxonomy')) {
            new \AIC\Taxonomies\FuelTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\TransmissionTaxonomy')) {
            new \AIC\Taxonomies\TransmissionTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\DriveTaxonomy')) {
            new \AIC\Taxonomies\DriveTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\StatusTaxonomy')) {
            new \AIC\Taxonomies\StatusTaxonomy();
        }
        
        if (class_exists('AIC\\Taxonomies\\LocationTaxonomy')) {
            new \AIC\Taxonomies\LocationTaxonomy();
        }
    }
    
    /**
     * Initialize Admin
     */
    private function init_admin() {
        if (class_exists('AIC\\Admin\\CarMetaBoxes')) {
            new \AIC\Admin\CarMetaBoxes();
        }
        
        if (class_exists('AIC\\Admin\\LeadMetaBoxes')) {
            new \AIC\Admin\LeadMetaBoxes();
        }
        
        if (class_exists('AIC\\Admin\\AdminColumns')) {
            new \AIC\Admin\AdminColumns();
        }
        
        if (class_exists('AIC\\Admin\\SettingsPage')) {
            new \AIC\Admin\SettingsPage();
        }
    }
    
    /**
     * Initialize Blocks
     */
    private function init_blocks() {
        if (class_exists('AIC\\Blocks\\BlocksManager')) {
            new \AIC\Blocks\BlocksManager();
        }
    }
    
    /**
     * Initialize REST API
     */
    private function init_api() {
        if (class_exists('AIC\\API\\LeadsEndpoint')) {
            new \AIC\API\LeadsEndpoint();
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Force init to register post types and taxonomies
        $this->init();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set default options
        $default_settings = [
            'company_name' => get_bloginfo('name'),
            'company_phone' => '',
            'company_email' => get_option('admin_email'),
            'company_address' => '',
            'email_notifications' => true,
            'notification_email' => get_option('admin_email'),
            'email_subject' => __('New Lead: {name}', 'auto-import-core'),
            'cars_per_page' => 12,
            'default_sort' => 'date_desc',
            'show_filters' => true,
        ];
        
        if (!get_option('aic_settings')) {
            add_option('aic_settings', $default_settings);
        }
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Enqueue admin scripts
     */
    public function admin_enqueue_scripts($hook) {
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
        
        // WordPress media upload
        wp_enqueue_media();
        
        // Admin styles
        $admin_css = AIC_PLUGIN_DIR . 'assets/css/admin.css';
        if (file_exists($admin_css)) {
            wp_enqueue_style(
                'aic-admin',
                AIC_PLUGIN_URL . 'assets/css/admin.css',
                [],
                AIC_VERSION
            );
        }
        
        // Admin scripts
        $admin_js = AIC_PLUGIN_DIR . 'assets/js/admin.js';
        if (file_exists($admin_js)) {
            wp_enqueue_script(
                'aic-admin',
                AIC_PLUGIN_URL . 'assets/js/admin.js',
                ['jquery', 'wp-api'],
                AIC_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('aic-admin', 'aicAdmin', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'restUrl' => rest_url('aic/v1/'),
                'nonce' => wp_create_nonce('wp_rest'),
                'strings' => [
                    'uploading' => __('Uploading...', 'auto-import-core'),
                    'error' => __('Error uploading file', 'auto-import-core'),
                    'selectImage' => __('Select Image', 'auto-import-core'),
                    'useImage' => __('Use Image', 'auto-import-core'),
                ]
            ]);
        }
    }
    
    /**
     * Enqueue block editor assets
     */
    public function block_editor_assets() {
        // Main blocks script
        $blocks_js = AIC_PLUGIN_DIR . 'assets/js/blocks.js';
        if (file_exists($blocks_js)) {
            wp_enqueue_script(
                'aic-blocks',
                AIC_PLUGIN_URL . 'assets/js/blocks.js',
                ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'],
                AIC_VERSION,
                true
            );
        }
        
        // Block editor styles
        $editor_css = AIC_PLUGIN_DIR . 'assets/css/editor.css';
        if (file_exists($editor_css)) {
            wp_enqueue_style(
                'aic-editor',
                AIC_PLUGIN_URL . 'assets/css/editor.css',
                ['wp-edit-blocks'],
                AIC_VERSION
            );
        }
    }
}

// Initialize the plugin
AutoImportCore::get_instance();