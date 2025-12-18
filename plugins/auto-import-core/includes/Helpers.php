<?php
/**
 * Helper functions
 *
 * @package AutoImportCore
 */

namespace AIC;

if (!defined('ABSPATH')) {
    exit;
}

class Helpers {
    
    /**
     * Format price
     */
    public static function format_price($price, $currency = 'RUB') {
        if (empty($price)) {
            return '';
        }
        
        $price = number_format($price, 0, '.', ' ');
        
        $symbols = [
            'RUB' => '₽',
            'USD' => '$',
            'EUR' => '€',
        ];
        
        $symbol = isset($symbols[$currency]) ? $symbols[$currency] : $currency;
        
        return $price . ' ' . $symbol;
    }
    
    /**
     * Format mileage
     */
    public static function format_mileage($mileage) {
        if (empty($mileage)) {
            return '';
        }
        
        return number_format($mileage, 0, '.', ' ') . ' км';
    }
    
    /**
     * Get car meta
     */
    public static function get_car_meta($post_id, $key, $single = true) {
        return get_post_meta($post_id, 'aic_' . $key, $single);
    }
    
    /**
     * Update car meta
     */
    public static function update_car_meta($post_id, $key, $value) {
        return update_post_meta($post_id, 'aic_' . $key, $value);
    }
    
    /**
     * Get lead meta
     */
    public static function get_lead_meta($post_id, $key, $single = true) {
        return get_post_meta($post_id, 'aic_lead_' . $key, $single);
    }
    
    /**
     * Update lead meta
     */
    public static function update_lead_meta($post_id, $key, $value) {
        return update_post_meta($post_id, 'aic_lead_' . $key, $value);
    }
    
    /**
     * Get settings
     */
    public static function get_settings() {
        $defaults = [
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
        
        $settings = get_option('aic_settings', []);
        
        return wp_parse_args($settings, $defaults);
    }
    
    /**
     * Get setting
     */
    public static function get_setting($key, $default = '') {
        $settings = self::get_settings();
        return isset($settings[$key]) ? $settings[$key] : $default;
    }
    
    /**
     * Get SVG icon
     */
    public static function get_icon($name, $size = 24) {
        $icons = [
            'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
            'mail' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
            'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
            'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
            'gauge' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
            'settings' => '<circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6m5.5-11.5l-4.24 4.24m-4.24 0L4.5 6.5m0 11l4.24-4.24m4.24 0L17.5 17.5"></path>',
            'check' => '<polyline points="20 6 9 17 4 12"></polyline>',
            'x' => '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>',
            'chevron-right' => '<polyline points="9 18 15 12 9 6"></polyline>',
            'filter' => '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>',
            'search' => '<circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
            'truck' => '<rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>',
            'dollar-sign' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
            'headphones' => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>',
        ];
        
        if (!isset($icons[$name])) {
            return '';
        }
        
        return sprintf(
            '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>',
            $size,
            $size,
            $icons[$name]
        );
    }
    
    /**
     * Sanitize phone
     */
    public static function sanitize_phone($phone) {
        return preg_replace('/[^0-9+\-()\s]/', '', $phone);
    }
    
    /**
     * Is catalog page
     */
    public static function is_catalog_page() {
        return is_post_type_archive('car') || is_tax(['brand', 'model', 'body_type', 'fuel', 'transmission', 'drive', 'status', 'location']);
    }
}
