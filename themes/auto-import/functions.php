<?php
/**
 * Auto Import Theme Functions
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('AUTO_IMPORT_VERSION', '2.0.0');
define('AUTO_IMPORT_THEME_DIR', get_template_directory());
define('AUTO_IMPORT_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function auto_import_setup() {
    // Make theme available for translation
    load_theme_textdomain('auto-import', AUTO_IMPORT_THEME_DIR . '/languages');
    
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Set default thumbnail size
    set_post_thumbnail_size(800, 600, true);
    
    // Add additional image sizes
    add_image_size('car-thumbnail', 400, 300, true);
    add_image_size('car-large', 1200, 800, true);
    add_image_size('article-thumbnail', 600, 400, true);
    
    // Register navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'auto-import'),
        'footer' => __('Footer Menu', 'auto-import'),
    ]);
    
    // Switch default core markup to output valid HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    
    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add support for Block Styles
    add_theme_support('wp-block-styles');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'auto_import_setup');

/**
 * Set the content width in pixels
 */
function auto_import_content_width() {
    $GLOBALS['content_width'] = apply_filters('auto_import_content_width', 1200);
}
add_action('after_setup_theme', 'auto_import_content_width', 0);

/**
 * Register widget areas
 */
function auto_import_widgets_init() {
    register_sidebar([
        'name' => __('Footer 1', 'auto-import'),
        'id' => 'footer-1',
        'description' => __('Add widgets here to appear in footer column 1.', 'auto-import'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 2', 'auto-import'),
        'id' => 'footer-2',
        'description' => __('Add widgets here to appear in footer column 2.', 'auto-import'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 3', 'auto-import'),
        'id' => 'footer-3',
        'description' => __('Add widgets here to appear in footer column 3.', 'auto-import'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 4', 'auto-import'),
        'id' => 'footer-4',
        'description' => __('Add widgets here to appear in footer column 4.', 'auto-import'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'auto_import_widgets_init');

/**
 * Enqueue scripts and styles
 */
function auto_import_scripts() {
    // Main stylesheet
    wp_enqueue_style('auto-import-style', get_stylesheet_uri(), [], AUTO_IMPORT_VERSION);
    
    // JavaScript files
    wp_enqueue_script('auto-import-main', AUTO_IMPORT_THEME_URI . '/assets/js/main.js', [], AUTO_IMPORT_VERSION, true);
    
    // Lead form script
    if (is_page() || is_singular('car')) {
        wp_enqueue_script('auto-import-lead-form', AUTO_IMPORT_THEME_URI . '/assets/js/lead-form.js', [], AUTO_IMPORT_VERSION, true);
    }
    
    // Filters script on catalog page
    if (is_post_type_archive('car') || is_tax(['brand', 'model', 'body_type', 'fuel', 'transmission', 'drive', 'status', 'location'])) {
        wp_enqueue_script('auto-import-filters', AUTO_IMPORT_THEME_URI . '/assets/js/filters.js', [], AUTO_IMPORT_VERSION, true);
    }
    
    // Gallery script on single car page
    if (is_singular('car')) {
        wp_enqueue_script('auto-import-gallery', AUTO_IMPORT_THEME_URI . '/assets/js/gallery.js', [], AUTO_IMPORT_VERSION, true);
    }
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'auto_import_scripts');

/**
 * Custom template tags
 */
require AUTO_IMPORT_THEME_DIR . '/inc/template-tags.php';

/**
 * Schema.org structured data
 */
require AUTO_IMPORT_THEME_DIR . '/inc/schema.php';

/**
 * Breadcrumbs
 */
require AUTO_IMPORT_THEME_DIR . '/inc/breadcrumbs.php';
