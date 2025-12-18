<?php
namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class CarAdmin {
    
    public static function init() {
        // Only initialize if we're in admin
        if (!is_admin()) {
            return;
        }
        
        add_filter('manage_car_posts_columns', [self::class, 'add_columns']);
        add_action('manage_car_posts_custom_column', [self::class, 'populate_columns'], 10, 2);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_car', [self::class, 'save_meta_boxes'], 10, 2);
    }
    
    public static function add_columns($columns) {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumbnail'] = __('Photo', 'auto-import-core');
        $new_columns['title'] = $columns['title'];
        $new_columns['price'] = __('Price', 'auto-import-core');
        $new_columns['year'] = __('Year', 'auto-import-core');
        $new_columns['status'] = __('Status', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public static function populate_columns($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [50, 50]);
                }
                break;
                
            case 'price':
                $price = get_post_meta($post_id, 'price_rub', true);
                if ($price) {
                    echo number_format($price, 0, '', ' ') . ' ₽';
                }
                break;
                
            case 'year':
                $year = get_post_meta($post_id, 'year', true);
                if ($year) {
                    echo esc_html($year);
                }
                break;
                
            case 'status':
                $terms = get_the_terms($post_id, 'car_status');
                if ($terms && !is_wp_error($terms)) {
                    echo esc_html($terms[0]->name);
                }
                break;
        }
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'car_details',
            __('Car Details', 'auto-import-core'),
            [self::class, 'render_details_meta_box'],
            'car',
            'normal',
            'high'
        );
        
        add_meta_box(
            'car_gallery',
            __('Photo Gallery', 'auto-import-core'),
            [self::class, 'render_gallery_meta_box'],
            'car',
            'side',
            'default'
        );
    }
    
    public static function render_details_meta_box($post) {
        wp_nonce_field('car_details_nonce', 'car_details_nonce');
        
        $price = get_post_meta($post->ID, 'price_rub', true);
        $year = get_post_meta($post->ID, 'year', true);
        $mileage = get_post_meta($post->ID, 'mileage_km', true);
        $vin = get_post_meta($post->ID, 'vin', true);
        $publish_to_catalog = get_post_meta($post->ID, 'publish_to_catalog', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="price_rub"><?php _e('Price, ₽', 'auto-import-core'); ?></label></th>
                <td><input type="number" id="price_rub" name="price_rub" value="<?php echo esc_attr($price); ?>" class="regular-text" step="1000"></td>
            </tr>
            <tr>
                <th><label for="year"><?php _e('Year', 'auto-import-core'); ?></label></th>
                <td><input type="number" id="year" name="year" value="<?php echo esc_attr($year); ?>" class="regular-text" min="1900" max="<?php echo date('Y') + 1; ?>"></td>
            </tr>
            <tr>
                <th><label for="mileage_km"><?php _e('Mileage, km', 'auto-import-core'); ?></label></th>
                <td><input type="number" id="mileage_km" name="mileage_km" value="<?php echo esc_attr($mileage); ?>" class="regular-text" step="1000"></td>
            </tr>
            <tr>
                <th><label for="vin"><?php _e('VIN', 'auto-import-core'); ?></label></th>
                <td><input type="text" id="vin" name="vin" value="<?php echo esc_attr($vin); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="publish_to_catalog"><?php _e('Show in Catalog', 'auto-import-core'); ?></label></th>
                <td><input type="checkbox" id="publish_to_catalog" name="publish_to_catalog" value="1" <?php checked($publish_to_catalog, '1'); ?>></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_gallery_meta_box($post) {
        $gallery = get_post_meta($post->ID, 'gallery', true);
        ?>
        <p><?php _e('Use Featured Image for main photo. Upload additional photos below.', 'auto-import-core'); ?></p>
        <p><em><?php _e('Gallery functionality requires additional JavaScript. For now, use Featured Image.', 'auto-import-core'); ?></em></p>
        <?php
    }
    
    public static function save_meta_boxes($post_id, $post) {
        // Check nonce
        if (!isset($_POST['car_details_nonce']) || !wp_verify_nonce($_POST['car_details_nonce'], 'car_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save fields
        $fields = ['price_rub', 'year', 'mileage_km', 'vin'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Save checkbox
        $publish = isset($_POST['publish_to_catalog']) ? '1' : '0';
        update_post_meta($post_id, 'publish_to_catalog', $publish);
    }
}