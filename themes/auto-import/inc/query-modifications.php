<?php
/**
 * Query modifications for catalog filtering and sorting
 */

// Modify car catalog query based on filters
function ai_modify_car_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('car')) {
        // Only show published cars that are enabled in catalog
        $meta_query = [
            [
                'key' => 'publish_to_catalog',
                'value' => '1',
                'compare' => '='
            ]
        ];
        
        // Price range filter
        if (!empty($_GET['price_min']) || !empty($_GET['price_max'])) {
            $price_query = ['key' => 'price_rub'];
            
            if (!empty($_GET['price_min'])) {
                $price_query['value'] = [$_GET['price_min']];
                $price_query['compare'] = '>=';
                $price_query['type'] = 'NUMERIC';
            }
            
            if (!empty($_GET['price_max'])) {
                if (isset($price_query['value'])) {
                    $price_query['value'][] = $_GET['price_max'];
                    $price_query['compare'] = 'BETWEEN';
                } else {
                    $price_query['value'] = [$_GET['price_max']];
                    $price_query['compare'] = '<=';
                }
                $price_query['type'] = 'NUMERIC';
            }
            
            $meta_query[] = $price_query;
        }
        
        // Year range filter
        if (!empty($_GET['year_min']) || !empty($_GET['year_max'])) {
            $year_query = ['key' => 'year'];
            
            if (!empty($_GET['year_min'])) {
                $year_query['value'] = [$_GET['year_min']];
                $year_query['compare'] = '>=';
                $year_query['type'] = 'NUMERIC';
            }
            
            if (!empty($_GET['year_max'])) {
                if (isset($year_query['value'])) {
                    $year_query['value'][] = $_GET['year_max'];
                    $year_query['compare'] = 'BETWEEN';
                } else {
                    $year_query['value'] = [$_GET['year_max']];
                    $year_query['compare'] = '<=';
                }
                $year_query['type'] = 'NUMERIC';
            }
            
            $meta_query[] = $year_query;
        }
        
        $query->set('meta_query', $meta_query);
        
        // Taxonomy filters
        $tax_query = [];
        $taxonomies = ['car_brand', 'car_body', 'car_fuel', 'car_transmission', 'car_status'];
        
        foreach ($taxonomies as $taxonomy) {
            if (!empty($_GET[$taxonomy])) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET[$taxonomy])
                ];
            }
        }
        
        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }
        
        // Sorting
        if (!empty($_GET['orderby'])) {
            switch ($_GET['orderby']) {
                case 'price_asc':
                    $query->set('meta_key', 'price_rub');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'price_desc':
                    $query->set('meta_key', 'price_rub');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                case 'year_asc':
                    $query->set('meta_key', 'year');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'year_desc':
                    $query->set('meta_key', 'year');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                case 'date_asc':
                    $query->set('orderby', 'date');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'date_desc':
                default:
                    $query->set('orderby', 'date');
                    $query->set('order', 'DESC');
                    break;
            }
        }
    }
}
add_action('pre_get_posts', 'ai_modify_car_query');