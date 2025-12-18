<?php
/**
 * Single Car Template
 */

get_header();

// Enqueue single car assets
wp_enqueue_style('ai-single-car', get_template_directory_uri() . '/assets/css/single-car.css', ['ai-style'], '1.0.0');
wp_enqueue_script('ai-single-car', get_template_directory_uri() . '/assets/js/single-car.js', ['jquery'], '1.0.0', true);

while (have_posts()) : the_post();
    
    // Get car meta
    $price = get_post_meta(get_the_ID(), 'price_rub', true);
    $year = get_post_meta(get_the_ID(), 'year', true);
    $mileage = get_post_meta(get_the_ID(), 'mileage_km', true);
    $vin = get_post_meta(get_the_ID(), 'vin', true);
    $color = get_post_meta(get_the_ID(), 'color', true);
    $steering = get_post_meta(get_the_ID(), 'steering', true);
    $owners = get_post_meta(get_the_ID(), 'owners', true);
    $condition = get_post_meta(get_the_ID(), 'condition', true);
    $engine_volume = get_post_meta(get_the_ID(), 'engine_volume', true);
    $engine_power = get_post_meta(get_the_ID(), 'engine_power_hp', true);
    $customs_status = get_post_meta(get_the_ID(), 'customs_status', true);
    $documents = get_post_meta(get_the_ID(), 'documents', true);
    $equipment = get_post_meta(get_the_ID(), 'equipment', true);
    $gallery = get_post_meta(get_the_ID(), 'gallery', true);
    $video_url = get_post_meta(get_the_ID(), 'video_url', true);
    
    // Get taxonomies
    $brand = get_the_terms(get_the_ID(), 'car_brand');
    $model = get_the_terms(get_the_ID(), 'car_model');
    $body = get_the_terms(get_the_ID(), 'car_body');
    $fuel = get_the_terms(get_the_ID(), 'car_fuel');
    $transmission = get_the_terms(get_the_ID(), 'car_transmission');
    $drive = get_the_terms(get_the_ID(), 'car_drive');
    $status = get_the_terms(get_the_ID(), 'car_status');
    $location = get_the_terms(get_the_ID(), 'car_location');
    
    // Gallery images
    $gallery_ids = !empty($gallery) ? explode(',', $gallery) : [];
    if (has_post_thumbnail()) {
        array_unshift($gallery_ids, get_post_thumbnail_id());
    }
    ?>

