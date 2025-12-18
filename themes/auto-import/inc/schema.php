<?php
/**
 * Schema.org Structured Data
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output schema.org structured data
 */
function auto_import_output_schema() {
    $schema = [];
    
    // Organization schema (always output)
    $settings = get_option('aic_settings', []);
    $schema[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => get_site_icon_url(),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $settings['company_phone'] ?? '',
            'email' => $settings['company_email'] ?? '',
            'contactType' => 'customer service',
        ],
    ];
    
    // Product schema for single car
    if (is_singular('car')) {
        $price = get_post_meta(get_the_ID(), 'aic_price', true);
        $year = get_post_meta(get_the_ID(), 'aic_year', true);
        $mileage = get_post_meta(get_the_ID(), 'aic_mileage', true);
        $vin = get_post_meta(get_the_ID(), 'aic_vin', true);
        
        $brands = get_the_terms(get_the_ID(), 'brand');
        $models = get_the_terms(get_the_ID(), 'model');
        
        $available = auto_import_is_car_available();
        
        $product_schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'url' => get_permalink(),
        ];
        
        if (has_post_thumbnail()) {
            $product_schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
        
        if ($brands && !is_wp_error($brands)) {
            $product_schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brands[0]->name,
            ];
        }
        
        if ($models && !is_wp_error($models)) {
            $product_schema['model'] = $models[0]->name;
        }
        
        if ($vin) {
            $product_schema['vehicleIdentificationNumber'] = $vin;
        }
        
        if ($year) {
            $product_schema['vehicleModelDate'] = $year;
        }
        
        if ($mileage) {
            $product_schema['mileageFromOdometer'] = [
                '@type' => 'QuantitativeValue',
                'value' => $mileage,
                'unitCode' => 'KMT',
            ];
        }
        
        if ($price) {
            $product_schema['offers'] = [
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => 'RUB',
                'availability' => $available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ];
        }
        
        $schema[] = $product_schema;
    }
    
    // Output JSON-LD
    if (!empty($schema)) {
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo '</script>';
    }
}
