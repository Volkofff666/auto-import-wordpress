<?php
/**
 * Single Car Template - Redesigned to match mockup exactly
 */

get_header();

// Enqueue single car assets
wp_enqueue_style('ai-single-car', get_template_directory_uri() . '/assets/css/single-car.css', ['ai-style'], '2.0.0');
wp_enqueue_script('ai-single-car', get_template_directory_uri() . '/assets/js/single-car.js', ['jquery'], '2.0.0', true);

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
    
    // Format price
    $formatted_price = $price ? number_format($price, 0, '', ' ') : false;
    ?>

<main class="site-main single-car-page">
    <div class="container">
        
        <!-- Top Section: Title + Deal of the Day -->
        <div class="car-top-section">
            <div class="car-top-section__left">
                <h1 class="car-title"><?php the_title(); ?></h1>
                <?php if ($brand && !is_wp_error($brand)): ?>
                    <div class="car-subtitle"><?php echo esc_html($brand[0]->name); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="car-top-section__right">
                <div class="deal-badge">
                    <div class="deal-badge__label">Предложение дня</div>
                    <?php if ($formatted_price): ?>
                        <div class="deal-badge__price">от <?php echo $formatted_price; ?> ₽</div>
                        <div class="deal-badge__old-price">Цена без учёта скидки от <?php echo number_format($price * 1.05, 0, '', ' '); ?> ₽</div>
                    <?php endif; ?>
                    <div class="deal-badge__timer" data-end="2024-08-31 23:59:59">
                        <span class="timer-label">Акция до 31.08.24</span>
                        <span class="timer-countdown">00:00:00</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content: Gallery + Sidebar -->
        <div class="car-main-content">
            <!-- Left: Gallery + Tabs -->
            <div class="car-left-section">
                <!-- Gallery -->
                <div class="car-gallery-main">
                    <?php if (!empty($gallery_ids)): ?>
                        <div class="gallery-slider">
                            <?php foreach ($gallery_ids as $index => $image_id): 
                                $image = wp_get_attachment_image_src($image_id, 'car-large');
                                if ($image):
                            ?>
                                <div class="gallery-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="<?php the_title(); ?>">
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        
                        <?php if (count($gallery_ids) > 1): ?>
                            <div class="gallery-thumbs">
                                <?php foreach ($gallery_ids as $index => $image_id): 
                                    $thumb = wp_get_attachment_image_src($image_id, 'thumbnail');
                                    if ($thumb):
                                ?>
                                    <div class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                        <img src="<?php echo esc_url($thumb[0]); ?>" alt="">
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="gallery-placeholder">
                            <p>Фотографии скоро появятся</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Tabs -->
                <div class="car-tabs">
                    <div class="car-tabs__nav">
                        <button class="car-tabs__tab active" data-tab="specs">Технические характеристики и опции</button>
                        <button class="car-tabs__tab" data-tab="ownership">Стоимость владения</button>
                        <button class="car-tabs__tab" data-tab="gallery">Галерея</button>
                    </div>
                    
                    <div class="car-tabs__content">
                        <!-- Tab 1: Specs -->
                        <div class="car-tabs__pane active" data-pane="specs">
                            <div class="specs-grid">
                                <?php if ($year): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Год выпуска</div>
                                        <div class="spec-item__value"><?php echo esc_html($year); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($engine_volume): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Объём двигателя</div>
                                        <div class="spec-item__value"><?php echo esc_html($engine_volume); ?> л</div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($engine_power): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Мощность</div>
                                        <div class="spec-item__value"><?php echo esc_html($engine_power); ?> л.с.</div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($fuel && !is_wp_error($fuel)): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Топливо</div>
                                        <div class="spec-item__value"><?php echo esc_html($fuel[0]->name); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($transmission && !is_wp_error($transmission)): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">КПП</div>
                                        <div class="spec-item__value"><?php echo esc_html($transmission[0]->name); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($drive && !is_wp_error($drive)): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Привод</div>
                                        <div class="spec-item__value"><?php echo esc_html($drive[0]->name); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($body && !is_wp_error($body)): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Кузов</div>
                                        <div class="spec-item__value"><?php echo esc_html($body[0]->name); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($color): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Цвет</div>
                                        <div class="spec-item__value"><?php echo esc_html($color); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($mileage): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Пробег</div>
                                        <div class="spec-item__value"><?php echo number_format($mileage, 0, '', ' '); ?> км</div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($steering): ?>
                                    <div class="spec-item">
                                        <div class="spec-item__label">Руль</div>
                                        <div class="spec-item__value"><?php echo $steering === 'left' ? 'Левый' : 'Правый'; ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($equipment)): ?>
                                <div class="equipment-section">
                                    <h3>Комплектация</h3>
                                    <ul class="equipment-list">
                                        <?php
                                        $equipment_array = is_array($equipment) ? $equipment : explode("\n", $equipment);
                                        foreach ($equipment_array as $item):
                                            if (trim($item)):
                                        ?>
                                            <li><?php echo esc_html(trim($item)); ?></li>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Tab 2: Ownership Cost -->
                        <div class="car-tabs__pane" data-pane="ownership">
                            <div class="ownership-info">
                                <h3>Стоимость владения</h3>
                                <p>Рассчитайте примерную стоимость владения автомобилем за год:</p>
                                
                                <div class="ownership-calculator">
                                    <div class="calc-item">
                                        <span>ОСАГО</span>
                                        <strong>≈ 15 000 ₽/год</strong>
                                    </div>
                                    <div class="calc-item">
                                        <span>КАСКО</span>
                                        <strong>≈ <?php echo $price ? number_format($price * 0.05, 0, '', ' ') : '50 000'; ?> ₽/год</strong>
                                    </div>
                                    <div class="calc-item">
                                        <span>Обслуживание (ТО)</span>
                                        <strong>≈ 30 000 ₽/год</strong>
                                    </div>
                                    <div class="calc-item">
                                        <span>Транспортный налог</span>
                                        <strong>≈ <?php echo $engine_power ? number_format($engine_power * 25, 0, '', ' ') : '4 500'; ?> ₽/год</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab 3: Gallery -->
                        <div class="car-tabs__pane" data-pane="gallery">
                            <?php if (!empty($gallery_ids)): ?>
                                <div class="gallery-grid">
                                    <?php foreach ($gallery_ids as $image_id): 
                                        $full_image = wp_get_attachment_image_src($image_id, 'car-large');
                                        if ($full_image):
                                    ?>
                                        <a href="<?php echo esc_url($full_image[0]); ?>" class="gallery-grid__item" data-lightbox="car-gallery">
                                            <img src="<?php echo esc_url($full_image[0]); ?>" alt="<?php the_title(); ?>">
                                        </a>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            <?php else: ?>
                                <p>Нет фотографий</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right: Sidebar with Calculator -->
            <div class="car-sidebar-section">
                <div class="price-card">
                    <?php if ($formatted_price): ?>
                        <div class="price-card__amount">от <?php echo $formatted_price; ?> ₽</div>
                    <?php else: ?>
                        <div class="price-card__amount">Цена по запросу</div>
                    <?php endif; ?>
                    
                    <div class="price-card__calc">
                        <h4>Рассчитайте условия по кредиту на <?php echo $model && !is_wp_error($model) ? esc_html($model[0]->name) : get_the_title(); ?></h4>
                        
                        <div class="credit-calculator">
                            <div class="calc-row">
                                <label>Первоначальный взнос</label>
                                <input type="range" class="calc-slider" id="down-payment" min="0" max="<?php echo $price; ?>" value="<?php echo $price * 0.2; ?>" step="10000">
                                <output class="calc-value" for="down-payment"><?php echo $price ? number_format($price * 0.2, 0, '', ' ') : '200 000'; ?> ₽</output>
                            </div>
                            
                            <div class="calc-row">
                                <label>Срок кредита (мес.)</label>
                                <input type="range" class="calc-slider" id="loan-term" min="12" max="84" value="60" step="12">
                                <output class="calc-value" for="loan-term">60 мес.</output>
                            </div>
                            
                            <div class="calc-result">
                                <div class="calc-result__label">Ежемесячный платёж</div>
                                <div class="calc-result__value" id="monthly-payment">34 344 ₽</div>
                            </div>
                            
                            <div class="bank-rates">
                                <div class="bank-rates__title">Ставка от 6.5% у банков-партнёров</div>
                                <div class="bank-rates__chart">
                                    <div class="rate-bar" style="width: 22.5%" data-rate="22.5">22.5%</div>
                                    <div class="rate-bar" style="width: 12.5%" data-rate="12.5">12.5%</div>
                                    <div class="rate-bar active" style="width: 6.5%" data-rate="6.5">6.5%</div>
                                    <div class="rate-bar" style="width: 5.5%" data-rate="5.5">5.5%</div>
                                </div>
                            </div>
                            
                            <button class="btn btn--primary btn--block" onclick="document.getElementById('lead-form').scrollIntoView({behavior: 'smooth'});">Получить предложение</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Special Offers -->
        <section class="special-offers">
            <h2>Спецпредложения на <?php echo $model && !is_wp_error($model) ? esc_html($model[0]->name) : get_the_title(); ?> в <?php echo $location && !is_wp_error($location) ? esc_html($location[0]->name) : 'вашем городе'; ?></h2>
            
            <div class="offers-grid">
                <div class="offer-card">
                    <div class="offer-card__icon">💰</div>
                    <div class="offer-card__amount">200 000 ₽</div>
                    <div class="offer-card__text">Скидка</div>
                    <a href="#lead-form" class="btn btn--outline btn--small">Получить предложение</a>
                </div>
                
                <div class="offer-card">
                    <div class="offer-card__icon">🔧</div>
                    <div class="offer-card__amount">Два первых ТО</div>
                    <div class="offer-card__text">на наш счёт</div>
                    <a href="#lead-form" class="btn btn--outline btn--small">Получить предложение</a>
                </div>
                
                <div class="offer-card">
                    <div class="offer-card__icon">📋</div>
                    <div class="offer-card__amount">Покупка по госпрограмме</div>
                    <div class="offer-card__text">со скидкой до 20%</div>
                    <a href="#lead-form" class="btn btn--outline btn--small">Получить предложение</a>
                </div>
                
                <div class="offer-card">
                    <div class="offer-card__icon">🔄</div>
                    <div class="offer-card__amount">Обменять свой автомобиль</div>
                    <div class="offer-card__text">на <?php echo $model && !is_wp_error($model) ? esc_html($model[0]->name) : 'новый'; ?></div>
                    <a href="#lead-form" class="btn btn--outline btn--small">Получить предложение</a>
                </div>
            </div>
        </section>
        
        <!-- Lead Form -->
        <section id="lead-form" class="car-lead-section">
            <div class="lead-section__content">
                <h2>Ответим на все ваши вопросы</h2>
                <p>Оставьте заявку и мы свяжемся с вами в течение 15 минут</p>
                
                <form class="lead-form" id="car-lead-form" data-car-id="<?php echo get_the_ID(); ?>" data-car-title="<?php echo esc_attr(get_the_title()); ?>">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-row">
                        <input type="email" name="email" placeholder="Email (необязательно)">
                        <textarea name="comment" placeholder="Комментарий" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--large">Отправить заявку</button>
                    <div class="form-message" style="display: none;"></div>
                </form>
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
            <section class="related-cars">
                <h2>Другие модели <?php echo $brand && !is_wp_error($brand) ? esc_html($brand[0]->name) : ''; ?></h2>
                <div class="cars-grid">
                    <?php
                    while ($related->have_posts()) : $related->the_post();
                        get_template_part('template-parts/content', 'car-card');
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
endwhile;

get_footer();