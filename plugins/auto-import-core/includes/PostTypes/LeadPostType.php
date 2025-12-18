<?php
/**
 * Lead Post Type
 *
 * @package AutoImportCore
 */

namespace AIC\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

class LeadPostType {
    
    public function __construct() {
        add_action('init', [$this, 'register'], 0);
        add_action('init', [$this, 'register_meta']);
    }
    
    /**
     * Register post type
     */
    public function register() {
        $labels = [
            'name' => __('Leads', 'auto-import-core'),
            'singular_name' => __('Lead', 'auto-import-core'),
            'menu_name' => __('Leads', 'auto-import-core'),
            'name_admin_bar' => __('Lead', 'auto-import-core'),
            'add_new' => __('Add New', 'auto-import-core'),
            'add_new_item' => __('Add New Lead', 'auto-import-core'),
            'new_item' => __('New Lead', 'auto-import-core'),
            'edit_item' => __('Edit Lead', 'auto-import-core'),
            'view_item' => __('View Lead', 'auto-import-core'),
            'all_items' => __('All Leads', 'auto-import-core'),
            'search_items' => __('Search Leads', 'auto-import-core'),
            'not_found' => __('No leads found.', 'auto-import-core'),
            'not_found_in_trash' => __('No leads found in Trash.', 'auto-import-core'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-feedback',
            'supports' => ['title'],
            'show_in_rest' => true,
            'rest_base' => 'leads',
        ];
        
        register_post_type('lead', $args);
    }
    
    /**
     * Register post meta
     */
    public function register_meta() {
        // Contact info
        register_post_meta('lead', 'aic_lead_phone', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => [__NAMESPACE__ . '\\LeadPostType', 'sanitize_phone'],
        ]);
        
        register_post_meta('lead', 'aic_lead_email', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_email',
        ]);
        
        // Budget
        register_post_meta('lead', 'aic_lead_budget', [
            'type' => 'integer',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'absint',
        ]);
        
        // Comment
        register_post_meta('lead', 'aic_lead_comment', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        
        // Source page
        register_post_meta('lead', 'aic_lead_source_page', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'esc_url_raw',
        ]);
        
        // Status
        register_post_meta('lead', 'aic_lead_status', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'default' => 'new',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        // Manager notes
        register_post_meta('lead', 'aic_lead_notes', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
    }
    
    /**
     * Sanitize phone number
     */
    public static function sanitize_phone($phone) {
        return preg_replace('/[^0-9+\-()\s]/', '', $phone);
    }
}
