<?php
namespace AIC\API;

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
        $params = $request->get_json_params();
        
        // Validate required fields
        if (empty($params['name']) || empty($params['phone'])) {
            return new \WP_Error(
                'missing_fields',
                __('Имя и телефон обязательны для заполнения', 'auto-import-core'),
                ['status' => 400]
            );
        }
        
        // Create lead
        $post_id = wp_insert_post([
            'post_type' => 'lead',
            'post_title' => sprintf(
                __('Заявка от %s - %s', 'auto-import-core'),
                sanitize_text_field($params['name']),
                date('d.m.Y H:i')
            ),
            'post_status' => 'publish',
        ]);
        
        if (is_wp_error($post_id)) {
            return new \WP_Error(
                'create_failed',
                __('Не удалось создать заявку', 'auto-import-core'),
                ['status' => 500]
            );
        }
        
        // Save meta fields
        update_post_meta($post_id, 'name', sanitize_text_field($params['name']));
        update_post_meta($post_id, 'phone', sanitize_text_field($params['phone']));
        update_post_meta($post_id, 'email', sanitize_email($params['email'] ?? ''));
        update_post_meta($post_id, 'city', sanitize_text_field($params['city'] ?? ''));
        update_post_meta($post_id, 'budget', absint($params['budget'] ?? 0));
        update_post_meta($post_id, 'preferred_brand', sanitize_text_field($params['preferred_brand'] ?? ''));
        update_post_meta($post_id, 'preferred_model', sanitize_text_field($params['preferred_model'] ?? ''));
        update_post_meta($post_id, 'source_page', esc_url_raw($params['source_page'] ?? ''));
        update_post_meta($post_id, 'status', 'new');
        
        if (!empty($params['comment'])) {
            wp_update_post([
                'ID' => $post_id,
                'post_content' => sanitize_textarea_field($params['comment'])
            ]);
        }
        
        return [
            'success' => true,
            'message' => __('Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.', 'auto-import-core'),
            'lead_id' => $post_id,
        ];
    }
}