<?php
namespace AIC\Admin;

class Settings {
    
    public static function init() {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
    }
    
    public static function add_menu() {
        add_menu_page(
            __('Настройки Auto Import', 'auto-import-core'),
            __('Auto Import', 'auto-import-core'),
            'manage_options',
            'auto-import-settings',
            [self::class, 'render_page'],
            'dashicons-admin-settings',
            30
        );
    }
    
    public static function register_settings() {
        register_setting('aic_settings', 'aic_company_phone');
        register_setting('aic_settings', 'aic_company_email');
        register_setting('aic_settings', 'aic_company_address');
        register_setting('aic_settings', 'aic_company_schedule');
        register_setting('aic_settings', 'aic_notification_email');
        register_setting('aic_settings', 'aic_trust_text_1');
        register_setting('aic_settings', 'aic_trust_text_2');
        register_setting('aic_settings', 'aic_trust_text_3');
        register_setting('aic_settings', 'aic_seo_title_template');
        register_setting('aic_settings', 'aic_seo_description_template');
        
        add_settings_section(
            'aic_company_section',
            __('Информация о компании', 'auto-import-core'),
            null,
            'aic_settings'
        );
        
        add_settings_section(
            'aic_trust_section',
            __('Блок доверия', 'auto-import-core'),
            null,
            'aic_settings'
        );
        
        add_settings_section(
            'aic_seo_section',
            __('SEO настройки', 'auto-import-core'),
            null,
            'aic_settings'
        );
        
        // Company fields
        add_settings_field('aic_company_phone', __('Телефон', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_company_section', ['name' => 'aic_company_phone']);
        add_settings_field('aic_company_email', __('Email', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_company_section', ['name' => 'aic_company_email']);
        add_settings_field('aic_company_address', __('Адрес', 'auto-import-core'), [self::class, 'render_textarea_field'], 'aic_settings', 'aic_company_section', ['name' => 'aic_company_address']);
        add_settings_field('aic_company_schedule', __('График работы', 'auto-import-core'), [self::class, 'render_textarea_field'], 'aic_settings', 'aic_company_section', ['name' => 'aic_company_schedule']);
        add_settings_field('aic_notification_email', __('Email для уведомлений', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_company_section', ['name' => 'aic_notification_email']);
        
        // Trust fields
        add_settings_field('aic_trust_text_1', __('Текст преимущества 1', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_trust_section', ['name' => 'aic_trust_text_1']);
        add_settings_field('aic_trust_text_2', __('Текст преимущества 2', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_trust_section', ['name' => 'aic_trust_text_2']);
        add_settings_field('aic_trust_text_3', __('Текст преимущества 3', 'auto-import-core'), [self::class, 'render_text_field'], 'aic_settings', 'aic_trust_section', ['name' => 'aic_trust_text_3']);
        
        // SEO fields
        add_settings_field('aic_seo_title_template', __('Шаблон title', 'auto-import-core'), [self::class, 'render_textarea_field'], 'aic_settings', 'aic_seo_section', ['name' => 'aic_seo_title_template', 'description' => __('Доступные переменные: {brand}, {model}, {year}, {price}', 'auto-import-core')]);
        add_settings_field('aic_seo_description_template', __('Шаблон description', 'auto-import-core'), [self::class, 'render_textarea_field'], 'aic_settings', 'aic_seo_section', ['name' => 'aic_seo_description_template', 'description' => __('Доступные переменные: {brand}, {model}, {year}, {price}', 'auto-import-core')]);
    }
    
    public static function render_text_field($args) {
        $value = get_option($args['name']);
        printf(
            '<input type="text" name="%s" value="%s" class="regular-text">',
            esc_attr($args['name']),
            esc_attr($value)
        );
        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', esc_html($args['description']));
        }
    }
    
    public static function render_textarea_field($args) {
        $value = get_option($args['name']);
        printf(
            '<textarea name="%s" class="large-text" rows="3">%s</textarea>',
            esc_attr($args['name']),
            esc_textarea($value)
        );
        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', esc_html($args['description']));
        }
    }
    
    public static function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aic_settings');
                do_settings_sections('aic_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}