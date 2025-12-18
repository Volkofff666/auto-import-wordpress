<?php
/**
 * Breadcrumbs functionality
 */

function ai_breadcrumbs() {
    $separator = '/';
    $home_title = __('Home', 'auto-import');
    
    // Don't display on front page
    if (is_front_page()) {
        return;
    }
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'auto-import') . '">';
    echo '<div class="container">';
    echo '<ul class="breadcrumbs__list">';
    
    // Home link
    echo '<li class="breadcrumbs__item">';
    echo '<a href="' . esc_url(home_url('/')) . '" class="breadcrumbs__link">' . esc_html($home_title) . '</a>';
    echo '</li>';
    
    if (is_post_type_archive('car')) {
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . __('Car Catalog', 'auto-import') . '</span>';
        echo '</li>';
    } elseif (is_singular('car')) {
        echo '<li class="breadcrumbs__item">';
        echo '<a href="' . get_post_type_archive_link('car') . '" class="breadcrumbs__link">' . __('Car Catalog', 'auto-import') . '</a>';
        echo '</li>';
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . get_the_title() . '</span>';
        echo '</li>';
    } elseif (is_post_type_archive('article')) {
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . __('Blog', 'auto-import') . '</span>';
        echo '</li>';
    } elseif (is_singular('article')) {
        echo '<li class="breadcrumbs__item">';
        echo '<a href="' . get_post_type_archive_link('article') . '" class="breadcrumbs__link">' . __('Blog', 'auto-import') . '</a>';
        echo '</li>';
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . get_the_title() . '</span>';
        echo '</li>';
    } elseif (is_search()) {
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . __('Search Results', 'auto-import') . '</span>';
        echo '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . __('404 Error', 'auto-import') . '</span>';
        echo '</li>';
    } elseif (is_page()) {
        echo '<li class="breadcrumbs__item">';
        echo '<span class="breadcrumbs__current">' . get_the_title() . '</span>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
    echo '</nav>';
}

// Schema.org structured data for breadcrumbs
function ai_breadcrumbs_schema() {
    if (is_front_page()) {
        return;
    }
    
    $items = [];
    $position = 1;
    
    // Home
    $items[] = [
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => get_bloginfo('name'),
        'item' => home_url('/')
    ];
    
    if (is_singular('car')) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => __('Car Catalog', 'auto-import'),
            'item' => get_post_type_archive_link('car')
        ];
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_the_title(),
            'item' => get_permalink()
        ];
    }
    
    if (empty($items)) {
        return;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items
    ];
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'ai_breadcrumbs_schema');