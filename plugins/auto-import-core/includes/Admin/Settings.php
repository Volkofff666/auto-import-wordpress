<?php
namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Settings {
    
    public static function init() {
        if (!is_admin()) {
            return;
        }
        
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
    }
    
    public static function add_menu() {
        add_menu_page(
            __('Auto Import Settings', 'auto-import-core'),
            __('Auto Import', 'auto-import-core'),
            'manage_options',
            'auto-import-settings',
            [self::class, 'render_page'],
            'dashicons-car',
            30
        );
    }
    
    public static function register_settings() {
        register_setting('aic_settings', 'aic_company_phone');
        register_setting('aic_settings', 'aic_company_email');
        register_setting('aic_settings', 'aic_company_address');
        register_setting('aic_settings', 'aic_company_schedule');
        
        add_settings_section(
            'aic_company_section',
            __('Company Information', 'auto-import-core'),
            null,
            'aic_settings'
        );
        
        add_settings_field(
            'aic_company_phone',
            __('Phone', 'auto-import-core'),
            [self::class, 'render_text_field'],
            'aic_settings',
            'aic_company_section',
            ['name' => 'aic_company_phone', 'placeholder' => '+7 (999) 123-45-67']
        );
        
        add_settings_field(
            'aic_company_email',
            __('Email', 'auto-import-core'),
            [self::class, 'render_text_field'],
            'aic_settings',
            'aic_company_section',
            ['name' => 'aic_company_email', 'placeholder' => 'info@example.com', 'type' => 'email']
        );
        
        add_settings_field(
            'aic_company_address',
            __('Address', 'auto-import-core'),
            [self::class, 'render_text_field'],
            'aic_settings',
            'aic_company_section',
            ['name' => 'aic_company_address', 'placeholder' => 'Moscow, Red Square 1']
        );
        
        add_settings_field(
            'aic_company_schedule',
            __('Schedule', 'auto-import-core'),
            [self::class, 'render_textarea_field'],
            'aic_settings',
            'aic_company_section',
            ['name' => 'aic_company_schedule', 'placeholder' => 'Mon-Fri: 9:00-18:00']
        );
    }
    
    public static function render_text_field($args) {
        $name = $args['name'];
        $value = get_option($name);
        $type = isset($args['type']) ? $args['type'] : 'text';
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        
        printf(
            '<input type="%s" name="%s" value="%s" placeholder="%s" class="regular-text">',
            esc_attr($type),
            esc_attr($name),
            esc_attr($value),
            esc_attr($placeholder)
        );
    }
    
    public static function render_textarea_field($args) {
        $name = $args['name'];
        $value = get_option($name);
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        
        printf(
            '<textarea name="%s" rows="4" class="large-text" placeholder="%s">%s</textarea>',
            esc_attr($name),
            esc_attr($placeholder),
            esc_textarea($value)
        );
    }
    
    public static function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        if (isset($_GET['settings-updated'])) {
            add_settings_error('aic_messages', 'aic_message', __('Settings Saved', 'auto-import-core'), 'updated');
        }
        
        settings_errors('aic_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aic_settings');
                do_settings_sections('aic_settings');
                submit_button(__('Save Settings', 'auto-import-core'));
                ?>
            </form>
        </div>
        <?php
    }
}