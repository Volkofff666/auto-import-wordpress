<?php
namespace AIC\Admin;

class CarAdmin {
    
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_car', [self::class, 'save_meta_boxes'], 10, 2);
        add_filter('manage_car_posts_columns', [self::class, 'custom_columns']);
        add_action('manage_car_posts_custom_column', [self::class, 'custom_column_content'], 10, 2);
        add_filter('manage_edit-car_sortable_columns', [self::class, 'sortable_columns']);
        add_action('restrict_manage_posts', [self::class, 'add_filters']);
        add_filter('parse_query', [self::class, 'filter_query']);
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'car_basic_info',
            __('Основная информация', 'auto-import-core'),
            [self::class, 'render_basic_info'],
            'car',
            'normal',
            'high'
        );
        
        add_meta_box(
            'car_technical',
            __('Технические характеристики', 'auto-import-core'),
            [self::class, 'render_technical'],
            'car',
            'normal',
            'default'
        );
        
        add_meta_box(
            'car_documents',
            __('Документы и таможня', 'auto-import-core'),
            [self::class, 'render_documents'],
            'car',
            'normal',
            'default'
        );
        
        add_meta_box(
            'car_gallery',
            __('Галерея фотографий', 'auto-import-core'),
            [self::class, 'render_gallery'],
            'car',
            'side',
            'default'
        );
        
        add_meta_box(
            'car_publish',
            __('Публикация', 'auto-import-core'),
            [self::class, 'render_publish'],
            'car',
            'side',
            'default'
        );
    }
    
    public static function render_basic_info($post) {
        wp_nonce_field('car_meta_box', 'car_meta_box_nonce');
        
        $price = get_post_meta($post->ID, 'price_rub', true);
        $year = get_post_meta($post->ID, 'year', true);
        $mileage = get_post_meta($post->ID, 'mileage_km', true);
        $color = get_post_meta($post->ID, 'color', true);
        $vin = get_post_meta($post->ID, 'vin', true);
        $steering = get_post_meta($post->ID, 'steering', true) ?: 'left';
        $owners = get_post_meta($post->ID, 'owners', true);
        $condition = get_post_meta($post->ID, 'condition', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="price_rub"><?php _e('Цена, ₽', 'auto-import-core'); ?> *</label>
                    <input type="number" id="price_rub" name="price_rub" value="<?php echo esc_attr($price); ?>" class="widefat" required>
                </div>
                <div class="aic-field">
                    <label for="year"><?php _e('Год выпуска', 'auto-import-core'); ?> *</label>
                    <input type="number" id="year" name="year" value="<?php echo esc_attr($year); ?>" class="widefat" min="1900" max="<?php echo date('Y') + 1; ?>" required>
                </div>
            </div>
            
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="mileage_km"><?php _e('Пробег, км', 'auto-import-core'); ?></label>
                    <input type="number" id="mileage_km" name="mileage_km" value="<?php echo esc_attr($mileage); ?>" class="widefat">
                </div>
                <div class="aic-field">
                    <label for="color"><?php _e('Цвет', 'auto-import-core'); ?></label>
                    <input type="text" id="color" name="color" value="<?php echo esc_attr($color); ?>" class="widefat">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="vin"><?php _e('VIN номер', 'auto-import-core'); ?></label>
                <input type="text" id="vin" name="vin" value="<?php echo esc_attr($vin); ?>" class="widefat" maxlength="17">
            </div>
            
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="steering"><?php _e('Руль', 'auto-import-core'); ?></label>
                    <select id="steering" name="steering" class="widefat">
                        <option value="left" <?php selected($steering, 'left'); ?>><?php _e('Левый', 'auto-import-core'); ?></option>
                        <option value="right" <?php selected($steering, 'right'); ?>><?php _e('Правый', 'auto-import-core'); ?></option>
                    </select>
                </div>
                <div class="aic-field">
                    <label for="owners"><?php _e('Владельцев', 'auto-import-core'); ?></label>
                    <input type="number" id="owners" name="owners" value="<?php echo esc_attr($owners); ?>" class="widefat" min="0">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="condition"><?php _e('Состояние', 'auto-import-core'); ?></label>
                <textarea id="condition" name="condition" class="widefat" rows="3"><?php echo esc_textarea($condition); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    public static function render_technical($post) {
        $engine_volume = get_post_meta($post->ID, 'engine_volume', true);
        $engine_power = get_post_meta($post->ID, 'engine_power_hp', true);
        $equipment = get_post_meta($post->ID, 'equipment', true) ?: [];
        $description_long = get_post_meta($post->ID, 'description_long', true);
        $video_url = get_post_meta($post->ID, 'video_url', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="engine_volume"><?php _e('Объем двигателя, л', 'auto-import-core'); ?></label>
                    <input type="text" id="engine_volume" name="engine_volume" value="<?php echo esc_attr($engine_volume); ?>" class="widefat" placeholder="2.0">
                </div>
                <div class="aic-field">
                    <label for="engine_power_hp"><?php _e('Мощность, л.с.', 'auto-import-core'); ?></label>
                    <input type="number" id="engine_power_hp" name="engine_power_hp" value="<?php echo esc_attr($engine_power); ?>" class="widefat">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="equipment"><?php _e('Комплектация (каждый пункт с новой строки)', 'auto-import-core'); ?></label>
                <textarea id="equipment" name="equipment" class="widefat" rows="5" placeholder="Кожаный салон&#10;Подогрев сидений&#10;Камера заднего вида"><?php echo esc_textarea(is_array($equipment) ? implode("\n", $equipment) : $equipment); ?></textarea>
            </div>
            
            <div class="aic-field">
                <label for="description_long"><?php _e('Подробное описание', 'auto-import-core'); ?></label>
                <textarea id="description_long" name="description_long" class="widefat" rows="6"><?php echo esc_textarea($description_long); ?></textarea>
            </div>
            
            <div class="aic-field">
                <label for="video_url"><?php _e('Ссылка на видео (YouTube)', 'auto-import-core'); ?></label>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_url($video_url); ?>" class="widefat" placeholder="https://youtube.com/watch?v=...">
            </div>
        </div>
        <?php
    }
    
    public static function render_documents($post) {
        $customs_status = get_post_meta($post->ID, 'customs_status', true) ?: 'not_cleared';
        $documents = get_post_meta($post->ID, 'documents', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field">
                <label for="customs_status"><?php _e('Таможенный статус', 'auto-import-core'); ?></label>
                <select id="customs_status" name="customs_status" class="widefat">
                    <option value="not_cleared" <?php selected($customs_status, 'not_cleared'); ?>><?php _e('Не растаможен', 'auto-import-core'); ?></option>
                    <option value="cleared" <?php selected($customs_status, 'cleared'); ?>><?php _e('Растаможен', 'auto-import-core'); ?></option>
                    <option value="in_process" <?php selected($customs_status, 'in_process'); ?>><?php _e('В процессе', 'auto-import-core'); ?></option>
                </select>
            </div>
            
            <div class="aic-field">
                <label for="documents"><?php _e('Документы', 'auto-import-core'); ?></label>
                <textarea id="documents" name="documents" class="widefat" rows="4" placeholder="ПТС, ЭПТС, ГТД..."><?php echo esc_textarea($documents); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    public static function render_gallery($post) {
        $gallery = get_post_meta($post->ID, 'gallery', true) ?: [];
        ?>
        <div class="aic-gallery-box">
            <div id="aic-gallery-images">
                <?php foreach ($gallery as $image_id): ?>
                    <div class="aic-gallery-item" data-id="<?php echo esc_attr($image_id); ?>">
                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                        <button type="button" class="aic-remove-image">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button" id="aic-add-gallery-images"><?php _e('Добавить фото', 'auto-import-core'); ?></button>
            <input type="hidden" name="gallery" id="gallery" value="<?php echo esc_attr(implode(',', $gallery)); ?>">
        </div>
        <?php
    }
    
    public static function render_publish($post) {
        $publish_to_catalog = get_post_meta($post->ID, 'publish_to_catalog', true);
        $checked = ($publish_to_catalog === '' || $publish_to_catalog) ? 'checked' : '';
        
        ?>
        <div class="aic-publish-box">
            <label>
                <input type="checkbox" name="publish_to_catalog" value="1" <?php echo $checked; ?>>
                <?php _e('Показывать в каталоге', 'auto-import-core'); ?>
            </label>
        </div>
        <?php
    }
    
    public static function save_meta_boxes($post_id, $post) {
        if (!isset($_POST['car_meta_box_nonce']) || !wp_verify_nonce($_POST['car_meta_box_nonce'], 'car_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = [
            'price_rub' => 'absint',
            'year' => 'absint',
            'mileage_km' => 'absint',
            'color' => 'sanitize_text_field',
            'vin' => 'sanitize_text_field',
            'steering' => 'sanitize_text_field',
            'owners' => 'absint',
            'condition' => 'sanitize_textarea_field',
            'engine_volume' => 'sanitize_text_field',
            'engine_power_hp' => 'absint',
            'description_long' => 'sanitize_textarea_field',
            'video_url' => 'esc_url_raw',
            'customs_status' => 'sanitize_text_field',
            'documents' => 'sanitize_textarea_field',
        ];
        
        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, $sanitize($_POST[$field]));
            }
        }
        
        // Equipment
        if (isset($_POST['equipment'])) {
            $equipment = array_filter(array_map('trim', explode("\n", $_POST['equipment'])));
            update_post_meta($post_id, 'equipment', $equipment);
        }
        
        // Gallery
        if (isset($_POST['gallery'])) {
            $gallery = array_filter(array_map('absint', explode(',', $_POST['gallery'])));
            update_post_meta($post_id, 'gallery', $gallery);
        }
        
        // Publish to catalog
        update_post_meta($post_id, 'publish_to_catalog', isset($_POST['publish_to_catalog']) ? 1 : 0);
    }
    
    public static function custom_columns($columns) {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['image'] = __('Фото', 'auto-import-core');
        $new_columns['title'] = $columns['title'];
        $new_columns['car_info'] = __('Марка/Модель', 'auto-import-core');
        $new_columns['year'] = __('Год', 'auto-import-core');
        $new_columns['price'] = __('Цена', 'auto-import-core');
        $new_columns['taxonomy-car_status'] = __('Статус', 'auto-import-core');
        $new_columns['taxonomy-car_location'] = __('Локация', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public static function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'image':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [50, 50]);
                }
                break;
                
            case 'car_info':
                $brands = get_the_terms($post_id, 'car_brand');
                $models = get_the_terms($post_id, 'car_model');
                if ($brands && !is_wp_error($brands)) {
                    echo esc_html($brands[0]->name);
                }
                if ($models && !is_wp_error($models)) {
                    echo ' ' . esc_html($models[0]->name);
                }
                break;
                
            case 'year':
                echo esc_html(get_post_meta($post_id, 'year', true));
                break;
                
            case 'price':
                $price = get_post_meta($post_id, 'price_rub', true);
                echo $price ? number_format($price, 0, '', ' ') . ' ₽' : '—';
                break;
        }
    }
    
    public static function sortable_columns($columns) {
        $columns['year'] = 'year';
        $columns['price'] = 'price_rub';
        return $columns;
    }
    
    public static function add_filters($post_type) {
        if ($post_type !== 'car') {
            return;
        }
        
        $taxonomies = ['car_status', 'car_brand', 'car_location'];
        
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ]);
            
            if ($terms && !is_wp_error($terms)) {
                $current = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                $tax_obj = get_taxonomy($taxonomy);
                
                echo '<select name="' . esc_attr($taxonomy) . '">';
                echo '<option value="">' . esc_html($tax_obj->labels->all_items) . '</option>';
                
                foreach ($terms as $term) {
                    printf(
                        '<option value="%s"%s>%s (%d)</option>',
                        esc_attr($term->slug),
                        selected($current, $term->slug, false),
                        esc_html($term->name),
                        $term->count
                    );
                }
                
                echo '</select>';
            }
        }
    }
    
    public static function filter_query($query) {
        global $pagenow, $typenow;
        
        if ($pagenow === 'edit.php' && $typenow === 'car') {
            $taxonomies = ['car_status', 'car_brand', 'car_location'];
            
            foreach ($taxonomies as $taxonomy) {
                if (isset($_GET[$taxonomy]) && $_GET[$taxonomy] !== '') {
                    $query->query_vars[$taxonomy] = sanitize_text_field($_GET[$taxonomy]);
                }
            }
        }
    }
}