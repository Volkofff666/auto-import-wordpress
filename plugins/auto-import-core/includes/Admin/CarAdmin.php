<?php
namespace AIC\Admin;

class CarAdmin {
    
    public static function init() {
        add_filter('manage_car_posts_columns', [self::class, 'add_columns']);
        add_action('manage_car_posts_custom_column', [self::class, 'render_columns'], 10, 2);
        add_filter('manage_edit-car_sortable_columns', [self::class, 'sortable_columns']);
        add_action('restrict_manage_posts', [self::class, 'add_filters']);
        add_filter('parse_query', [self::class, 'filter_query']);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_car', [self::class, 'save_meta'], 10, 2);
    }
    
    public static function add_columns($columns) {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['image'] = __('Фото', 'auto-import-core');
        $new_columns['title'] = $columns['title'];
        $new_columns['brand_model'] = __('Марка/Модель', 'auto-import-core');
        $new_columns['year'] = __('Год', 'auto-import-core');
        $new_columns['price'] = __('Цена', 'auto-import-core');
        $new_columns['car_status'] = __('Статус', 'auto-import-core');
        $new_columns['car_location'] = __('Локация', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }
    
    public static function render_columns($column, $post_id) {
        switch ($column) {
            case 'image':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [50, 50]);
                } else {
                    echo '<span class="dashicons dashicons-car" style="font-size:50px;color:#ccc;"></span>';
                }
                break;
                
            case 'brand_model':
                $brands = get_the_terms($post_id, 'car_brand');
                $models = get_the_terms($post_id, 'car_model');
                $brand = $brands ? $brands[0]->name : '';
                $model = $models ? $models[0]->name : '';
                echo esc_html($brand . ' ' . $model);
                break;
                
            case 'year':
                echo esc_html(get_post_meta($post_id, 'year', true));
                break;
                
            case 'price':
                $price = get_post_meta($post_id, 'price_rub', true);
                echo $price ? number_format($price, 0, '', ' ') . ' ₽' : '—';
                break;
                
            case 'car_status':
                $statuses = get_the_terms($post_id, 'car_status');
                if ($statuses) {
                    $colors = [
                        'в пути' => '#0066FF',
                        'на стоянке' => '#10B981',
                        'под заказ' => '#FF6B35',
                        'продан' => '#6B7280'
                    ];
                    foreach ($statuses as $status) {
                        $color = $colors[mb_strtolower($status->name)] ?? '#6B7280';
                        echo '<span style="display:inline-block;padding:2px 8px;background:' . $color . ';color:#fff;border-radius:3px;font-size:11px;">' . esc_html($status->name) . '</span>';
                    }
                }
                break;
                
            case 'car_location':
                $locations = get_the_terms($post_id, 'car_location');
                echo $locations ? esc_html($locations[0]->name) : '—';
                break;
        }
    }
    
    public static function sortable_columns($columns) {
        $columns['year'] = 'year';
        $columns['price'] = 'price';
        return $columns;
    }
    
    public static function add_filters($post_type) {
        if ($post_type !== 'car') {
            return;
        }
        
        // Filter by status
        $statuses = get_terms(['taxonomy' => 'car_status', 'hide_empty' => false]);
        if ($statuses) {
            echo '<select name="car_status_filter">';
            echo '<option value="">' . __('Все статусы', 'auto-import-core') . '</option>';
            foreach ($statuses as $status) {
                $selected = isset($_GET['car_status_filter']) && $_GET['car_status_filter'] == $status->slug ? 'selected' : '';
                echo '<option value="' . esc_attr($status->slug) . '" ' . $selected . '>' . esc_html($status->name) . '</option>';
            }
            echo '</select>';
        }
        
        // Filter by brand
        $brands = get_terms(['taxonomy' => 'car_brand', 'hide_empty' => false]);
        if ($brands) {
            echo '<select name="car_brand_filter">';
            echo '<option value="">' . __('Все марки', 'auto-import-core') . '</option>';
            foreach ($brands as $brand) {
                $selected = isset($_GET['car_brand_filter']) && $_GET['car_brand_filter'] == $brand->slug ? 'selected' : '';
                echo '<option value="' . esc_attr($brand->slug) . '" ' . $selected . '>' . esc_html($brand->name) . '</option>';
            }
            echo '</select>';
        }
    }
    
    public static function filter_query($query) {
        global $pagenow;
        
        if ($pagenow !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'car') {
            return $query;
        }
        
        $tax_query = [];
        
        if (!empty($_GET['car_status_filter'])) {
            $tax_query[] = [
                'taxonomy' => 'car_status',
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET['car_status_filter'])
            ];
        }
        
        if (!empty($_GET['car_brand_filter'])) {
            $tax_query[] = [
                'taxonomy' => 'car_brand',
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET['car_brand_filter'])
            ];
        }
        
        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }
        
        return $query;
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'car_basic_info',
            __('Основная информация', 'auto-import-core'),
            [self::class, 'render_basic_info_box'],
            'car',
            'normal',
            'high'
        );
        
        add_meta_box(
            'car_tech_specs',
            __('Технические характеристики', 'auto-import-core'),
            [self::class, 'render_tech_specs_box'],
            'car',
            'normal',
            'default'
        );
        
        add_meta_box(
            'car_docs',
            __('Документы и таможня', 'auto-import-core'),
            [self::class, 'render_docs_box'],
            'car',
            'normal',
            'default'
        );
        
        add_meta_box(
            'car_gallery',
            __('Галерея фотографий', 'auto-import-core'),
            [self::class, 'render_gallery_box'],
            'car',
            'normal',
            'default'
        );
        
        add_meta_box(
            'car_publish',
            __('Публикация', 'auto-import-core'),
            [self::class, 'render_publish_box'],
            'car',
            'side',
            'default'
        );
    }
    
    public static function render_basic_info_box($post) {
        wp_nonce_field('car_meta_save', 'car_meta_nonce');
        $price = get_post_meta($post->ID, 'price_rub', true);
        $year = get_post_meta($post->ID, 'year', true);
        $mileage = get_post_meta($post->ID, 'mileage_km', true);
        $vin = get_post_meta($post->ID, 'vin', true);
        $color = get_post_meta($post->ID, 'color', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="price_rub">Цена (₽)</label></th>
                <td><input type="number" id="price_rub" name="price_rub" value="<?php echo esc_attr($price); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="year">Год выпуска</label></th>
                <td><input type="number" id="year" name="year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y') + 1; ?>" required></td>
            </tr>
            <tr>
                <th><label for="mileage_km">Пробег (км)</label></th>
                <td><input type="number" id="mileage_km" name="mileage_km" value="<?php echo esc_attr($mileage); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="vin">VIN</label></th>
                <td><input type="text" id="vin" name="vin" value="<?php echo esc_attr($vin); ?>" class="regular-text" maxlength="17"></td>
            </tr>
            <tr>
                <th><label for="color">Цвет</label></th>
                <td><input type="text" id="color" name="color" value="<?php echo esc_attr($color); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_tech_specs_box($post) {
        $engine_volume = get_post_meta($post->ID, 'engine_volume', true);
        $engine_power = get_post_meta($post->ID, 'engine_power_hp', true);
        $steering = get_post_meta($post->ID, 'steering', true);
        $owners = get_post_meta($post->ID, 'owners', true);
        $condition = get_post_meta($post->ID, 'condition', true);
        $equipment = get_post_meta($post->ID, 'equipment', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="engine_volume">Объем двигателя</label></th>
                <td><input type="text" id="engine_volume" name="engine_volume" value="<?php echo esc_attr($engine_volume); ?>" placeholder="2.0"></td>
            </tr>
            <tr>
                <th><label for="engine_power_hp">Мощность (л.с.)</label></th>
                <td><input type="number" id="engine_power_hp" name="engine_power_hp" value="<?php echo esc_attr($engine_power); ?>"></td>
            </tr>
            <tr>
                <th><label for="steering">Руль</label></th>
                <td>
                    <select id="steering" name="steering">
                        <option value="left" <?php selected($steering, 'left'); ?>>Левый</option>
                        <option value="right" <?php selected($steering, 'right'); ?>>Правый</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="owners">Количество владельцев</label></th>
                <td><input type="number" id="owners" name="owners" value="<?php echo esc_attr($owners); ?>" min="0"></td>
            </tr>
            <tr>
                <th><label for="condition">Состояние</label></th>
                <td><input type="text" id="condition" name="condition" value="<?php echo esc_attr($condition); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="equipment">Комплектация</label></th>
                <td><textarea id="equipment" name="equipment" rows="4" class="large-text"><?php echo esc_textarea(is_array($equipment) ? implode("\n", $equipment) : $equipment); ?></textarea>
                <p class="description">Каждая опция с новой строки</p></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_docs_box($post) {
        $customs_status = get_post_meta($post->ID, 'customs_status', true);
        $documents = get_post_meta($post->ID, 'documents', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="customs_status">Таможня</label></th>
                <td>
                    <select id="customs_status" name="customs_status">
                        <option value="not_cleared" <?php selected($customs_status, 'not_cleared'); ?>>Не растаможен</option>
                        <option value="cleared" <?php selected($customs_status, 'cleared'); ?>>Растаможен</option>
                        <option value="in_process" <?php selected($customs_status, 'in_process'); ?>>В процессе</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="documents">Документы</label></th>
                <td><input type="text" id="documents" name="documents" value="<?php echo esc_attr($documents); ?>" class="regular-text" placeholder="ПТС, ЭПТС, ГТД"></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_gallery_box($post) {
        $gallery = get_post_meta($post->ID, 'gallery', true);
        $video_url = get_post_meta($post->ID, 'video_url', true);
        ?>
        <div id="car-gallery-container">
            <div id="car-gallery-images" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;">
                <?php
                if (is_array($gallery) && !empty($gallery)) {
                    foreach ($gallery as $image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                        if ($image_url) {
                            echo '<div class="gallery-item" style="position:relative;">';
                            echo '<img src="' . esc_url($image_url) . '" style="width:100px;height:100px;object-fit:cover;">';
                            echo '<button type="button" class="remove-gallery-image" data-id="' . $image_id . '" style="position:absolute;top:0;right:0;background:#f00;color:#fff;border:none;cursor:pointer;width:20px;height:20px;">&times;</button>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
            <input type="hidden" id="gallery" name="gallery" value="<?php echo esc_attr(is_array($gallery) ? implode(',', $gallery) : ''); ?>">
            <button type="button" class="button" id="add-gallery-images">Добавить фотографии</button>
        </div>
        <table class="form-table" style="margin-top:15px;">
            <tr>
                <th><label for="video_url">Видео URL</label></th>
                <td><input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" class="regular-text" placeholder="https://youtube.com/..."></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_publish_box($post) {
        $publish_to_catalog = get_post_meta($post->ID, 'publish_to_catalog', true);
        $publish_to_catalog = $publish_to_catalog === '' ? true : (bool) $publish_to_catalog;
        ?>
        <label>
            <input type="checkbox" name="publish_to_catalog" value="1" <?php checked($publish_to_catalog, true); ?>>
            Показывать в каталоге
        </label>
        <?php
    }
    
    public static function save_meta($post_id, $post) {
        if (!isset($_POST['car_meta_nonce']) || !wp_verify_nonce($_POST['car_meta_nonce'], 'car_meta_save')) {
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
            'vin' => 'sanitize_text_field',
            'engine_volume' => 'sanitize_text_field',
            'engine_power_hp' => 'absint',
            'color' => 'sanitize_text_field',
            'steering' => 'sanitize_text_field',
            'owners' => 'absint',
            'condition' => 'sanitize_text_field',
            'customs_status' => 'sanitize_text_field',
            'documents' => 'sanitize_text_field',
            'video_url' => 'esc_url_raw',
        ];
        
        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, $sanitize($_POST[$field]));
            }
        }
        
        // Equipment array
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
}