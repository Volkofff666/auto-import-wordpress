<?php
/**
 * Front Page Template - Homepage
 * Redesigned to match Glavnaia.pdf mockup
 */

get_header();

// Enqueue homepage assets
wp_enqueue_style('ai-homepage', get_template_directory_uri() . '/assets/css/front-page.css', ['ai-style'], '2.0.0');
wp_enqueue_script('ai-homepage', get_template_directory_uri() . '/assets/js/homepage.js', ['jquery'], '2.0.0', true);

// Get settings
$site_name = get_option('ai_site_name', 'АвтоСалон');
$phone = get_option('ai_phone', '+7 (965) 550-80-86');
$location = get_option('ai_location', 'Новосибирске');

// Get featured cars
$featured_args = [
    'post_type' => 'car',
    'posts_per_page' => 8,
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

$featured_query = new WP_Query($featured_args);
?>

<main class="site-main homepage">
    
    <!-- Hero Banner: Liquidation -->
    <section class="hero-liquidation">
        <div class="container">
            <div class="hero-liquidation__content">
                <div class="hero-liquidation__badge">ЛИКВИДАЦИЯ СКЛАДА</div>
                <h1 class="hero-liquidation__title">2024 ГОДА</h1>
                <div class="hero-liquidation__timer" data-end="2024-12-31 23:59:59">
                    <div class="timer-item">
                        <span class="timer-value" id="days">00</span>
                        <span class="timer-label">дней</span>
                    </div>
                    <div class="timer-separator">:</div>
                    <div class="timer-item">
                        <span class="timer-value" id="hours">00</span>
                        <span class="timer-label">часов</span>
                    </div>
                    <div class="timer-separator">:</div>
                    <div class="timer-item">
                        <span class="timer-value" id="minutes">00</span>
                        <span class="timer-label">минут</span>
                    </div>
                    <div class="timer-separator">:</div>
                    <div class="timer-item">
                        <span class="timer-value" id="seconds">00</span>
                        <span class="timer-label">секунд</span>
                    </div>
                </div>
                <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--large btn--hero">Смотреть все автомобили</a>
            </div>
        </div>
    </section>
    
    <!-- Promo Blocks -->
    <section class="promo-blocks">
        <div class="container">
            <div class="promo-grid">
                <!-- Credit Block -->
                <div class="promo-card promo-card--primary">
                    <div class="promo-card__icon">💳</div>
                    <h3 class="promo-card__title">Автокредит от 7,9%</h3>
                    <p class="promo-card__text">на все автомобили</p>
                    <div class="promo-card__value">7,9%</div>
                    <a href="#contact-form" class="btn btn--outline btn--white">Рассчитать</a>
                </div>
                
                <!-- Discounts Block -->
                <div class="promo-card promo-card--secondary">
                    <div class="promo-card__icon">🎁</div>
                    <h3 class="promo-card__title">Скидки на авто</h3>
                    <p class="promo-card__text">до</p>
                    <div class="promo-card__value">50%</div>
                    <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--outline btn--white">Подробнее</a>
                </div>
                
                <!-- Gifts Block -->
                <div class="promo-card promo-card--accent">
                    <div class="promo-card__label">СКИДКА</div>
                    <div class="promo-card__amount">200 000 ₽</div>
                    <h3 class="promo-card__title">Подарки для вас</h3>
                    <ul class="promo-card__list">
                        <li>Зимняя резина</li>
                        <li>Коврики</li>
                        <li>Сигнализация</li>
                    </ul>
                    <a href="#contact-form" class="btn btn--primary">Получить подарок</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Popular Cars Collections -->
    <section class="popular-collections">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Подборки популярных автомобилей</h2>
                <a href="<?php echo get_post_type_archive_link('car'); ?>" class="section-link">Показать все →</a>
            </div>
            
            <?php if ($featured_query->have_posts()): ?>
                <div class="cars-slider">
                    <?php
                    while ($featured_query->have_posts()) : $featured_query->the_post();
                        get_template_part('template-parts/content', 'car-card');
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Help Section -->
    <section class="help-section">
        <div class="container">
            <div class="help-content">
                <div class="help-content__text">
                    <h2>Бесплатно поможем с подбором авто</h2>
                    <p>Ответим на все ваши вопросы</p>
                </div>
                <a href="#contact-form" class="btn btn--primary btn--large">Получить консультацию</a>
            </div>
        </div>
    </section>
    
    <!-- Competitor Offer -->
    <section class="competitor-offer">
        <div class="container">
            <div class="competitor-card">
                <div class="competitor-card__icon">🏆</div>
                <h3 class="competitor-card__title">Есть предложение от конкурентов?</h3>
                <p class="competitor-card__text">Мы предложим лучшие условия! Оставьте заявку и получите персональное предложение.</p>
                <form class="competitor-form" id="competitor-form">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-row">
                        <input type="text" name="competitor_price" placeholder="Цена конкурента" required>
                        <button type="submit" class="btn btn--primary btn--large">Получить лучшее предложение</button>
                    </div>
                    <div class="form-message" style="display: none;"></div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Trust Blocks -->
    <section class="trust-blocks">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-item__number">01</div>
                    <h4 class="trust-item__title">Надёжность</h4>
                    <p class="trust-item__text">Надёжная и оперативная доставка автомобилей</p>
                </div>
                
                <div class="trust-item">
                    <div class="trust-item__number">02</div>
                    <h4 class="trust-item__title">Широкий выбор</h4>
                    <p class="trust-item__text">Широкий ассортимент автомобилей</p>
                </div>
                
                <div class="trust-item">
                    <div class="trust-item__number">03</div>
                    <h4 class="trust-item__title">Полное сопровождение</h4>
                    <p class="trust-item__text">Полное сопровождение вашей сделки</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Form -->
    <section id="contact-form" class="homepage-contact">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2>Нужна помощь в подборе автомобиля?</h2>
                    <p>Обратитесь к нашей команде <?php echo esc_html($site_name); ?>, мы с радостью свяжемся с вами и проконсультируем по выбору</p>
                    
                    <div class="contact-details">
                        <div class="contact-detail">
                            <strong>Телефон:</strong>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                        </div>
                        <div class="contact-detail">
                            <strong>Адрес:</strong>
                            <span><?php echo get_option('ai_address', 'г. ' . $location); ?></span>
                        </div>
                        <div class="contact-detail">
                            <strong>График работы:</strong>
                            <span><?php echo get_option('ai_schedule', 'Пн-Вс: 9:00 - 21:00'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-wrapper">
                    <form class="main-contact-form" id="main-contact-form">
                        <input type="text" name="name" placeholder="Ваше имя..." required>
                        <input type="tel" name="phone" placeholder="+7(___)_______" required>
                        <textarea name="comment" placeholder="Ваше сообщение" rows="4"></textarea>
                        <button type="submit" class="btn btn--primary btn--large btn--block">Отправить</button>
                        <div class="form-message" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php
get_footer();