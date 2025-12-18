<?php
/**
 * Auto Import Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AI_THEME_VERSION', '1.0.0');
define('AI_THEME_DIR', get_template_directory());
define('AI_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function ai_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
    
    // Register nav menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'auto-import'),
        'footer' => __('Footer Menu', 'auto-import'),
    ]);
    
    // Image sizes
    add_image_size('car-thumbnail', 400, 300, true);
    add_image_size('car-large', 800, 600, true);
    add_image_size('article-thumbnail', 600, 400, true);
}
add_action('after_setup_theme', 'ai_theme_setup');

/**
 * Enqueue styles and scripts
 */
function ai_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style('ai-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null);
    
    // Theme styles
    wp_enqueue_style('ai-style', get_stylesheet_uri(), [], AI_THEME_VERSION);
    
    // Theme scripts
    wp_enqueue_script('ai-main', AI_THEME_URI . '/assets/js/main.js', [], AI_THEME_VERSION, true);
    
    // Localize script
    wp_localize_script('ai-main', 'aiTheme', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url('aic/v1/'),
        'nonce' => wp_create_nonce('wp_rest')
    ]);
}
add_action('wp_enqueue_scripts', 'ai_enqueue_assets');

/**
 * Register widgets
 */
function ai_widgets_init() {
    register_sidebar([
        'name' => __('Sidebar', 'auto-import'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'auto-import'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 1', 'auto-import'),
        'id' => 'footer-1',
        'before_widget' => '<div class="footer__widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer__widget-title">',
        'after_title' => '</h4>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 2', 'auto-import'),
        'id' => 'footer-2',
        'before_widget' => '<div class="footer__widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer__widget-title">',
        'after_title' => '</h4>',
    ]);
    
    register_sidebar([
        'name' => __('Footer 3', 'auto-import'),
        'id' => 'footer-3',
        'before_widget' => '<div class="footer__widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer__widget-title">',
        'after_title' => '</h4>',
    ]);
}
add_action('widgets_init', 'ai_widgets_init');

/**
 * Custom template tags
 */
require_once AI_THEME_DIR . '/inc/template-tags.php';

/**
 * Breadcrumbs
 */
require_once AI_THEME_DIR . '/inc/breadcrumbs.php';

/**
 * SEO functions
 */
require_once AI_THEME_DIR . '/inc/seo.php';

/**
 * Query modifications
 */
require_once AI_THEME_DIR . '/inc/query-modifications.php';

/**
 * Customizer
 */
require_once AI_THEME_DIR . '/inc/customizer.php';

/**
 * Body classes
 */
function ai_body_classes($classes) {
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    
    if (is_post_type_archive('car') || is_singular('car')) {
        $classes[] = 'catalog-page';
    }
    
    return $classes;
}
add_filter('body_class', 'ai_body_classes');

/**
 * Excerpt length
 */
function ai_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'ai_excerpt_length');

/**
 * Excerpt more
 */
function ai_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'ai_excerpt_more');