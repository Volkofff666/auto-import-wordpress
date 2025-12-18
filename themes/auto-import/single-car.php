<?php
/**
 * Single Car Template
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    
    // Get car meta
    $price = get_post_meta(get_the_ID(), 'aic_price', true);
    $year = get_post_meta(get_the_ID(), 'aic_year', true);
    $mileage = get_post_meta(get_the_ID(), 'aic_mileage', true);
    $vin = get_post_meta(get_the_ID(), 'aic_vin', true);
    $color = get_post_meta(get_the_ID(), 'aic_color', true);
    $engine_volume = get_post_meta(get_the_ID(), 'aic_engine_volume', true);
    $engine_power = get_post_meta(get_the_ID(), 'aic_engine_power', true);
    $condition = get_post_meta(get_the_ID(), 'aic_condition', true);
    $equipment = get_post_meta(get_the_ID(), 'aic_equipment', true);
    
    // Get taxonomies
    $brands = get_the_terms(get_the_ID(), 'brand');
    $models = get_the_terms(get_the_ID(), 'model');
    $body_types = get_the_terms(get_the_ID(), 'body_type');
    $fuel_types = get_the_terms(get_the_ID(), 'fuel');
    $transmissions = get_the_terms(get_the_ID(), 'transmission');
    $drives = get_the_terms(get_the_ID(), 'drive');
    $statuses = get_the_terms(get_the_ID(), 'status');
    $locations = get_the_terms(get_the_ID(), 'location');
    ?>
    
    <div class="container">
        <?php
        // Breadcrumbs
        if (function_exists('auto_import_breadcrumbs')) {
            auto_import_breadcrumbs();
        }
        ?>
        
        <div class="single-car-layout">
            <div class="single-car-layout__header">
                <div class="single-car-header">
                    <div class="single-car-header__main">
                        <h1 class="single-car-header__title"><?php the_title(); ?></h1>
                        
                        <?php if ($statuses && !is_wp_error($statuses)) : ?>
                            <span class="badge badge--<?php echo esc_attr($statuses[0]->slug === 'в-наличии' ? 'success' : 'warning'); ?>">
                                <?php echo esc_html($statuses[0]->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($price) : ?>
                        <div class="single-car-header__price">
                            <?php echo number_format($price, 0, '.', ' '); ?> ₽
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="single-car-layout__body">
                <div class="single-car-main">
                    <!-- Gallery -->
                    <?php get_template_part('template-parts/car', 'gallery'); ?>
                    
                    <!-- Description -->
                    <?php if (get_the_content()) : ?>
                        <div class="single-car-section">
                            <h2><?php esc_html_e('Description', 'auto-import'); ?></h2>
                            <div class="single-car-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Technical Specifications -->
                    <div class="single-car-section">
                        <h2><?php esc_html_e('Technical Specifications', 'auto-import'); ?></h2>
                        <div class="specs-grid">
                            <?php if ($year) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Year', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($year); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($mileage) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Mileage', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo number_format($mileage, 0, '.', ' '); ?> км</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($body_types && !is_wp_error($body_types)) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Body Type', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($body_types[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($color) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Color', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($color); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($engine_volume) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Engine Volume', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($engine_volume); ?> л</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($engine_power) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Power', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($engine_power); ?> л.с.</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($fuel_types && !is_wp_error($fuel_types)) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Fuel Type', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($fuel_types[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($transmissions && !is_wp_error($transmissions)) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Transmission', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($transmissions[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($drives && !is_wp_error($drives)) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('Drive Type', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($drives[0]->name); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($vin) : ?>
                                <div class="spec-item">
                                    <span class="spec-item__label"><?php esc_html_e('VIN', 'auto-import'); ?></span>
                                    <span class="spec-item__value"><?php echo esc_html($vin); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Equipment -->
                    <?php if ($equipment) : ?>
                        <div class="single-car-section">
                            <h2><?php esc_html_e('Equipment', 'auto-import'); ?></h2>
                            <ul class="equipment-list">
                                <?php
                                $equipment_items = explode("\n", $equipment);
                                foreach ($equipment_items as $item) {
                                    $item = trim($item);
                                    if (!empty($item)) {
                                        echo '<li>' . esc_html($item) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Condition -->
                    <?php if ($condition) : ?>
                        <div class="single-car-section">
                            <h2><?php esc_html_e('Condition', 'auto-import'); ?></h2>
                            <p><?php echo nl2br(esc_html($condition)); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="single-car-sidebar">
                    <div class="sticky" style="top: 100px;">
                        <!-- Contact Form -->
                        <div class="card">
                            <h3><?php esc_html_e('Interested in this car?', 'auto-import'); ?></h3>
                            <p class="text-sm text-muted"><?php esc_html_e('Leave your contact information and we will get back to you shortly.', 'auto-import'); ?></p>
                            
                            <form class="lead-form" id="aic-lead-form">
                                <input type="hidden" name="car_id" value="<?php the_ID(); ?>">
                                <input type="hidden" name="car_title" value="<?php echo esc_attr(get_the_title()); ?>">
                                
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="<?php esc_attr_e('Your Name', 'auto-import'); ?>" required class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <input type="tel" name="phone" placeholder="<?php esc_attr_e('Phone Number', 'auto-import'); ?>" required class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="<?php esc_attr_e('Email (optional)', 'auto-import'); ?>" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <textarea name="comment" rows="3" placeholder="<?php esc_attr_e('Your Message', 'auto-import'); ?>" class="form-control"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn--primary btn--block">
                                    <?php esc_html_e('Send Request', 'auto-import'); ?>
                                </button>
                                
                                <div class="lead-form__message" style="display: none;"></div>
                            </form>
                        </div>
                        
                        <!-- Contact Info -->
                        <?php
                        $settings = get_option('aic_settings', []);
                        if (!empty($settings['company_phone']) || !empty($settings['company_email'])) :
                        ?>
                            <div class="card mt-4">
                                <h3><?php esc_html_e('Contact Us', 'auto-import'); ?></h3>
                                
                                <?php if (!empty($settings['company_phone'])) : ?>
                                    <p>
                                        <strong><?php esc_html_e('Phone:', 'auto-import'); ?></strong><br>
                                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $settings['company_phone'])); ?>">
                                            <?php echo esc_html($settings['company_phone']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($settings['company_email'])) : ?>
                                    <p>
                                        <strong><?php esc_html_e('Email:', 'auto-import'); ?></strong><br>
                                        <a href="mailto:<?php echo esc_attr($settings['company_email']); ?>">
                                            <?php echo esc_html($settings['company_email']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    
<?php
endwhile;

get_footer();
