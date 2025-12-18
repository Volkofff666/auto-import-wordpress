<?php
/**
 * Settings Page
 *
 * @package AutoImportCore
 */

namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class SettingsPage {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }
    
    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_menu_page(
            __('Auto Import Settings', 'auto-import-core'),
            __('Auto Import', 'auto-import-core'),
            'manage_options',
            'auto-import-settings',
            [$this, 'render_page'],
            'dashicons-admin-generic',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aic_settings', 'aic_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings']
        ]);
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = [];
        
        // Company info
        $sanitized['company_name'] = sanitize_text_field($input['company_name'] ?? '');
        $sanitized['company_phone'] = sanitize_text_field($input['company_phone'] ?? '');
        $sanitized['company_email'] = sanitize_email($input['company_email'] ?? '');
        $sanitized['company_address'] = sanitize_textarea_field($input['company_address'] ?? '');
        
        // Email settings
        $sanitized['email_notifications'] = isset($input['email_notifications']);
        $sanitized['notification_email'] = sanitize_email($input['notification_email'] ?? '');
        $sanitized['email_subject'] = sanitize_text_field($input['email_subject'] ?? '');
        
        // Catalog settings
        $sanitized['cars_per_page'] = absint($input['cars_per_page'] ?? 12);
        $sanitized['default_sort'] = sanitize_text_field($input['default_sort'] ?? 'date_desc');
        $sanitized['show_filters'] = isset($input['show_filters']);
        
        return $sanitized;
    }
    
    /**
     * Render settings page
     */
    public function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $settings = get_option('aic_settings', []);
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('aic_settings'); ?>
                
                <h2 class="nav-tab-wrapper">
                    <a href="#tab-company" class="nav-tab nav-tab-active"><?php _e('Company', 'auto-import-core'); ?></a>
                    <a href="#tab-email" class="nav-tab"><?php _e('Email', 'auto-import-core'); ?></a>
                    <a href="#tab-catalog" class="nav-tab"><?php _e('Catalog', 'auto-import-core'); ?></a>
                </h2>
                
                <!-- Company Tab -->
                <div id="tab-company" class="tab-content">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Company Name', 'auto-import-core'); ?></th>
                            <td>
                                <input type="text" name="aic_settings[company_name]" value="<?php echo esc_attr($settings['company_name'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Phone', 'auto-import-core'); ?></th>
                            <td>
                                <input type="tel" name="aic_settings[company_phone]" value="<?php echo esc_attr($settings['company_phone'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Email', 'auto-import-core'); ?></th>
                            <td>
                                <input type="email" name="aic_settings[company_email]" value="<?php echo esc_attr($settings['company_email'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Address', 'auto-import-core'); ?></th>
                            <td>
                                <textarea name="aic_settings[company_address]" rows="3" class="large-text"><?php echo esc_textarea($settings['company_address'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Email Tab -->
                <div id="tab-email" class="tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Enable Notifications', 'auto-import-core'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="aic_settings[email_notifications]" value="1" <?php checked($settings['email_notifications'] ?? true); ?>>
                                    <?php _e('Send email notification on new lead', 'auto-import-core'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Notification Email', 'auto-import-core'); ?></th>
                            <td>
                                <input type="email" name="aic_settings[notification_email]" value="<?php echo esc_attr($settings['notification_email'] ?? get_option('admin_email')); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Email Subject', 'auto-import-core'); ?></th>
                            <td>
                                <input type="text" name="aic_settings[email_subject]" value="<?php echo esc_attr($settings['email_subject'] ?? __('New Lead: {name}', 'auto-import-core')); ?>" class="regular-text">
                                <p class="description"><?php _e('Use {name} for lead name', 'auto-import-core'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Catalog Tab -->
                <div id="tab-catalog" class="tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Cars Per Page', 'auto-import-core'); ?></th>
                            <td>
                                <input type="number" name="aic_settings[cars_per_page]" value="<?php echo esc_attr($settings['cars_per_page'] ?? 12); ?>" min="1" max="100">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Default Sort', 'auto-import-core'); ?></th>
                            <td>
                                <select name="aic_settings[default_sort]">
                                    <option value="date_desc" <?php selected($settings['default_sort'] ?? 'date_desc', 'date_desc'); ?>><?php _e('Date: Newest First', 'auto-import-core'); ?></option>
                                    <option value="date_asc" <?php selected($settings['default_sort'] ?? '', 'date_asc'); ?>><?php _e('Date: Oldest First', 'auto-import-core'); ?></option>
                                    <option value="price_asc" <?php selected($settings['default_sort'] ?? '', 'price_asc'); ?>><?php _e('Price: Low to High', 'auto-import-core'); ?></option>
                                    <option value="price_desc" <?php selected($settings['default_sort'] ?? '', 'price_desc'); ?>><?php _e('Price: High to Low', 'auto-import-core'); ?></option>
                                    <option value="year_desc" <?php selected($settings['default_sort'] ?? '', 'year_desc'); ?>><?php _e('Year: Newest First', 'auto-import-core'); ?></option>
                                    <option value="year_asc" <?php selected($settings['default_sort'] ?? '', 'year_asc'); ?>><?php _e('Year: Oldest First', 'auto-import-core'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Show Filters', 'auto-import-core'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="aic_settings[show_filters]" value="1" <?php checked($settings['show_filters'] ?? true); ?>>
                                    <?php _e('Show filters sidebar on catalog page', 'auto-import-core'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.tab-content').hide();
                $($(this).attr('href')).show();
            });
        });
        </script>
        <?php
    }
}
