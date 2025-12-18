<?php
namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class LeadAdmin {
    
    public static function init() {
        if (!is_admin()) {
            return;
        }
        
        add_filter('manage_lead_posts_columns', [self::class, 'add_columns']);
        add_action('manage_lead_posts_custom_column', [self::class, 'populate_columns'], 10, 2);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post_lead', [self::class, 'save_meta_boxes'], 10, 2);
    }
    
    public static function add_columns($columns) {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['phone'] = __('Phone', 'auto-import-core');
        $new_columns['budget'] = __('Budget', 'auto-import-core');
        $new_columns['status'] = __('Status', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public static function populate_columns($column, $post_id) {
        switch ($column) {
            case 'phone':
                $phone = get_post_meta($post_id, 'phone', true);
                if ($phone) {
                    echo esc_html($phone);
                }
                break;
                
            case 'budget':
                $budget = get_post_meta($post_id, 'budget', true);
                if ($budget) {
                    echo number_format($budget, 0, '', ' ') . ' ₽';
                }
                break;
                
            case 'status':
                $status = get_post_meta($post_id, 'status', true);
                if ($status) {
                    $statuses = [
                        'new' => __('New', 'auto-import-core'),
                        'in_progress' => __('In Progress', 'auto-import-core'),
                        'closed' => __('Closed', 'auto-import-core'),
                    ];
                    echo isset($statuses[$status]) ? esc_html($statuses[$status]) : esc_html($status);
                }
                break;
        }
    }
    
    public static function add_meta_boxes() {
        add_meta_box(
            'lead_details',
            __('Lead Details', 'auto-import-core'),
            [self::class, 'render_details_meta_box'],
            'lead',
            'normal',
            'high'
        );
    }
    
    public static function render_details_meta_box($post) {
        wp_nonce_field('lead_details_nonce', 'lead_details_nonce');
        
        $phone = get_post_meta($post->ID, 'phone', true);
        $email = get_post_meta($post->ID, 'email', true);
        $budget = get_post_meta($post->ID, 'budget', true);
        $status = get_post_meta($post->ID, 'status', true);
        $comment = get_post_meta($post->ID, 'comment', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="phone"><?php _e('Phone', 'auto-import-core'); ?></label></th>
                <td><input type="text" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="email"><?php _e('Email', 'auto-import-core'); ?></label></th>
                <td><input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="budget"><?php _e('Budget, ₽', 'auto-import-core'); ?></label></th>
                <td><input type="number" id="budget" name="budget" value="<?php echo esc_attr($budget); ?>" class="regular-text" step="100000"></td>
            </tr>
            <tr>
                <th><label for="status"><?php _e('Status', 'auto-import-core'); ?></label></th>
                <td>
                    <select id="status" name="status">
                        <option value="new" <?php selected($status, 'new'); ?>><?php _e('New', 'auto-import-core'); ?></option>
                        <option value="in_progress" <?php selected($status, 'in_progress'); ?>><?php _e('In Progress', 'auto-import-core'); ?></option>
                        <option value="closed" <?php selected($status, 'closed'); ?>><?php _e('Closed', 'auto-import-core'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="comment"><?php _e('Comment', 'auto-import-core'); ?></label></th>
                <td><textarea id="comment" name="comment" rows="4" class="large-text"><?php echo esc_textarea($comment); ?></textarea></td>
            </tr>
        </table>
        <?php
    }
    
    public static function save_meta_boxes($post_id, $post) {
        if (!isset($_POST['lead_details_nonce']) || !wp_verify_nonce($_POST['lead_details_nonce'], 'lead_details_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = ['phone', 'email', 'budget', 'status', 'comment'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}