<?php
$posts_per_page = $attributes['postsPerPage'] ?? 6;
$filter_status = $attributes['filterByStatus'] ?? '';
$filter_brand = $attributes['filterByBrand'] ?? '';
$order_by = $attributes['orderBy'] ?? 'date';
$order = $attributes['order'] ?? 'DESC';

$args = [
    'post_type' => 'car',
    'posts_per_page' => $posts_per_page,
    'post_status' => 'publish',
    'orderby' => $order_by,
    'order' => $order,
    'meta_query' => [
        [
            'key' => 'publish_to_catalog',
            'value' => '1',
            'compare' => '='
        ]
    ]
];

if ($filter_status) {
    $args['tax_query'][] = [
        'taxonomy' => 'car_status',
        'field' => 'slug',
        'terms' => $filter_status
    ];
}

if ($filter_brand) {
    $args['tax_query'][] = [
        'taxonomy' => 'car_brand',
        'field' => 'slug',
        'terms' => $filter_brand
    ];
}

$query = new WP_Query($args);

if (!$query->have_posts()) {
    return;
}
?>

<section class="car-grid">
    <div class="container">
        <div class="car-grid__items">
            <?php while ($query->have_posts()): $query->the_post(); 
                $price = get_post_meta(get_the_ID(), 'price_rub', true);
                $year = get_post_meta(get_the_ID(), 'year', true);
                $mileage = get_post_meta(get_the_ID(), 'mileage_km', true);
                $brands = get_the_terms(get_the_ID(), 'car_brand');
                $models = get_the_terms(get_the_ID(), 'car_model');
                $brand = $brands && !is_wp_error($brands) ? $brands[0]->name : '';
                $model = $models && !is_wp_error($models) ? $models[0]->name : '';
            ?>
                <article class="car-card">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" class="car-card__image">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="car-card__content">
                        <h3 class="car-card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo esc_html($brand . ' ' . $model); ?>
                            </a>
                        </h3>
                        
                        <div class="car-card__meta">
                            <?php if ($year): ?>
                                <span><?php echo esc_html($year); ?> год</span>
                            <?php endif; ?>
                            <?php if ($mileage): ?>
                                <span><?php echo number_format($mileage, 0, '', ' '); ?> км</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($price): ?>
                            <div class="car-card__price">
                                <?php echo number_format($price, 0, '', ' '); ?> ₽
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php the_permalink(); ?>" class="btn btn--secondary btn--block">
                            Подробнее
                        </a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>