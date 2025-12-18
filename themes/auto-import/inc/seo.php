<?php
/**
 * SEO functions
 */

// Add meta description
function ai_meta_description() {
    if (is_singular('car')) {
        $car_id = get_the_ID();
        $template = get_option('aic_seo_description_template');
        
        if ($template) {
            $brands = get_the_terms($car_id, 'car_brand');
            $models = get_the_terms($car_id, 'car_model');
            $year = get_post_meta($car_id, 'year', true);
            $price = get_post_meta($car_id, 'price_rub', true);
            
            $brand = $brands && !is_wp_error($brands) ? $brands[0]->name : '';
            $model = $models && !is_wp_error($models) ? $models[0]->name : '';
            
            $description = str_replace(
                ['{brand}', '{model}', '{year}', '{price}'],
                [$brand, $model, $year, number_format($price, 0, '', ' ')],
                $template
            );
            
            echo '<meta name="description" content="' . esc_attr($description) . '">';
        } elseif (has_excerpt()) {
            echo '<meta name="description" content="' . esc_attr(get_the_excerpt()) . '">';
        }
    }
}
add_action('wp_head', 'ai_meta_description');

// Schema.org structured data for cars
function ai_car_schema() {
    if (!is_singular('car')) {
        return;
    }
    
    $car_id = get_the_ID();
    $brands = get_the_terms($car_id, 'car_brand');
    $models = get_the_terms($car_id, 'car_model');
    $year = get_post_meta($car_id, 'year', true);
    $price = get_post_meta($car_id, 'price_rub', true);
    $mileage = get_post_meta($car_id, 'mileage_km', true);
    
    $brand = $brands && !is_wp_error($brands) ? $brands[0]->name : '';
    $model = $models && !is_wp_error($models) ? $models[0]->name : '';
    
    $schema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Car',
        'name' => $brand . ' ' . $model,
        'brand' => [
            '@type' => 'Brand',
            'name' => $brand
        ],
        'model' => $model,
        'vehicleModelDate' => $year,
        'mileageFromOdometer' => [
            '@type' => 'QuantitativeValue',
            'value' => $mileage,
            'unitCode' => 'KMT'
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => $price,
            'priceCurrency' => 'RUB',
            'availability' => 'https://schema.org/InStock',
            'url' => get_permalink()
        ]
    ];
    
    if (has_post_thumbnail()) {
        $schema['image'] = get_the_post_thumbnail_url($car_id, 'full');
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'ai_car_schema');

// Organization schema
function ai_organization_schema() {
    if (!is_front_page()) {
        return;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'logo' => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => get_option('aic_company_phone'),
            'contactType' => 'Customer Service',
            'email' => get_option('aic_company_email')
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => get_option('aic_company_address')
        ]
    ];
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'ai_organization_schema');