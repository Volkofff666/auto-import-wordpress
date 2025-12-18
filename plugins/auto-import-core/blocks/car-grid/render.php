<?php
$per_page = $attributes['perPage'] ?? 6;
$orderby = $attributes['orderBy'] ?? 'date';
$order = $attributes['order'] ?? 'DESC';
$filter_status = $attributes['filterStatus'] ?? '';
$filter_brand = $attributes['filterBrand'] ?? '';

$args = [
    'post_type' => 'car',
    'posts_per_page' => $per_page,
    'orderby' => $orderby,
    'order' => $order,
    'meta_query' => [
        [
            'key' => 'publish_to_catalog',
            'value' => '1',
            'compare' => '='
        ]
    ]
];

$tax_query = [];
if ($filter_status) {
    $tax_query[] = [
        'taxonomy' => 'car_status',
        'field' => 'slug',
        'terms' => $filter_status
    ];
}
if ($filter_brand) {
    $tax_query[] = [
        'taxonomy' => 'car_brand',
        'field' => 'slug',
        'terms' => $filter_brand
    ];
}
if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
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
            ?>
                <article class="car-card">
                    <a href="<?php the_permalink(); ?>" class="car-card__link">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="car-card__image">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="car-card__content">
                            <h3 class="car-card__title">
                                <?php echo $brands ? esc_html($brands[0]->name) : ''; ?>
                                <?php echo $models ? esc_html($models[0]->name) : ''; ?>
                            </h3>
                            <div class="car-card__specs">
                                <?php if ($year): ?><span><?php echo esc_html($year); ?> г.</span><?php endif; ?>
                                <?php if ($mileage): ?><span><?php echo number_format($mileage, 0, '', ' '); ?> км</span><?php endif; ?>
                            </div>
                            <div class="car-card__price">
                                <?php echo $price ? number_format($price, 0, '', ' ') . ' ₽' : 'Цена по запросу'; ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>