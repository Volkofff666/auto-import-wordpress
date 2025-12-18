<?php
/**
 * Leads REST API Endpoint
 *
 * @package AutoImportCore
 */

namespace AIC\API;

if (!defined('ABSPATH')) {
    exit;
}

class LeadsEndpoint {
    
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('aic/v1', '/leads', [
            'methods' => 'POST',
            'callback' => [$this, 'create_lead'],
            'permission_callback' => '__return_true',
            'args' => [
                'name' => [
                    'required' => true,
                    'validate_callback' => function($param) {
                        return !empty($param) && strlen($param) <= 100;
                    },
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'phone' => [
                    'required' => true,
                    'validate_callback' => function($param) {
                        return !empty($param);
                    },
                    'sanitize_callback' => [$this, 'sanitize_phone'],
                ],
                'email' => [
                    'required' => false,
                    'validate_callback' => function($param) {
                        return empty($param) || is_email($param);
                    },
                    'sanitize_callback' => 'sanitize_email',
                ],
                'budget' => [
                    'required' => false,
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    },
                    'sanitize_callback' => 'absint',
                ],
                'comment' => [
                    'required' => false,
                    'sanitize_callback' => 'sanitize_textarea_field',
                ],
                'source_page' => [
                    'required' => false,
                    'sanitize_callback' => 'esc_url_raw',
                ],
            ],
        ]);
    }
    
    /**
     * Create lead
     */
    public function create_lead($request) {
        $name = $request->get_param('name');
        $phone = $request->get_param('phone');
        $email = $request->get_param('email');
        $budget = $request->get_param('budget');
        $comment = $request->get_param('comment');
        $source_page = $request->get_param('source_page');
        
        // Create lead post
        $post_id = wp_insert_post([
            'post_type' => 'lead',
            'post_title' => $name,
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($post_id)) {
            return new \WP_Error('create_failed', __('Failed to create lead', 'auto-import-core'), ['status' => 500]);
        }
        
        // Save meta
        update_post_meta($post_id, 'aic_lead_phone', $phone);
        update_post_meta($post_id, 'aic_lead_email', $email);
        update_post_meta($post_id, 'aic_lead_budget', $budget);
        update_post_meta($post_id, 'aic_lead_comment', $comment);
        update_post_meta($post_id, 'aic_lead_source_page', $source_page);
        update_post_meta($post_id, 'aic_lead_status', 'new');
        
        // Send email notification
        $this->send_notification($post_id, $name, $phone, $email, $budget, $comment, $source_page);
        
        return rest_ensure_response([
            'success' => true,
            'message' => __('Thank you! We will contact you soon.', 'auto-import-core'),
            'lead_id' => $post_id,
        ]);
    }
    
    /**
     * Send email notification
     */
    private function send_notification($post_id, $name, $phone, $email, $budget, $comment, $source_page) {
        $settings = get_option('aic_settings', []);
        
        if (empty($settings['email_notifications'])) {
            return;
        }
        
        $to = $settings['notification_email'] ?? get_option('admin_email');
        $subject = $settings['email_subject'] ?? __('New Lead: {name}', 'auto-import-core');
        $subject = str_replace('{name}', $name, $subject);
        
        $message = sprintf(
            __('New lead received from website:\n\n', 'auto-import-core') .
            __('Name: %s\n', 'auto-import-core') .
            __('Phone: %s\n', 'auto-import-core') .
            __('Email: %s\n', 'auto-import-core') .
            __('Budget: %s\n', 'auto-import-core') .
            __('Comment: %s\n', 'auto-import-core') .
            __('Source Page: %s\n\n', 'auto-import-core') .
            __('View in admin: %s', 'auto-import-core'),
            $name,
            $phone,
            $email ?: '—',
            $budget ? number_format($budget, 0, '.', ' ') . ' ₽' : '—',
            $comment ?: '—',
            $source_page ?: '—',
            admin_url('post.php?post=' . $post_id . '&action=edit')
        );
        
        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        
        wp_mail($to, $subject, $message, $headers);
    }
    
    /**
     * Sanitize phone
     */
    public function sanitize_phone($phone) {
        return preg_replace('/[^0-9+\-()\s]/', '', $phone);
    }
}
