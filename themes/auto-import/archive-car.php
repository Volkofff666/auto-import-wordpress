<?php
/**
 * Archive Car Template - Catalog Page
 * Redesigned to match Katalog.pdf mockup
 */

get_header();

// Enqueue catalog assets
wp_enqueue_style('ai-catalog', get_template_directory_uri() . '/assets/css/archive-car.css', ['ai-style'], '2.0.0');
wp_enqueue_script('ai-catalog', get_template_directory_uri() . '/assets/js/catalog.js', ['jquery'], '2.0.0', true);

// Get current filters
$current_brand = isset($_GET['brand']) ? sanitize_text_field($_GET['brand']) : '';
$current_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date';
$current_location = get_option('ai_default_location', 'вашем городе');

// Get all brands for filter
$brands = get_terms([
    'taxonomy' => 'car_brand',
    'hide_empty' => true,
]);

// Get all statuses
$statuses = get_terms([
    'taxonomy' => 'car_status',
    'hide_empty' => true,
]);

// Build query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = [
    'post_type' => 'car',
    'posts_per_page' => 12,
    'paged' => $paged,
    'meta_query' => [
        [
            'key' => 'publish_to_catalog',
            'value' => '1',
            'compare' => '='
        ]
    ],
];

// Apply brand filter
if ($current_brand) {
    $args['tax_query'][] = [
        'taxonomy' => 'car_brand',
        'field' => 'slug',
        'terms' => $current_brand,
    ];
}

// Apply status filter
if ($current_status) {
    $args['tax_query'][] = [
        'taxonomy' => 'car_status',
        'field' => 'slug',
        'terms' => $current_status,
    ];
}

// Apply sorting
switch ($current_sort) {
    case 'price-asc':
        $args['meta_key'] = 'price_rub';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'ASC';
        break;
    case 'price-desc':
        $args['meta_key'] = 'price_rub';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        break;
    case 'year':
        $args['meta_key'] = 'year';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
}

$query = new WP_Query($args);

// Get popular collections (by brand)
$popular_brands = ['lada', 'haval', 'geely', 'chery']; // Можно настроить в опциях
?>

