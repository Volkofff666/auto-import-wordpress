<?php
namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class CarAdmin {
    
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_car', [self::class, 'save_meta'], 10, 2);
        add_filter('manage_car_posts_columns', [self::class, 'set_columns']);
        add_action('manage_car_posts_custom_column', [self::class, 'render_column'], 10, 2);
        add_filter('manage_edit-car_sortable_columns', [self::class, 'sortable_columns']);
        add_action('restrict_manage_posts', [self::class, 'add_filters']);
        add_filter('parse_query', [self::class, 'filter_query']);
    }
    
    public static function add_meta_boxes() {
        // Main info
        add_meta_box(
            'car_main_info',
            __('Основная информация', 'auto-import-core'),
            [self::class, 'render_main_info'],
            'car',
            'normal',
            'high'
        );
        
        // Technical specs
        add_meta_box(
            'car_specs',
            __('Технические характеристики', 'auto-import-core'),
            [self::class, 'render_specs'],
            'car',
            'normal',
            'default'
        );
        
        // Documents
        add_meta_box(
            'car_documents',
            __('Документы и таможня', 'auto-import-core'),
            [self::class, 'render_documents'],
            'car',
            'normal',
            'default'
        );
        
        // Equipment
        add_meta_box(
            'car_equipment',
            __('Комплектация', 'auto-import-core'),
            [self::class, 'render_equipment'],
            'car',
            'normal',
            'default'
        );
        
        // Gallery
        add_meta_box(
            'car_gallery',
            __('Галерея фотографий', 'auto-import-core'),
            [self::class, 'render_gallery'],
            'car',
            'side',
            'default'
        );
        
        // Publication
        add_meta_box(
            'car_publication',
            __('Публикация', 'auto-import-core'),
            [self::class, 'render_publication'],
            'car',
            'side',
            'default'
        );
    }
    
    public static function render_main_info($post) {
        wp_nonce_field('car_meta_nonce', 'car_meta_nonce');
        
        $price = get_post_meta($post->ID, 'price_rub', true);
        $year = get_post_meta($post->ID, 'year', true);
        $mileage = get_post_meta($post->ID, 'mileage_km', true);
        $vin = get_post_meta($post->ID, 'vin', true);
        $color = get_post_meta($post->ID, 'color', true);
        $steering = get_post_meta($post->ID, 'steering', true);
        $owners = get_post_meta($post->ID, 'owners', true);
        $condition = get_post_meta($post->ID, 'condition', true);
        ?>
        <div class="aic-metabox">
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Цена <span class="required">*</span>
                        <input type="number" name="price_rub" value="<?php echo esc_attr($price); ?>" class="aic-metabox__input" placeholder="2500000" step="1000" required>
                        <span class="aic-metabox__hint">В рублях</span>
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Год выпуска <span class="required">*</span>
                        <input type="number" name="year" value="<?php echo esc_attr($year); ?>" class="aic-metabox__input" placeholder="2020" min="1950" max="<?php echo date('Y') + 1; ?>" required>
                    </label>
                </div>
            </div>
            
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Пробег <span class="required">*</span>
                        <input type="number" name="mileage_km" value="<?php echo esc_attr($mileage); ?>" class="aic-metabox__input" placeholder="35000" step="1000" required>
                        <span class="aic-metabox__hint">В километрах</span>
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        VIN-код
                        <input type="text" name="vin" value="<?php echo esc_attr($vin); ?>" class="aic-metabox__input" placeholder="1HGBH41JXMN109186" maxlength="17">
                        <span class="aic-metabox__hint">17 символов</span>
                    </label>
                </div>
            </div>
            
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Цвет
                        <input type="text" name="color" value="<?php echo esc_attr($color); ?>" class="aic-metabox__input" placeholder="Черный">
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Руль
                        <select name="steering" class="aic-metabox__select">
                            <option value="">Не указано</option>
                            <option value="left" <?php selected($steering, 'left'); ?>>Левый</option>
                            <option value="right" <?php selected($steering, 'right'); ?>>Правый</option>
                        </select>
                    </label>
                </div>
            </div>
            
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Количество владельцев
                        <input type="number" name="owners" value="<?php echo esc_attr($owners); ?>" class="aic-metabox__input" placeholder="1" min="0">
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Состояние
                        <input type="text" name="condition" value="<?php echo esc_attr($condition); ?>" class="aic-metabox__input" placeholder="Отличное">
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
    
    public static function render_specs($post) {
        $engine_volume = get_post_meta($post->ID, 'engine_volume', true);
        $engine_power = get_post_meta($post->ID, 'engine_power_hp', true);
        $video_url = get_post_meta($post->ID, 'video_url', true);
        ?>
        <div class="aic-metabox">
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Объем двигателя
                        <input type="text" name="engine_volume" value="<?php echo esc_attr($engine_volume); ?>" class="aic-metabox__input" placeholder="2.0">
                        <span class="aic-metabox__hint">В литрах (например: 2.0, 3.5)</span>
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Мощность двигателя
                        <input type="number" name="engine_power_hp" value="<?php echo esc_attr($engine_power); ?>" class="aic-metabox__input" placeholder="150">
                        <span class="aic-metabox__hint">В лошадиных силах</span>
                    </label>
                </div>
            </div>
            
            <div class="aic-metabox__row">
                <div class="aic-metabox__col">
                    <label class="aic-metabox__label">
                        Ссылка на видео (YouTube)
                        <input type="url" name="video_url" value="<?php echo esc_attr($video_url); ?>" class="aic-metabox__input" placeholder="https://www.youtube.com/watch?v=...">
                        <span class="aic-metabox__hint">Необязательно</span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
    
    public static function render_documents($post) {
        $customs_status = get_post_meta($post->ID, 'customs_status', true);
        $documents = get_post_meta($post->ID, 'documents', true);
        ?>
        <div class="aic-metabox">
            <div class="aic-metabox__row">
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Таможенный статус
                        <select name="customs_status" class="aic-metabox__select">
                            <option value="">Не указано</option>
                            <option value="cleared" <?php selected($customs_status, 'cleared'); ?>>Растаможен</option>
                            <option value="not_cleared" <?php selected($customs_status, 'not_cleared'); ?>>Не растаможен</option>
                            <option value="in_process" <?php selected($customs_status, 'in_process'); ?>>В процессе</option>
                        </select>
                    </label>
                </div>
                <div class="aic-metabox__col aic-metabox__col--half">
                    <label class="aic-metabox__label">
                        Документы
                        <input type="text" name="documents" value="<?php echo esc_attr($documents); ?>" class="aic-metabox__input" placeholder="ПТС, ЭПТС, ГТД">
                        <span class="aic-metabox__hint">Через запятую</span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
    
    public static function render_equipment($post) {
        $equipment = get_post_meta($post->ID, 'equipment', true);
        $equipment_text = is_array($equipment) ? implode("\n", $equipment) : $equipment;
        ?>
        <div class="aic-metabox">
            <label class="aic-metabox__label">
                Список опций
                <textarea name="equipment" class="aic-metabox__textarea" rows="10" placeholder="Кожаный салон\nПанорамная крыша\nСистема кругового обзора\nАдаптивный круиз-контроль\n..."><?php echo esc_textarea($equipment_text); ?></textarea>
                <span class="aic-metabox__hint">Каждая опция с новой строки</span>
            </label>
        </div>
        <?php
    }
    
    public static function render_gallery($post) {
        $gallery = get_post_meta($post->ID, 'gallery', true);
        $gallery_ids = !empty($gallery) ? explode(',', $gallery) : [];
        ?>
        <div class="aic-gallery">
            <div class="aic-gallery__items" id="car-gallery-items">
                <?php foreach ($gallery_ids as $image_id): ?>
                    <?php if ($image = wp_get_attachment_image_src($image_id, 'thumbnail')): ?>
                        <div class="aic-gallery__item" data-id="<?php echo esc_attr($image_id); ?>">
                            <img src="<?php echo esc_url($image[0]); ?>" alt="">
                            <button type="button" class="aic-gallery__remove" title="Удалить">
                                <span class="dashicons dashicons-no"></span>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="gallery" id="car-gallery-input" value="<?php echo esc_attr($gallery); ?>">
            <button type="button" class="button button-primary button-large aic-gallery__add" id="car-gallery-add">
                <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span> Добавить фото
            </button>
            <p class="description" style="margin-top: 10px;">Рекомендуется загружать минимум 5-10 фотографий</p>
        </div>
        <?php
    }
    
    public static function render_publication($post) {
        $publish_to_catalog = get_post_meta($post->ID, 'publish_to_catalog', true);
        ?>
        <div class="aic-metabox aic-metabox--compact">
            <label class="aic-metabox__checkbox">
                <input type="checkbox" name="publish_to_catalog" value="1" <?php checked($publish_to_catalog, '1'); ?>>
                <span>Показывать в каталоге</span>
            </label>
            <p class="description">Если не отмечено, автомобиль будет скрыт из каталога на сайте</p>
        </div>
        <?php
    }
    
    public static function save_meta($post_id, $post) {
        if (!isset($_POST['car_meta_nonce']) || !wp_verify_nonce($_POST['car_meta_nonce'], 'car_meta_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Main info
        $fields = [
            'price_rub' => 'intval',
            'year' => 'intval',
            'mileage_km' => 'intval',
            'vin' => 'sanitize_text_field',
            'color' => 'sanitize_text_field',
            'steering' => 'sanitize_text_field',
            'owners' => 'intval',
            'condition' => 'sanitize_text_field',
            'engine_volume' => 'sanitize_text_field',
            'engine_power_hp' => 'intval',
            'video_url' => 'esc_url_raw',
            'customs_status' => 'sanitize_text_field',
            'documents' => 'sanitize_text_field',
            'gallery' => 'sanitize_text_field',
        ];
        
        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, $sanitize($_POST[$field]));
            }
        }
        
        // Equipment
        if (isset($_POST['equipment'])) {
            $equipment = sanitize_textarea_field($_POST['equipment']);
            $equipment_array = array_filter(array_map('trim', explode("\n", $equipment)));
            update_post_meta($post_id, 'equipment', $equipment_array);
        }
        
        // Publication
        update_post_meta($post_id, 'publish_to_catalog', isset($_POST['publish_to_catalog']) ? '1' : '0');
    }
    
    public static function set_columns($columns) {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['image'] = __('Фото', 'auto-import-core');
        $new_columns['title'] = __('Название', 'auto-import-core');
        $new_columns['brand'] = __('Марка', 'auto-import-core');
        $new_columns['year'] = __('Год', 'auto-import-core');
        $new_columns['price'] = __('Цена', 'auto-import-core');
        $new_columns['mileage'] = __('Пробег', 'auto-import-core');
        $new_columns['status'] = __('Статус', 'auto-import-core');
        $new_columns['catalog'] = __('В каталоге', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }
    
    public static function render_column($column, $post_id) {
        switch ($column) {
            case 'image':
                if (has_post_thumbnail($post_id)) {
                    echo '<img src="' . get_the_post_thumbnail_url($post_id, 'thumbnail') . '" style="width: 60px; height: auto; border-radius: 4px;">';
                } else {
                    echo '<span class="dashicons dashicons-format-image" style="font-size: 40px; color: #ddd;"></span>';
                }
                break;
                
            case 'brand':
                $terms = get_the_terms($post_id, 'car_brand');
                if ($terms && !is_wp_error($terms)) {
                    echo esc_html($terms[0]->name);
                }
                break;
                
            case 'year':
                $year = get_post_meta($post_id, 'year', true);
                echo $year ? esc_html($year) : '—';
                break;
                
            case 'price':
                $price = get_post_meta($post_id, 'price_rub', true);
                echo $price ? number_format($price, 0, '', ' ') . ' ₽' : '—';
                break;
                
            case 'mileage':
                $mileage = get_post_meta($post_id, 'mileage_km', true);
                echo $mileage ? number_format($mileage, 0, '', ' ') . ' км' : '—';
                break;
                
            case 'status':
                $terms = get_the_terms($post_id, 'car_status');
                if ($terms && !is_wp_error($terms)) {
                    $colors = [
                        'available' => '#10B981',
                        'in_transit' => '#F59E0B',
                        'on_order' => '#3B82F6',
                        'sold' => '#EF4444',
                    ];
                    $slug = $terms[0]->slug;
                    $color = isset($colors[$slug]) ? $colors[$slug] : '#6B7280';
                    echo '<span style="display: inline-block; padding: 4px 8px; background: ' . $color . '; color: white; border-radius: 4px; font-size: 12px;">' . esc_html($terms[0]->name) . '</span>';
                }
                break;
                
            case 'catalog':
                $publish = get_post_meta($post_id, 'publish_to_catalog', true);
                if ($publish === '1') {
                    echo '<span class="dashicons dashicons-yes" style="color: #10B981; font-size: 20px;"></span>';
                } else {
                    echo '<span class="dashicons dashicons-no" style="color: #EF4444; font-size: 20px;"></span>';
                }
                break;
        }
    }
    
    public static function sortable_columns($columns) {
        $columns['year'] = 'year';
        $columns['price'] = 'price';
        $columns['mileage'] = 'mileage';
        return $columns;
    }
    
    public static function add_filters($post_type) {
        if ($post_type !== 'car') {
            return;
        }
        
        // Status filter
        $selected_status = isset($_GET['car_status']) ? $_GET['car_status'] : '';
        $terms = get_terms('car_status', ['hide_empty' => false]);
        
        if ($terms && !is_wp_error($terms)) {
            echo '<select name="car_status">';
            echo '<option value="">Все статусы</option>';
            foreach ($terms as $term) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    $term->slug,
                    selected($selected_status, $term->slug, false),
                    $term->name
                );
            }
            echo '</select>';
        }
        
        // Brand filter
        $selected_brand = isset($_GET['car_brand']) ? $_GET['car_brand'] : '';
        $brands = get_terms('car_brand', ['hide_empty' => false]);
        
        if ($brands && !is_wp_error($brands)) {
            echo '<select name="car_brand">';
            echo '<option value="">Все марки</option>';
            foreach ($brands as $brand) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    $brand->slug,
                    selected($selected_brand, $brand->slug, false),
                    $brand->name
                );
            }
            echo '</select>';
        }
    }
    
    public static function filter_query($query) {
        global $pagenow;
        
        if ($pagenow === 'edit.php' && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'car') {
            if (isset($_GET['car_status']) && $_GET['car_status'] !== '') {
                $query->query_vars['tax_query'] = [
                    [
                        'taxonomy' => 'car_status',
                        'field' => 'slug',
                        'terms' => $_GET['car_status']
                    ]
                ];
            }
            
            if (isset($_GET['car_brand']) && $_GET['car_brand'] !== '') {
                $query->query_vars['tax_query'] = [
                    [
                        'taxonomy' => 'car_brand',
                        'field' => 'slug',
                        'terms' => $_GET['car_brand']
                    ]
                ];
            }
        }
    }
}