<?php
/**
 * Template part for displaying car card in catalog
 */

$car_id = get_the_ID();
$price = get_post_meta($car_id, 'price_rub', true);
$year = get_post_meta($car_id, 'year', true);
$mileage = get_post_meta($car_id, 'mileage_km', true);

$brands = get_the_terms($car_id, 'car_brand');
$models = get_the_terms($car_id, 'car_model');
$status = get_the_terms($car_id, 'car_status');

$brand = $brands && !is_wp_error($brands) ? $brands[0]->name : '';
$model = $models && !is_wp_error($models) ? $models[0]->name : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('car-card'); ?>>
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" class="car-card__image">
            <?php the_post_thumbnail('car-thumbnail'); ?>
            
            <?php if ($status && !is_wp_error($status)): ?>
                <span class="badge badge--info" style="position: absolute; top: 10px; left: 10px;">
                    <?php echo esc_html($status[0]->name); ?>
                </span>
            <?php endif; ?>
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
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo esc_html($year); ?> <?php _e('year', 'auto-import'); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($mileage): ?>
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo number_format($mileage, 0, '', ' '); ?> km
                </span>
            <?php endif; ?>
        </div>
        
        <?php if ($price): ?>
            <div class="car-card__price">
                <?php echo number_format($price, 0, '', ' '); ?> ₽
            </div>
        <?php endif; ?>
        
        <a href="<?php the_permalink(); ?>" class="btn btn--secondary btn--block">
            <?php _e('View Details', 'auto-import'); ?>
        </a>
    </div>
</article>