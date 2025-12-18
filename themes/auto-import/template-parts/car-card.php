<?php
/**
 * Car Card Template Part
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get car meta
$price = get_post_meta(get_the_ID(), 'aic_price', true);
$year = get_post_meta(get_the_ID(), 'aic_year', true);
$mileage = get_post_meta(get_the_ID(), 'aic_mileage', true);
$engine_volume = get_post_meta(get_the_ID(), 'aic_engine_volume', true);

// Get taxonomies
$brands = get_the_terms(get_the_ID(), 'brand');
$models = get_the_terms(get_the_ID(), 'model');
$fuel_types = get_the_terms(get_the_ID(), 'fuel');
$transmissions = get_the_terms(get_the_ID(), 'transmission');
$statuses = get_the_terms(get_the_ID(), 'status');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('car-card'); ?>>
    <a href="<?php the_permalink(); ?>" class="car-card__image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('car-thumbnail', ['loading' => 'lazy']); ?>
        <?php else : ?>
            <img src="<?php echo esc_url(AUTO_IMPORT_THEME_URI . '/assets/images/no-image.svg'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
        <?php endif; ?>
        
        <?php if ($statuses && !is_wp_error($statuses)) : ?>
            <span class="car-card__badge badge badge--<?php echo esc_attr($statuses[0]->slug === 'в-наличии' ? 'success' : 'warning'); ?>">
                <?php echo esc_html($statuses[0]->name); ?>
            </span>
        <?php endif; ?>
    </a>
    
    <div class="car-card__content">
        <h3 class="car-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <?php if ($price) : ?>
            <div class="car-card__price">
                <?php echo number_format($price, 0, '.', ' '); ?> ₽
            </div>
        <?php endif; ?>
        
        <div class="car-card__meta">
            <?php if ($year) : ?>
                <span class="car-card__meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo esc_html($year); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($mileage) : ?>
                <span class="car-card__meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo number_format($mileage, 0, '.', ' '); ?> км
                </span>
            <?php endif; ?>
            
            <?php if ($engine_volume) : ?>
                <span class="car-card__meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                        <polyline points="17 2 12 7 7 2"></polyline>
                    </svg>
                    <?php echo esc_html($engine_volume); ?> л
                </span>
            <?php endif; ?>
        </div>
        
        <div class="car-card__features">
            <?php if ($fuel_types && !is_wp_error($fuel_types)) : ?>
                <span class="car-card__feature"><?php echo esc_html($fuel_types[0]->name); ?></span>
            <?php endif; ?>
            
            <?php if ($transmissions && !is_wp_error($transmissions)) : ?>
                <span class="car-card__feature"><?php echo esc_html($transmissions[0]->name); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
