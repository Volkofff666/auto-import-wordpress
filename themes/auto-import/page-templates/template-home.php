<?php
/**
 * Template Name: Home Page (Static)
 * Description: Full static home page with all sections
 */

get_header();
?>

<main class="site-main home-page">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div>
        <div class="hero__content container">
            <h1 class="hero__title"><?php echo esc_html(get_option('aic_hero_title', 'Импорт автомобилей из США, Европы, Японии')); ?></h1>
            <p class="hero__subtitle"><?php echo esc_html(get_option('aic_hero_subtitle', 'Поможем купить и доставить автомобиль вашей мечты с гарантией качества')); ?></p>
            <div class="hero__actions">
                <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--primary btn--large">
                    Смотреть каталог
                </a>
                <a href="#contact-form" class="btn btn--secondary btn--large">
                    Оставить заявку
                </a>
            </div>
        </div>
    </section>

    <!-- Trust Bar -->
    <section class="trust-bar">
        <div class="container">
            <div class="trust-bar__grid">
                <div class="trust-bar__item">
                    <div class="trust-bar__icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h3 class="trust-bar__title">Гарантия качества</h3>
                    <p class="trust-bar__text">Проверка каждого автомобиля перед отправкой</p>
                </div>
                
                <div class="trust-bar__item">
                    <div class="trust-bar__icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3 class="trust-bar__title">Быстрая доставка</h3>
                    <p class="trust-bar__text">От 2 до 4 недель в зависимости от локации</p>
                </div>
                
                <div class="trust-bar__item">
                    <div class="trust-bar__icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h3 class="trust-bar__title">Полное сопровождение</h3>
                    <p class="trust-bar__text">Помощь с растаможкой и постановкой на учёт</p>
                </div>
                
                <div class="trust-bar__item">
                    <div class="trust-bar__icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="trust-bar__title">Опытная команда</h3>
                    <p class="trust-bar__text">Более 500 успешно импортированных автомобилей</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Cars -->
    <section class="featured-cars section">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">Популярные автомобили</h2>
                <p class="section__subtitle">Актуальные предложения в наличии и под заказ</p>
            </div>
            
            <div class="cars-grid">
                <?php
                $args = [
                    'post_type' => 'car',
                    'posts_per_page' => 6,
                    'meta_query' => [
                        [
                            'key' => 'publish_to_catalog',
                            'value' => '1',
                            'compare' => '='
                        ]
                    ],
                    'orderby' => 'date',
                    'order' => 'DESC',
                ];
                
                $cars_query = new WP_Query($args);
                
                if ($cars_query->have_posts()) :
                    while ($cars_query->have_posts()) : $cars_query->the_post();
                        get_template_part('template-parts/content', 'car-card');
                    endwhile;
                    wp_reset_postdata();
                else:
                    ?>
                    <div class="no-cars">
                        <p>Автомобили скоро появятся в каталоге</p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
            
            <div class="section__footer">
                <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--primary btn--large">
                    Смотреть все автомобили
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works section section--gray">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">Как мы работаем</h2>
                <p class="section__subtitle">Простой процесс покупки в 5 шагов</p>
            </div>
            
            <div class="steps">
                <div class="step">
                    <div class="step__number">1</div>
                    <h3 class="step__title">Выбор автомобиля</h3>
                    <p class="step__text">Подбираем автомобиль по вашим критериям из каталога или под заказ</p>
                </div>
                
                <div class="step">
                    <div class="step__number">2</div>
                    <h3 class="step__title">Проверка и покупка</h3>
                    <p class="step__text">Проверяем историю, состояние и документы. Покупаем на аукционе или у дилера</p>
                </div>
                
                <div class="step">
                    <div class="step__number">3</div>
                    <h3 class="step__title">Доставка</h3>
                    <p class="step__text">Организуем доставку морем или автовозом до вашего города</p>
                </div>
                
                <div class="step">
                    <div class="step__number">4</div>
                    <h3 class="step__title">Растаможка</h3>
                    <p class="step__text">Помогаем с растаможкой и оформлением всех документов</p>
                </div>
                
                <div class="step">
                    <div class="step__number">5</div>
                    <h3 class="step__title">Получение</h3>
                    <p class="step__text">Ставим на учёт и передаём вам ключи от автомобиля</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact-form" class="contact-form section">
        <div class="container">
            <div class="contact-form__wrapper">
                <div class="contact-form__info">
                    <h2 class="contact-form__title">Получить консультацию</h2>
                    <p class="contact-form__subtitle">Оставьте заявку и мы свяжемся с вами в течение 15 минут</p>
                    
                    <div class="contact-info">
                        <?php if ($phone = get_option('aic_company_phone')): ?>
                        <div class="contact-info__item">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($email = get_option('aic_company_email')): ?>
                        <div class="contact-info__item">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($schedule = get_option('aic_company_schedule')): ?>
                        <div class="contact-info__item">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span><?php echo nl2br(esc_html($schedule)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="contact-form__form">
                    <form class="lead-form" id="home-lead-form">
                        <div class="form-group">
                            <label for="lead-name">Ваше имя <span class="required">*</span></label>
                            <input type="text" id="lead-name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lead-phone">Телефон <span class="required">*</span></label>
                            <input type="tel" id="lead-phone" name="phone" required placeholder="+7 (999) 123-45-67">
                        </div>
                        
                        <div class="form-group">
                            <label for="lead-email">Email</label>
                            <input type="email" id="lead-email" name="email" placeholder="your@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="lead-budget">Ваш бюджет (₽)</label>
                            <input type="number" id="lead-budget" name="budget" step="100000" placeholder="2000000">
                        </div>
                        
                        <div class="form-group">
                            <label for="lead-comment">Комментарий</label>
                            <textarea id="lead-comment" name="comment" rows="4" placeholder="Расскажите о ваших предпочтениях..."></textarea>
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
</main>

<?php
get_footer();