<main class="site-main single-car">
    <div class="container">
        <?php
        // Breadcrumbs
        if (function_exists('ai_breadcrumbs')) {
            ai_breadcrumbs();
        }
        ?>
        
        <!-- Car Header -->
        <div class="car-header">
            <h1 class="car-header__title"><?php the_title(); ?></h1>
            <div class="car-header__meta">
                <?php if ($brand && !is_wp_error($brand)): ?>
                    <span class="car-header__brand"><?php echo esc_html($brand[0]->name); ?></span>
                <?php endif; ?>
                <?php if ($year): ?>
                    <span class="car-header__year"><?php echo esc_html($year); ?> г.</span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Main Info Section -->
        <div class="car-main">
            <!-- Gallery -->
            <div class="car-gallery">
                <?php if (!empty($gallery_ids)): ?>
                    <div class="car-gallery__main">
                        <?php 
                        $main_image = wp_get_attachment_image_src($gallery_ids[0], 'car-large');
                        if ($main_image):
                        ?>
                            <img src="<?php echo esc_url($main_image[0]); ?>" alt="<?php the_title(); ?>" class="car-gallery__main-img">
                        <?php endif; ?>
                    </div>
                    
                    <?php if (count($gallery_ids) > 1): ?>
                        <div class="car-gallery__thumbs">
                            <?php foreach ($gallery_ids as $index => $image_id): 
                                $thumb = wp_get_attachment_image_src($image_id, 'thumbnail');
                                if ($thumb):
                            ?>
                                <div class="car-gallery__thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                    <img src="<?php echo esc_url($thumb[0]); ?>" alt="">
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="car-gallery__placeholder">
                        <span class="dashicons dashicons-format-image"></span>
                        <p>Фотографии скоро появятся</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Price & Actions -->
            <div class="car-sidebar">
                <div class="car-price">
                    <?php if ($price): ?>
                        <div class="car-price__amount"><?php echo number_format($price, 0, '', ' '); ?> ₽</div>
                    <?php else: ?>
                        <div class="car-price__amount">Цена по запросу</div>
                    <?php endif; ?>
                    
                    <?php if ($status && !is_wp_error($status)): ?>
                        <div class="car-price__status car-price__status--<?php echo esc_attr($status[0]->slug); ?>">
                            <?php echo esc_html($status[0]->name); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Key Features -->
                <div class="car-features">
                    <h3 class="car-features__title">Основные характеристики</h3>
                    <ul class="car-features__list">
                        <?php if ($year): ?>
                            <li><span>Год:</span> <strong><?php echo esc_html($year); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($mileage): ?>
                            <li><span>Пробег:</span> <strong><?php echo number_format($mileage, 0, '', ' '); ?> км</strong></li>
                        <?php endif; ?>
                        
                        <?php if ($engine_volume): ?>
                            <li><span>Объём двигателя:</span> <strong><?php echo esc_html($engine_volume); ?> л</strong></li>
                        <?php endif; ?>
                        
                        <?php if ($engine_power): ?>
                            <li><span>Мощность:</span> <strong><?php echo esc_html($engine_power); ?> л.с.</strong></li>
                        <?php endif; ?>
                        
                        <?php if ($fuel && !is_wp_error($fuel)): ?>
                            <li><span>Топливо:</span> <strong><?php echo esc_html($fuel[0]->name); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($transmission && !is_wp_error($transmission)): ?>
                            <li><span>КПП:</span> <strong><?php echo esc_html($transmission[0]->name); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($drive && !is_wp_error($drive)): ?>
                            <li><span>Привод:</span> <strong><?php echo esc_html($drive[0]->name); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($body && !is_wp_error($body)): ?>
                            <li><span>Кузов:</span> <strong><?php echo esc_html($body[0]->name); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($color): ?>
                            <li><span>Цвет:</span> <strong><?php echo esc_html($color); ?></strong></li>
                        <?php endif; ?>
                        
                        <?php if ($steering): ?>
                            <li><span>Руль:</span> <strong><?php echo $steering === 'left' ? 'Левый' : 'Правый'; ?></strong></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="car-actions">
                    <a href="#contact-form" class="btn btn--primary btn--large btn--block">Узнать цену с доставкой</a>
                    <a href="#contact-form" class="btn btn--secondary btn--large btn--block">Рассчитать платеж</a>
                    <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--outline btn--large btn--block">Подобрать авто</a>
                </div>
                
                <?php if ($location && !is_wp_error($location)): ?>
                    <div class="car-location">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span><?php echo esc_html($location[0]->name); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Full Specs -->
        <section class="car-specs section">
            <h2 class="section__title">Характеристики</h2>
            
            <div class="specs-table">
                <!-- General Info -->
                <div class="specs-table__section">
                    <h3 class="specs-table__heading">Общая информация</h3>
                    <table class="specs-table__table">
                        <tbody>
                            <?php if ($brand && !is_wp_error($brand)): ?>
                                <tr>
                                    <td>Марка</td>
                                    <td><strong><?php echo esc_html($brand[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($model && !is_wp_error($model)): ?>
                                <tr>
                                    <td>Модель</td>
                                    <td><strong><?php echo esc_html($model[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($year): ?>
                                <tr>
                                    <td>Год выпуска</td>
                                    <td><strong><?php echo esc_html($year); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($condition): ?>
                                <tr>
                                    <td>Состояние</td>
                                    <td><strong><?php echo esc_html($condition); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($owners): ?>
                                <tr>
                                    <td>Владельцы</td>
                                    <td><strong><?php echo esc_html($owners); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($vin): ?>
                                <tr>
                                    <td>VIN</td>
                                    <td><strong><?php echo esc_html($vin); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Engine -->
                <div class="specs-table__section">
                    <h3 class="specs-table__heading">Двигатель</h3>
                    <table class="specs-table__table">
                        <tbody>
                            <?php if ($fuel && !is_wp_error($fuel)): ?>
                                <tr>
                                    <td>Тип двигателя</td>
                                    <td><strong><?php echo esc_html($fuel[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($engine_volume): ?>
                                <tr>
                                    <td>Объём двигателя</td>
                                    <td><strong><?php echo esc_html($engine_volume); ?> л</strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($engine_power): ?>
                                <tr>
                                    <td>Мощность</td>
                                    <td><strong><?php echo esc_html($engine_power); ?> л.с.</strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Body -->
                <div class="specs-table__section">
                    <h3 class="specs-table__heading">Кузов</h3>
                    <table class="specs-table__table">
                        <tbody>
                            <?php if ($body && !is_wp_error($body)): ?>
                                <tr>
                                    <td>Тип кузова</td>
                                    <td><strong><?php echo esc_html($body[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($color): ?>
                                <tr>
                                    <td>Цвет</td>
                                    <td><strong><?php echo esc_html($color); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($steering): ?>
                                <tr>
                                    <td>Руль</td>
                                    <td><strong><?php echo $steering === 'left' ? 'Левый' : 'Правый'; ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Transmission -->
                <div class="specs-table__section">
                    <h3 class="specs-table__heading">Трансмиссия</h3>
                    <table class="specs-table__table">
                        <tbody>
                            <?php if ($transmission && !is_wp_error($transmission)): ?>
                                <tr>
                                    <td>Коробка передач</td>
                                    <td><strong><?php echo esc_html($transmission[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php if ($drive && !is_wp_error($drive)): ?>
                                <tr>
                                    <td>Тип привода</td>
                                    <td><strong><?php echo esc_html($drive[0]->name); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Documents -->
                <?php if ($customs_status || $documents): ?>
                    <div class="specs-table__section">
                        <h3 class="specs-table__heading">Документы</h3>
                        <table class="specs-table__table">
                            <tbody>
                                <?php if ($customs_status): ?>
                                    <tr>
                                        <td>Таможенный статус</td>
                                        <td><strong>
                                            <?php
                                            $statuses = [
                                                'cleared' => 'Растаможен',
                                                'not_cleared' => 'Не растаможен',
                                                'in_process' => 'В процессе',
                                            ];
                                            echo isset($statuses[$customs_status]) ? $statuses[$customs_status] : esc_html($customs_status);
                                            ?>
                                        </strong></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($documents): ?>
                                    <tr>
                                        <td>Документы</td>
                                        <td><strong><?php echo esc_html($documents); ?></strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Equipment -->
        <?php if (!empty($equipment)): ?>
            <section class="car-equipment section section--gray">
                <div class="container">
                    <h2 class="section__title">Комплектация</h2>
                    <div class="equipment-grid">
                        <?php
                        $equipment_array = is_array($equipment) ? $equipment : explode("\n", $equipment);
                        foreach ($equipment_array as $item):
                            if (trim($item)):
                        ?>
                            <div class="equipment-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><?php echo esc_html(trim($item)); ?></span>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Description -->
        <?php if (get_the_content()): ?>
            <section class="car-description section">
                <h2 class="section__title">Описание</h2>
                <div class="car-description__content">
                    <?php the_content(); ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Video -->
        <?php if ($video_url): ?>
            <section class="car-video section section--gray">
                <div class="container">
                    <h2 class="section__title">Видеообзор</h2>
                    <div class="car-video__wrapper">
                        <?php
                        // Extract YouTube ID
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $video_url, $matches);
                        if (!empty($matches[1])):
                        ?>
                            <iframe width="100%" height="500" src="https://www.youtube.com/embed/<?php echo esc_attr($matches[1]); ?>" frameborder="0" allowfullscreen></iframe>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Contact Form -->
        <section id="contact-form" class="car-contact section">
            <div class="container">
                <div class="car-contact__wrapper">
                    <div class="car-contact__info">
                        <h2 class="car-contact__title">Заинтересовал этот автомобиль?</h2>
                        <p class="car-contact__subtitle">Оставьте заявку и мы свяжемся с вами в течение 15 минут</p>
                        
                        <ul class="car-contact__features">
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Бесплатная консультация</span>
                            </li>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Расчёт стоимости с доставкой</span>
                            </li>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Подбор альтернативных вариантов</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="car-contact__form">
                        <form class="lead-form" id="car-lead-form" data-car-id="<?php echo get_the_ID(); ?>" data-car-title="<?php echo esc_attr(get_the_title()); ?>">
                            <div class="form-group">
                                <label for="car-lead-name">Ваше имя <span class="required">*</span></label>
                                <input type="text" id="car-lead-name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="car-lead-phone">Телефон <span class="required">*</span></label>
                                <input type="tel" id="car-lead-phone" name="phone" required placeholder="+7 (999) 123-45-67">
                            </div>
                            
                            <div class="form-group">
                                <label for="car-lead-email">Email</label>
                                <input type="email" id="car-lead-email" name="email" placeholder="your@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="car-lead-comment">Комментарий</label>
                                <textarea id="car-lead-comment" name="comment" rows="4" placeholder="Интересует комплектация, сроки доставки..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn--primary btn--large btn--block">
                                Отправить заявку
                            </button>
                            
                            <div class="form-message" style="display: none;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Related Cars -->
        <?php
        $related_args = [
            'post_type' => 'car',
            'posts_per_page' => 4,
            'post__not_in' => [get_the_ID()],
            'meta_query' => [
                [
                    'key' => 'publish_to_catalog',
                    'value' => '1',
                    'compare' => '='
                ]
            ],
        ];
        
        if ($brand && !is_wp_error($brand)) {
            $related_args['tax_query'] = [
                [
                    'taxonomy' => 'car_brand',
                    'field' => 'term_id',
                    'terms' => $brand[0]->term_id,
                ]
            ];
        }
        
        $related = new WP_Query($related_args);
        
        if ($related->have_posts()):
        ?>
            <section class="related-cars section section--gray">
                <div class="container">
                    <h2 class="section__title">Похожие автомобили</h2>
                    <div class="cars-grid">
                        <?php
                        while ($related->have_posts()) : $related->the_post();
                            get_template_part('template-parts/content', 'car-card');
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
endwhile;

get_footer();