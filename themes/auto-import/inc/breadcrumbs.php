<?php
/**
 * Breadcrumbs
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display breadcrumbs
 */
function auto_import_breadcrumbs() {
    // Don't display on home page
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'url' => home_url(),
        'title' => __('Home', 'auto-import'),
    ];
    
    // Single car
    if (is_singular('car')) {
        $breadcrumbs[] = [
            'url' => get_post_type_archive_link('car'),
            'title' => __('Cars', 'auto-import'),
        ];
        
        $brands = get_the_terms(get_the_ID(), 'brand');
        if ($brands && !is_wp_error($brands)) {
            $breadcrumbs[] = [
                'url' => get_term_link($brands[0]),
                'title' => $brands[0]->name,
            ];
        }
        
        $breadcrumbs[] = [
            'url' => '',
            'title' => get_the_title(),
        ];
    }
    // Car archive
    elseif (is_post_type_archive('car')) {
        $breadcrumbs[] = [
            'url' => '',
            'title' => __('Cars', 'auto-import'),
        ];
    }
    // Taxonomy
    elseif (is_tax(['brand', 'model', 'body_type', 'fuel', 'transmission', 'drive', 'status', 'location'])) {
        $breadcrumbs[] = [
            'url' => get_post_type_archive_link('car'),
            'title' => __('Cars', 'auto-import'),
        ];
        
        $term = get_queried_object();
        $breadcrumbs[] = [
            'url' => '',
            'title' => $term->name,
        ];
    }
    // Single article
    elseif (is_singular('article')) {
        $breadcrumbs[] = [
            'url' => get_post_type_archive_link('article'),
            'title' => __('Blog', 'auto-import'),
        ];
        
        $breadcrumbs[] = [
            'url' => '',
            'title' => get_the_title(),
        ];
    }
    // Article archive
    elseif (is_post_type_archive('article')) {
        $breadcrumbs[] = [
            'url' => '',
            'title' => __('Blog', 'auto-import'),
        ];
    }
    // 404
    elseif (is_404()) {
        $breadcrumbs[] = [
            'url' => '',
            'title' => __('Page Not Found', 'auto-import'),
        ];
    }
    // Search
    elseif (is_search()) {
        $breadcrumbs[] = [
            'url' => '',
            'title' => sprintf(__('Search Results for: %s', 'auto-import'), get_search_query()),
        ];
    }
    
    // Output breadcrumbs
    if (count($breadcrumbs) > 1) {
        echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
        
        // Schema.org markup
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $is_last = ($index === count($breadcrumbs) - 1);
            
            echo '<div class="breadcrumbs__item">';
            
            if (!$is_last && !empty($breadcrumb['url'])) {
                echo '<a href="' . esc_url($breadcrumb['url']) . '" class="breadcrumbs__link">';
                echo esc_html($breadcrumb['title']);
                echo '</a>';
            } else {
                echo '<span class="breadcrumbs__current">' . esc_html($breadcrumb['title']) . '</span>';
            }
            
            if (!$is_last) {
                echo '<span class="breadcrumbs__separator">&raquo;</span>';
            }
            
            echo '</div>';
            
            // Add to schema
            $schema_item = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['title'],
            ];
            
            if (!empty($breadcrumb['url'])) {
                $schema_item['item'] = $breadcrumb['url'];
            }
            
            $schema['itemListElement'][] = $schema_item;
        }
        
        echo '</nav>';
        
        // Output schema
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo '</script>';
    }
}
