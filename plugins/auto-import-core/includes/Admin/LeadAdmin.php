<?php
namespace AIC\Admin;

class LeadAdmin {
    
    public static function init() {
        add_filter('manage_lead_posts_columns', [self::class, 'add_columns']);
        add_action('manage_lead_posts_custom_column', [self::class, 'render_columns'], 10, 2);
        add_action('restrict_manage_posts', [self::class, 'add_filters']);
        add_filter('parse_query', [self::class, 'filter_query']);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_lead', [self::class, 'save_meta'], 10, 2);
        add_action('transition_post_status', [self::class, 'send_notification'], 10, 3);
    }
    
    public static function add_columns($columns) {
        return [
            'cb' => $columns['cb'],
            'title' => 'ID',
            'name' => __('Имя', 'auto-import-core'),
            'phone' => __('Телефон', 'auto-import-core'),
            'budget' => __('Бюджет', 'auto-import-core'),
            'status' => __('Статус', 'auto-import-core'),
            'source' => __('Источник', 'auto-import-core'),
            'date' => $columns['date'],
        ];
    }
    
    public static function render_columns($column, $post_id) {
        switch ($column) {
            case 'name':
                echo esc_html(get_post_meta($post_id, 'name', true));
                break;
            case 'phone':
                $phone = get_post_meta($post_id, 'phone', true);
                echo '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
                break;
            case 'budget':
                $budget = get_post_meta($post_id, 'budget', true);
                echo $budget ? number_format($budget, 0, '', ' ') . ' ₽' : '—';
                break;
            case 'status':
                $status = get_post_meta($post_id, 'status', true);
                $statuses = [
                    'new' => ['label' => 'Новая', 'color' => '#0066FF'],
                    'in_progress' => ['label' => 'В работе', 'color' => '#FF6B35'],
                    'closed' => ['label' => 'Закрыта', 'color' => '#10B981'],
                ];
                $s = $statuses[$status] ?? $statuses['new'];
                echo '<span style="display:inline-block;padding:2px 8px;background:' . $s['color'] . ';color:#fff;border-radius:3px;font-size:11px;">' . $s['label'] . '</span>';
                break;
            case 'source':
                $source = get_post_meta($post_id, 'source_page', true);
                if ($source) {
                    echo '<a href="' . esc_url($source) . '" target="_blank" title="' . esc_attr($source) . '">' . wp_trim_words($source, 5, '...') . '</a>';
                }
                break;
        }
    }
    
