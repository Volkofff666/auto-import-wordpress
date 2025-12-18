<?php
/**
 * Custom template tags
 */

// Format price
function ai_format_price($price) {
    return number_format($price, 0, '', ' ') . ' ₽';
}

// Get car meta
function ai_get_car_meta($car_id, $key, $default = '') {
    $value = get_post_meta($car_id, $key, true);
    return $value ? $value : $default;
}

// Display car status badge
function ai_car_status_badge($car_id) {
    $status = get_the_terms($car_id, 'car_status');
    
    if ($status && !is_wp_error($status)) {
        $status_slug = $status[0]->slug;
        $status_name = $status[0]->name;
        
        $badge_class = 'badge';
        
        switch ($status_slug) {
            case 'available':
                $badge_class .= ' badge--success';
                break;
            case 'in-transit':
                $badge_class .= ' badge--warning';
                break;
            case 'sold':
                $badge_class .= ' badge--danger';
                break;
            default:
                $badge_class .= ' badge--info';
        }
        
        echo '<span class="' . esc_attr($badge_class) . '">' . esc_html($status_name) . '</span>';
    }
}

// Posted on
function ai_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );
    
    printf(
        '<span class="posted-on">%s</span>',
        $time_string
    );
}

// Entry footer
function ai_entry_footer() {
    $categories_list = get_the_category_list(', ');
    if ($categories_list) {
        printf('<span class="cat-links">' . __('Posted in %1$s', 'auto-import') . '</span>', $categories_list);
    }
    
    $tags_list = get_the_tag_list('', ', ');
    if ($tags_list) {
        printf('<span class="tags-links">' . __('Tagged %1$s', 'auto-import') . '</span>', $tags_list);
    }
}