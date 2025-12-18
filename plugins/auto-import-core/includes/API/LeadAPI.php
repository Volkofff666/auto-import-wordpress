<?php
namespace AIC\API;

if (!defined('ABSPATH')) {
    exit;
}

class LeadAPI {
    
    public static function init() {
        add_action('rest_api_init', [self::class, 'register_routes']);
    }
    
    public static function register_routes() {
        register_rest_route('aic/v1', '/leads', [
            'methods' => 'POST',
            'callback' => [self::class, 'create_lead'],
            'permission_callback' => '__return_true',
        ]);
    }
    
    public static function create_lead($request) {
        $params = $request->get_params();
        
        // Validate required fields
        if (empty($params['name']) || empty($params['phone'])) {
            return new \WP_Error('missing_fields', __('Name and phone are required', 'auto-import-core'), ['status' => 400]);
        }
        
        // Create lead post
        $post_id = wp_insert_post([
            'post_type' => 'lead',
            'post_title' => sanitize_text_field($params['name']),
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($post_id)) {
            return $post_id;
        }
        
        // Save meta
        update_post_meta($post_id, 'phone', sanitize_text_field($params['phone']));
        
        if (!empty($params['email'])) {
            update_post_meta($post_id, 'email', sanitize_email($params['email']));
        }
        
        if (!empty($params['budget'])) {
            update_post_meta($post_id, 'budget', absint($params['budget']));
        }
        
        if (!empty($params['comment'])) {
            update_post_meta($post_id, 'comment', sanitize_textarea_field($params['comment']));
        }
        
        if (!empty($params['source_page'])) {
            update_post_meta($post_id, 'source_page', esc_url_raw($params['source_page']));
        }
        
        update_post_meta($post_id, 'status', 'new');
        
        // Send email notification
        $admin_email = get_option('aic_company_email');
        if (!$admin_email) {
            $admin_email = get_option('admin_email');
        }
        
        if ($admin_email) {
            $subject = __('New lead from website', 'auto-import-core');
            $message = sprintf(
                __('New lead received:\n\nName: %s\nPhone: %s\nBudget: %s\n\nView in admin: %s', 'auto-import-core'),
                $params['name'],
                $params['phone'],
                !empty($params['budget']) ? number_format($params['budget'], 0, '', ' ') . ' ₽' : '-',
                admin_url('post.php?post=' . $post_id . '&action=edit')
            );
            
            wp_mail($admin_email, $subject, $message);
        }
        
        return [
            'success' => true,
            'message' => __('Lead created successfully', 'auto-import-core'),
            'lead_id' => $post_id,
        ];
    }
}