    public static function add_filters($post_type) {
        if ($post_type !== 'lead') {
            return;
        }
        
        $statuses = [
            'new' => 'Новые',
            'in_progress' => 'В работе',
            'closed' => 'Закрытые',
        ];
        
        echo '<select name="lead_status_filter">';
        echo '<option value="">' . __('Все статусы', 'auto-import-core') . '</option>';
        foreach ($statuses as $key => $label) {
            $selected = isset($_GET['lead_status_filter']) && $_GET['lead_status_filter'] == $key ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public static function filter_query($query) {
        global $pagenow;
        
        if ($pagenow !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'lead') {
            return $query;
        }
        
        if (!empty($_GET['lead_status_filter'])) {
            $query->set('meta_key', 'status');
            $query->set('meta_value', sanitize_text_field($_GET['lead_status_filter']));
        }
        
        return $query;
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'lead_info',
            __('Информация о заявке', 'auto-import-core'),
            [self::class, 'render_info_box'],
            'lead',
            'normal',
            'high'
        );
        
        add_meta_box(
            'lead_preferences',
            __('Предпочтения клиента', 'auto-import-core'),
            [self::class, 'render_preferences_box'],
            'lead',
            'normal',
            'default'
        );
        
        add_meta_box(
            'lead_management',
            __('Управление заявкой', 'auto-import-core'),
            [self::class, 'render_management_box'],
            'lead',
            'side',
            'default'
        );
    }
    
    public static function render_info_box($post) {
        wp_nonce_field('lead_meta_save', 'lead_meta_nonce');
        $name = get_post_meta($post->ID, 'name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $email = get_post_meta($post->ID, 'email', true);
        $city = get_post_meta($post->ID, 'city', true);
        $source = get_post_meta($post->ID, 'source_page', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="name">Имя</label></th>
                <td><input type="text" id="name" name="name" value="<?php echo esc_attr($name); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="phone">Телефон</label></th>
                <td><input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="email">Email</label></th>
                <td><input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="city">Город</label></th>
                <td><input type="text" id="city" name="city" value="<?php echo esc_attr($city); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th>Источник</th>
                <td><?php echo $source ? '<a href="' . esc_url($source) . '" target="_blank">' . esc_html($source) . '</a>' : '—'; ?></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_preferences_box($post) {
        $brand = get_post_meta($post->ID, 'preferred_brand', true);
        $model = get_post_meta($post->ID, 'preferred_model', true);
        $year_from = get_post_meta($post->ID, 'year_from', true);
        $year_to = get_post_meta($post->ID, 'year_to', true);
        $budget = get_post_meta($post->ID, 'budget', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="preferred_brand">Марка</label></th>
                <td><input type="text" id="preferred_brand" name="preferred_brand" value="<?php echo esc_attr($brand); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="preferred_model">Модель</label></th>
                <td><input type="text" id="preferred_model" name="preferred_model" value="<?php echo esc_attr($model); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th>Год</th>
                <td>
                    <input type="number" id="year_from" name="year_from" value="<?php echo esc_attr($year_from); ?>" placeholder="От" style="width:80px;">
                    —
                    <input type="number" id="year_to" name="year_to" value="<?php echo esc_attr($year_to); ?>" placeholder="До" style="width:80px;">
                </td>
            </tr>
            <tr>
                <th><label for="budget">Бюджет (₽)</label></th>
                <td><input type="number" id="budget" name="budget" value="<?php echo esc_attr($budget); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }
    
    public static function render_management_box($post) {
        $status = get_post_meta($post->ID, 'status', true);
        $note = get_post_meta($post->ID, 'manager_note', true);
        ?>
        <p>
            <label for="status"><strong>Статус заявки</strong></label><br>
            <select id="status" name="status" style="width:100%;">
                <option value="new" <?php selected($status, 'new'); ?>>Новая</option>
                <option value="in_progress" <?php selected($status, 'in_progress'); ?>>В работе</option>
                <option value="closed" <?php selected($status, 'closed'); ?>>Закрыта</option>
            </select>
        </p>
        <p>
            <label for="manager_note"><strong>Заметка менеджера</strong></label><br>
            <textarea id="manager_note" name="manager_note" rows="4" style="width:100%;"><?php echo esc_textarea($note); ?></textarea>
        </p>
        <?php
    }
    
    public static function save_meta($post_id, $post) {
        if (!isset($_POST['lead_meta_nonce']) || !wp_verify_nonce($_POST['lead_meta_nonce'], 'lead_meta_save')) {
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
    
    public static function send_notification($new_status, $old_status, $post) {
        if ($post->post_type !== 'lead' || $new_status !== 'publish' || $old_status === 'publish') {
            return;
        }
        
        $admin_email = get_option('aic_admin_email', get_option('admin_email'));
        if (!$admin_email) {
            return;
        }
        
        $name = get_post_meta($post->ID, 'name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $budget = get_post_meta($post->ID, 'budget', true);
        
        $subject = 'Новая заявка на сайте';
        $message = "Получена новая заявка:\n\n";
        $message .= "Имя: {$name}\n";
        $message .= "Телефон: {$phone}\n";
        $message .= "Бюджет: " . number_format($budget, 0, '', ' ') . " ₽\n";
        $message .= "\nСсылка: " . admin_url('post.php?post=' . $post->ID . '&action=edit');
        
        wp_mail($admin_email, $subject, $message);
    }
}