<?php
/**
 * Template for displaying single car
 */

get_header();

while (have_posts()) : the_post();
    $car_id = get_the_ID();
    
    // Get meta data
    $price = get_post_meta($car_id, 'price_rub', true);
    $year = get_post_meta($car_id, 'year', true);
    $mileage = get_post_meta($car_id, 'mileage_km', true);
    $vin = get_post_meta($car_id, 'vin', true);
    $engine_volume = get_post_meta($car_id, 'engine_volume', true);
    $engine_power = get_post_meta($car_id, 'engine_power_hp', true);
    $color = get_post_meta($car_id, 'color', true);
    $steering = get_post_meta($car_id, 'steering', true);
    $owners = get_post_meta($car_id, 'owners', true);
    $customs_status = get_post_meta($car_id, 'customs_status', true);
    $documents = get_post_meta($car_id, 'documents', true);
    $equipment = get_post_meta($car_id, 'equipment', true);
    $gallery = get_post_meta($car_id, 'gallery', true) ?: [];
    $video_url = get_post_meta($car_id, 'video_url', true);
    
    // Get taxonomies
    $brands = get_the_terms($car_id, 'car_brand');
    $models = get_the_terms($car_id, 'car_model');
    $body = get_the_terms($car_id, 'car_body');
    $fuel = get_the_terms($car_id, 'car_fuel');
    $transmission = get_the_terms($car_id, 'car_transmission');
    $drive = get_the_terms($car_id, 'car_drive');
    $status = get_the_terms($car_id, 'car_status');
    $location = get_the_terms($car_id, 'car_location');
?>

<main class="site-main single-car">
    <div class="container">
        <article class="car-single">
            <div class="car-main">
                <?php if (!empty($gallery) || has_post_thumbnail()): ?>
                    <div class="car-gallery">
                        <div class="car-gallery__main" id="car-gallery-main">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('car-large');
                            } elseif (!empty($gallery)) {
                                echo wp_get_attachment_image($gallery[0], 'car-large');
                            }
                            ?>
                        </div>
                        
                        <?php if (!empty($gallery)): ?>
                            <div class="car-gallery__thumbs">
                                <?php foreach ($gallery as $index => $image_id): ?>
                                    <div class="car-gallery__thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-image-id="<?php echo esc_attr($image_id); ?>">
                                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="car-details">
                    <h1 class="car-details__title"><?php the_title(); ?></h1>
                    
                    <?php if ($price): ?>
                        <div class="car-details__price">
                            <?php echo number_format($price, 0, '', ' '); ?> ₽
                        </div>
                    <?php endif; ?>
                    
                    <div class="car-details__meta">
                        <?php if ($year): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Year', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo esc_html($year); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($mileage): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Mileage', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo number_format($mileage, 0, '', ' '); ?> km</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($body && !is_wp_error($body)): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Body Type', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo esc_html($body[0]->name); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($fuel && !is_wp_error($fuel)): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Fuel', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo esc_html($fuel[0]->name); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($transmission && !is_wp_error($transmission)): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Transmission', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo esc_html($transmission[0]->name); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($drive && !is_wp_error($drive)): ?>
                            <div class="car-details__meta-item">
                                <span class="car-details__meta-label"><?php _e('Drive', 'auto-import'); ?></span>
                                <span class="car-details__meta-value"><?php echo esc_html($drive[0]->name); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (has_excerpt()): ?>
                        <div class="car-details__excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (get_the_content()): ?>
                        <div class="car-details__description">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($engine_volume || $engine_power || $color || $steering || $vin || $owners): ?>
                    <div class="car-specs">
                        <h2 class="car-specs__title"><?php _e('Technical Specifications', 'auto-import'); ?></h2>
                        <table class="car-specs__table">
                            <tbody>
                                <?php if ($engine_volume): ?>
                                    <tr>
                                        <td><?php _e('Engine Volume', 'auto-import'); ?></td>
                                        <td><?php echo esc_html($engine_volume); ?> L</td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($engine_power): ?>
                                    <tr>
                                        <td><?php _e('Engine Power', 'auto-import'); ?></td>
                                        <td><?php echo esc_html($engine_power); ?> HP</td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($color): ?>
                                    <tr>
                                        <td><?php _e('Color', 'auto-import'); ?></td>
                                        <td><?php echo esc_html($color); ?></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($steering): ?>
                                    <tr>
                                        <td><?php _e('Steering', 'auto-import'); ?></td>
                                        <td><?php echo $steering === 'left' ? __('Left', 'auto-import') : __('Right', 'auto-import'); ?></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($vin): ?>
                                    <tr>
                                        <td><?php _e('VIN', 'auto-import'); ?></td>
                                        <td><?php echo esc_html($vin); ?></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($owners): ?>
                                    <tr>
                                        <td><?php _e('Owners', 'auto-import'); ?></td>
                                        <td><?php echo esc_html($owners); ?></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($customs_status): ?>
                                    <tr>
                                        <td><?php _e('Customs Status', 'auto-import'); ?></td>
                                        <td>
                                            <?php
                                            $statuses = [
                                                'not_cleared' => __('Not Cleared', 'auto-import'),
                                                'cleared' => __('Cleared', 'auto-import'),
                                                'in_process' => __('In Process', 'auto-import'),
                                            ];
                                            echo isset($statuses[$customs_status]) ? $statuses[$customs_status] : $customs_status;
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($equipment) && is_array($equipment)): ?>
                    <div class="car-equipment">
                        <h2 class="car-equipment__title"><?php _e('Equipment', 'auto-import'); ?></h2>
                        <ul class="car-equipment__list">
                            <?php foreach ($equipment as $item): ?>
                                <li><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            
            <aside class="car-sidebar">
                <div class="car-contact">
                    <h3 class="car-contact__title"><?php _e('Contact Us', 'auto-import'); ?></h3>
                    
                    <div class="car-contact__info">
                        <?php
                        $phone = get_option('aic_company_phone');
                        $email = get_option('aic_company_email');
                        
                        if ($phone):
                        ?>
                            <div class="car-contact__info-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path>
                                </svg>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <a href="#contact-form" class="btn btn--primary btn--block">
                        <?php _e('Request Information', 'auto-import'); ?>
                    </a>
                </div>
            </aside>
        </article>
    </div>
</main>

<?php
endwhile;

get_footer();