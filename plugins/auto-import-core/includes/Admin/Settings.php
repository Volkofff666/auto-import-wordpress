<?php
namespace AIC\Admin;

class Settings {
    
    public static function init() {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
    }
    
    public static function add_menu() {
        add_options_page(
            __('Настройки Auto Import', 'auto-import-core'),
            __('Auto Import', 'auto-import-core'),
            'manage_options',
            'auto-import-settings',
            [self::class, 'render_page']
        );
    }
    
    public static function register_settings() {
        register_setting('aic_settings', 'aic_company_phone');
        register_setting('aic_settings', 'aic_company_address');
        register_setting('aic_settings', 'aic_company_schedule');
        register_setting('aic_settings', 'aic_company_email');
        register_setting('aic_settings', 'aic_admin_email');
        register_setting('aic_settings', 'aic_trust_text');
        register_setting('aic_settings', 'aic_seo_title_template');
        register_setting('aic_settings', 'aic_seo_description_template');
    }
    
    public static function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        if (isset($_GET['settings-updated'])) {
            add_settings_error('aic_messages', 'aic_message', __('Настройки сохранены', 'auto-import-core'), 'updated');
        }
        
        settings_errors('aic_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aic_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th colspan="2"><h2>Контактная информация</h2></th>
                    </tr>
                    <tr>
                        <th><label for="aic_company_phone">Телефон компании</label></th>
                        <td><input type="text" id="aic_company_phone" name="aic_company_phone" value="<?php echo esc_attr(get_option('aic_company_phone')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="aic_company_email">Email компании</label></th>
                        <td><input type="email" id="aic_company_email" name="aic_company_email" value="<?php echo esc_attr(get_option('aic_company_email')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="aic_company_address">Адрес</label></th>
                        <td><input type="text" id="aic_company_address" name="aic_company_address" value="<?php echo esc_attr(get_option('aic_company_address')); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th><label for="aic_company_schedule">График работы</label></th>
                        <td><input type="text" id="aic_company_schedule" name="aic_company_schedule" value="<?php echo esc_attr(get_option('aic_company_schedule')); ?>" class="regular-text" placeholder="Пн-Пт 9:00-18:00"></td>
                    </tr>
                    <tr>
                        <th><label for="aic_admin_email">Email для уведомлений</label></th>
                        <td>
                            <input type="email" id="aic_admin_email" name="aic_admin_email" value="<?php echo esc_attr(get_option('aic_admin_email', get_option('admin_email'))); ?>" class="regular-text">
                            <p class="description">На этот адрес будут приходить уведомления о новых заявках</p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2>Тексты</h2></th>
                    </tr>
                    <tr>
                        <th><label for="aic_trust_text">Текст доверия</label></th>
                        <td><textarea id="aic_trust_text" name="aic_trust_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('aic_trust_text')); ?></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2"><h2>SEO</h2></th>
                    </tr>
                    <tr>
                        <th><label for="aic_seo_title_template">Шаблон Title для авто</label></th>
                        <td>
                            <input type="text" id="aic_seo_title_template" name="aic_seo_title_template" value="<?php echo esc_attr(get_option('aic_seo_title_template', '{brand} {model} {year} - купить в {city}')); ?>" class="large-text">
                            <p class="description">Доступны: {brand}, {model}, {year}, {price}, {city}</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="aic_seo_description_template">Шаблон Description для авто</label></th>
                        <td>
                            <textarea id="aic_seo_description_template" name="aic_seo_description_template" rows="2" class="large-text"><?php echo esc_textarea(get_option('aic_seo_description_template', '{brand} {model} {year} года по цене {price} ₽. Пробег {mileage} км. Звоните!')); ?></textarea>
                            <p class="description">Доступны: {brand}, {model}, {year}, {price}, {mileage}</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Сохранить настройки'); ?>
            </form>
        </div>
        <?php
    }
}