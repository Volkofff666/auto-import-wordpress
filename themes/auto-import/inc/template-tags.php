<?php
/**
 * Template Tags
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Format price
 */
function auto_import_format_price($price) {
    return number_format($price, 0, '.', ' ') . ' ₽';
}

/**
 * Get car specs as array
 */
function auto_import_get_car_specs($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return [
        'price' => get_post_meta($post_id, 'aic_price', true),
        'year' => get_post_meta($post_id, 'aic_year', true),
        'mileage' => get_post_meta($post_id, 'aic_mileage', true),
        'vin' => get_post_meta($post_id, 'aic_vin', true),
        'color' => get_post_meta($post_id, 'aic_color', true),
        'engine_volume' => get_post_meta($post_id, 'aic_engine_volume', true),
        'engine_power' => get_post_meta($post_id, 'aic_engine_power', true),
        'condition' => get_post_meta($post_id, 'aic_condition', true),
        'equipment' => get_post_meta($post_id, 'aic_equipment', true),
    ];
}

/**
 * Check if car is available
 */
function auto_import_is_car_available($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $statuses = get_the_terms($post_id, 'status');
    
    if (!$statuses || is_wp_error($statuses)) {
        return true;
    }
    
    return $statuses[0]->slug === 'в-наличии';
}
