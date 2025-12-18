<?php
namespace AIC\Admin;

class LeadAdmin {
    
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_lead', [self::class, 'save_meta_boxes'], 10, 2);
        add_filter('manage_lead_posts_columns', [self::class, 'custom_columns']);
        add_action('manage_lead_posts_custom_column', [self::class, 'custom_column_content'], 10, 2);
        add_filter('post_row_actions', [self::class, 'row_actions'], 10, 2);
        add_action('restrict_manage_posts', [self::class, 'add_filters']);
        add_filter('parse_query', [self::class, 'filter_query']);
        
        // Email notification on new lead
        add_action('transition_post_status', [self::class, 'send_notification'], 10, 3);
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'lead_contact_info',
            __('Контактная информация', 'auto-import-core'),
            [self::class, 'render_contact_info'],
            'lead',
            'normal',
            'high'
        );
        
        add_meta_box(
            'lead_preferences',
            __('Предпочтения клиента', 'auto-import-core'),
            [self::class, 'render_preferences'],
            'lead',
            'normal',
            'default'
        );
        
        add_meta_box(
            'lead_management',
            __('Управление заявкой', 'auto-import-core'),
            [self::class, 'render_management'],
            'lead',
            'side',
            'default'
        );
    }
    
    public static function render_contact_info($post) {
        wp_nonce_field('lead_meta_box', 'lead_meta_box_nonce');
        
        $name = get_post_meta($post->ID, 'name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $email = get_post_meta($post->ID, 'email', true);
        $city = get_post_meta($post->ID, 'city', true);
        $source_page = get_post_meta($post->ID, 'source_page', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field">
                <label for="name"><?php _e('Имя', 'auto-import-core'); ?> *</label>
                <input type="text" id="name" name="name" value="<?php echo esc_attr($name); ?>" class="widefat" required>
            </div>
            
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="phone"><?php _e('Телефон', 'auto-import-core'); ?> *</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" class="widefat" required>
                </div>
                <div class="aic-field">
                    <label for="email"><?php _e('Email', 'auto-import-core'); ?></label>
                    <input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" class="widefat">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="city"><?php _e('Город', 'auto-import-core'); ?></label>
                <input type="text" id="city" name="city" value="<?php echo esc_attr($city); ?>" class="widefat">
            </div>
            
            <div class="aic-field">
                <label for="source_page"><?php _e('Страница источника', 'auto-import-core'); ?></label>
                <input type="url" id="source_page" name="source_page" value="<?php echo esc_url($source_page); ?>" class="widefat" readonly>
            </div>
        </div>
        <?php
    }
    
    public static function render_preferences($post) {
        $preferred_brand = get_post_meta($post->ID, 'preferred_brand', true);
        $preferred_model = get_post_meta($post->ID, 'preferred_model', true);
        $year_from = get_post_meta($post->ID, 'year_from', true);
        $year_to = get_post_meta($post->ID, 'year_to', true);
        $budget = get_post_meta($post->ID, 'budget', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="preferred_brand"><?php _e('Марка', 'auto-import-core'); ?></label>
                    <input type="text" id="preferred_brand" name="preferred_brand" value="<?php echo esc_attr($preferred_brand); ?>" class="widefat">
                </div>
                <div class="aic-field">
                    <label for="preferred_model"><?php _e('Модель', 'auto-import-core'); ?></label>
                    <input type="text" id="preferred_model" name="preferred_model" value="<?php echo esc_attr($preferred_model); ?>" class="widefat">
                </div>
            </div>
            
            <div class="aic-field-row">
                <div class="aic-field">
                    <label for="year_from"><?php _e('Год от', 'auto-import-core'); ?></label>
                    <input type="number" id="year_from" name="year_from" value="<?php echo esc_attr($year_from); ?>" class="widefat" min="1900">
                </div>
                <div class="aic-field">
                    <label for="year_to"><?php _e('Год до', 'auto-import-core'); ?></label>
                    <input type="number" id="year_to" name="year_to" value="<?php echo esc_attr($year_to); ?>" class="widefat" min="1900">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="budget"><?php _e('Бюджет, ₽', 'auto-import-core'); ?></label>
                <input type="number" id="budget" name="budget" value="<?php echo esc_attr($budget); ?>" class="widefat">
            </div>
        </div>
        <?php
    }
    
    public static function render_management($post) {
        $status = get_post_meta($post->ID, 'status', true) ?: 'new';
        $manager_note = get_post_meta($post->ID, 'manager_note', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field">
                <label for="status"><?php _e('Статус', 'auto-import-core'); ?></label>
                <select id="status" name="status" class="widefat">
                    <option value="new" <?php selected($status, 'new'); ?>><?php _e('Новая', 'auto-import-core'); ?></option>
                    <option value="in_progress" <?php selected($status, 'in_progress'); ?>><?php _e('В работе', 'auto-import-core'); ?></option>
                    <option value="closed" <?php selected($status, 'closed'); ?>><?php _e('Закрыта', 'auto-import-core'); ?></option>
                </select>
            </div>
            
            <div class="aic-field">
                <label for="manager_note"><?php _e('Заметка менеджера', 'auto-import-core'); ?></label>
                <textarea id="manager_note" name="manager_note" class="widefat" rows="5"><?php echo esc_textarea($manager_note); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    public static function save_meta_boxes($post_id, $post) {
        if (!isset($_POST['lead_meta_box_nonce']) || !wp_verify_nonce($_POST['lead_meta_box_nonce'], 'lead_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = [
            'name' => 'sanitize_text_field',
            'phone' => 'sanitize_text_field',
            'email' => 'sanitize_email',
            'city' => 'sanitize_text_field',
            'source_page' => 'esc_url_raw',
            'preferred_brand' => 'sanitize_text_field',
            'preferred_model' => 'sanitize_text_field',
            'year_from' => 'absint',
            'year_to' => 'absint',
            'budget' => 'absint',
            'status' => 'sanitize_text_field',
            'manager_note' => 'sanitize_textarea_field',
        ];
        
        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, $sanitize($_POST[$field]));
            }
        }
    }
    
    public static function custom_columns($columns) {
        return [
            'cb' => $columns['cb'],
            'title' => __('ID', 'auto-import-core'),
            'name' => __('Имя', 'auto-import-core'),
            'phone' => __('Телефон', 'auto-import-core'),
            'budget' => __('Бюджет', 'auto-import-core'),
            'status' => __('Статус', 'auto-import-core'),
            'source' => __('Источник', 'auto-import-core'),
            'date' => __('Дата', 'auto-import-core'),
        ];
    }
    
    public static function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'name':
                echo esc_html(get_post_meta($post_id, 'name', true));
                break;
                
            case 'phone':
                echo esc_html(get_post_meta($post_id, 'phone', true));
                break;
                
            case 'budget':
                $budget = get_post_meta($post_id, 'budget', true);
                echo $budget ? number_format($budget, 0, '', ' ') . ' ₽' : '—';
                break;
                
            case 'status':
                $status = get_post_meta($post_id, 'status', true);
                $statuses = [
                    'new' => '<span class="aic-status aic-status--new">Новая</span>',
                    'in_progress' => '<span class="aic-status aic-status--progress">В работе</span>',
                    'closed' => '<span class="aic-status aic-status--closed">Закрыта</span>',
                ];
                echo $statuses[$status] ?? '—';
                break;
                
            case 'source':
                $url = get_post_meta($post_id, 'source_page', true);
                if ($url) {
                    $path = parse_url($url, PHP_URL_PATH);
                    echo '<a href="' . esc_url($url) . '" target="_blank">' . esc_html($path ?: '/') . '</a>';
                } else {
                    echo '—';
                }
                break;
        }
    }
    
    public static function row_actions($actions, $post) {
        if ($post->post_type === 'lead') {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }
    
    public static function add_filters($post_type) {
        if ($post_type !== 'lead') {
            return;
        }
        
        $current = isset($_GET['lead_status']) ? $_GET['lead_status'] : '';
        $statuses = [
            'new' => __('Новые', 'auto-import-core'),
            'in_progress' => __('В работе', 'auto-import-core'),
            'closed' => __('Закрытые', 'auto-import-core'),
        ];
        
        echo '<select name="lead_status">';
        echo '<option value="">' . __('Все статусы', 'auto-import-core') . '</option>';
        
        foreach ($statuses as $value => $label) {
            printf(
                '<option value="%s"%s>%s</option>',
                esc_attr($value),
                selected($current, $value, false),
                esc_html($label)
            );
        }
        
        echo '</select>';
    }
    
    public static function filter_query($query) {
        global $pagenow, $typenow;
        
        if ($pagenow === 'edit.php' && $typenow === 'lead' && isset($_GET['lead_status']) && $_GET['lead_status'] !== '') {
            $query->set('meta_key', 'status');
            $query->set('meta_value', sanitize_text_field($_GET['lead_status']));
        }
    }
    
    public static function send_notification($new_status, $old_status, $post) {
        if ($post->post_type !== 'lead') {
            return;
        }
        
        if ($new_status !== 'publish' || $old_status === 'publish') {
            return;
        }
        
        $admin_email = get_option('aic_notification_email') ?: get_option('admin_email');
        $name = get_post_meta($post->ID, 'name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $budget = get_post_meta($post->ID, 'budget', true);
        
        $subject = sprintf(__('Новая заявка от %s', 'auto-import-core'), $name);
        $message = sprintf(
            __('Получена новая заявка:\n\nИмя: %s\nТелефон: %s\nБюджет: %s ₽\n\nСсылка: %s', 'auto-import-core'),
            $name,
            $phone,
            number_format($budget, 0, '', ' '),
            admin_url('post.php?post=' . $post->ID . '&action=edit')
        );
        
        wp_mail($admin_email, $subject, $message);
    }
}