<main class="site-main catalog-page">
    <!-- Hero Section -->
    <section class="catalog-hero">
        <div class="container">
            <div class="catalog-hero__content">
                <div class="catalog-hero__text">
                    <h1 class="catalog-hero__title">
                        <?php 
                        if ($current_brand) {
                            $brand_obj = get_term_by('slug', $current_brand, 'car_brand');
                            echo 'Новые автомобили ' . esc_html($brand_obj->name) . ' в ' . esc_html($current_location);
                        } else {
                            echo 'Все автомобили';
                        }
                        ?>
                    </h1>
                    <p class="catalog-hero__subtitle">Бесплатно поможем вам с подбором авто</p>
                </div>
                
                <div class="catalog-hero__cta">
                    <button class="btn btn--primary btn--large" onclick="document.getElementById('catalog-form').scrollIntoView({behavior: 'smooth'});">
                        Ответим на все ваши вопросы
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filters & Sorting -->
    <section class="catalog-filters">
        <div class="container">
            <form class="filters-form" method="get" id="catalog-filters-form">
                <div class="filters-group">
                    <select name="brand" class="filter-select" onchange="this.form.submit()">
                        <option value="">Все марки</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo esc_attr($brand->slug); ?>" <?php selected($current_brand, $brand->slug); ?>>
                                <?php echo esc_html($brand->name); ?> (<?php echo $brand->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Все статусы</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo esc_attr($status->slug); ?>" <?php selected($current_status, $status->slug); ?>>
                                <?php echo esc_html($status->name); ?> (<?php echo $status->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="date" <?php selected($current_sort, 'date'); ?>>По дате добавления</option>
                        <option value="price-asc" <?php selected($current_sort, 'price-asc'); ?>>Сначала дешёвые</option>
                        <option value="price-desc" <?php selected($current_sort, 'price-desc'); ?>>Сначала дорогие</option>
                        <option value="year" <?php selected($current_sort, 'year'); ?>>По году выпуска</option>
                    </select>
                    
                    <?php if ($current_brand || $current_status || $current_sort !== 'date'): ?>
                        <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--outline">Сбросить фильтры</a>
                    <?php endif; ?>
                </div>
                
                <div class="catalog-results">
                    <span class="results-count">Найдено: <?php echo $query->found_posts; ?> автомобилей</span>
                </div>
            </form>
        </div>
    </section>
    
    <!-- Cars Grid -->
    <section class="catalog-grid-section">
        <div class="container">
            <?php if ($query->have_posts()): ?>
                <div class="cars-grid">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        get_template_part('template-parts/content', 'car-card');
                    endwhile;
                    ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($query->max_num_pages > 1): ?>
                    <nav class="catalog-pagination">
                        <?php
                        echo paginate_links([
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, $paged),
                            'total' => $query->max_num_pages,
                            'prev_text' => '← Предыдущая',
                            'next_text' => 'Следующая →',
                            'type' => 'list',
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="catalog-empty">
                    <h3>Автомобили не найдены</h3>
                    <p>Попробуйте изменить параметры фильтрации или <a href="<?php echo get_post_type_archive_link('car'); ?>">сбросить все фильтры</a>.</p>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
    
    <!-- Popular Collections -->
    <section class="catalog-collections">
        <div class="container">
            <h2 class="section-title">Подборки популярных авто</h2>
            
            <div class="collections-grid">
                <?php foreach ($popular_brands as $brand_slug): 
                    $brand_term = get_term_by('slug', $brand_slug, 'car_brand');
                    if (!$brand_term) continue;
                    
                    // Get count of cars for this brand
                    $brand_query = new WP_Query([
                        'post_type' => 'car',
                        'posts_per_page' => 1,
                        'tax_query' => [
                            [
                                'taxonomy' => 'car_brand',
                                'field' => 'term_id',
                                'terms' => $brand_term->term_id,
                            ]
                        ],
                        'meta_query' => [
                            [
                                'key' => 'publish_to_catalog',
                                'value' => '1',
                                'compare' => '='
                            ]
                        ],
                    ]);
                    
                    $count = $brand_query->found_posts;
                    wp_reset_postdata();
                    
                    if ($count === 0) continue;
                    
                    // Get first car image as collection cover
                    $brand_query = new WP_Query([
                        'post_type' => 'car',
                        'posts_per_page' => 1,
                        'tax_query' => [
                            [
                                'taxonomy' => 'car_brand',
                                'field' => 'term_id',
                                'terms' => $brand_term->term_id,
                            ]
                        ],
                        'meta_query' => [
                            [
                                'key' => 'publish_to_catalog',
                                'value' => '1',
                                'compare' => '='
                            ]
                        ],
                    ]);
                    
                    $cover_image = '';
                    if ($brand_query->have_posts()) {
                        $brand_query->the_post();
                        $cover_image = get_the_post_thumbnail_url(get_the_ID(), 'car-large');
                    }
                    wp_reset_postdata();
                ?>
                    <a href="<?php echo add_query_arg('brand', $brand_slug, get_post_type_archive_link('car')); ?>" class="collection-card">
                        <?php if ($cover_image): ?>
                            <div class="collection-card__image" style="background-image: url(<?php echo esc_url($cover_image); ?>)"></div>
                        <?php else: ?>
                            <div class="collection-card__image collection-card__image--placeholder"></div>
                        <?php endif; ?>
                        <div class="collection-card__content">
                            <h3 class="collection-card__title">Модельный ряд <?php echo esc_html($brand_term->name); ?></h3>
                            <span class="collection-card__count"><?php echo $count; ?> автомобилей</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Contact Form -->
    <section id="catalog-form" class="catalog-contact">
        <div class="container">
            <div class="catalog-contact__content">
                <div class="catalog-contact__text">
                    <h2>Бесплатно поможем вам с подбором авто</h2>
                    <p>Ответим на все ваши вопросы</p>
                    <ul class="contact-features">
                        <li>Консультация по выбору автомобиля</li>
                        <li>Помощь с оформлением кредита</li>
                        <li>Расчёт выгоды по трейд-ин</li>
                        <li>Подбор по вашим требованиям</li>
                    </ul>
                </div>
                
                <div class="catalog-contact__form">
                    <form class="contact-form" id="catalog-contact-form">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                        <input type="email" name="email" placeholder="Email (необязательно)">
                        <textarea name="comment" placeholder="Какой автомобиль вас интересует?" rows="3"></textarea>
                        <button type="submit" class="btn btn--primary btn--large btn--block">Отправить заявку</button>
                        <div class="form-message" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();