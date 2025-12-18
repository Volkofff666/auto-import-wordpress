<?php
/**
 * Lead Meta Boxes
 *
 * @package AutoImportCore
 */

namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class LeadMetaBoxes {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_lead', [$this, 'save_meta_boxes'], 10, 2);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aic_lead_details',
            __('Lead Details', 'auto-import-core'),
            [$this, 'render_lead_details'],
            'lead',
            'normal',
            'high'
        );
    }
    
    /**
     * Render lead details meta box
     */
    public function render_lead_details($post) {
        wp_nonce_field('aic_lead_meta_box', 'aic_lead_meta_box_nonce');
        
        $phone = get_post_meta($post->ID, 'aic_lead_phone', true);
        $email = get_post_meta($post->ID, 'aic_lead_email', true);
        $budget = get_post_meta($post->ID, 'aic_lead_budget', true);
        $comment = get_post_meta($post->ID, 'aic_lead_comment', true);
        $source_page = get_post_meta($post->ID, 'aic_lead_source_page', true);
        $status = get_post_meta($post->ID, 'aic_lead_status', true);
        $notes = get_post_meta($post->ID, 'aic_lead_notes', true);
        
        if (empty($status)) {
            $status = 'new';
        }
        
        ?>
        <div class="aic-meta-box">
            <h3><?php _e('Contact Information', 'auto-import-core'); ?></h3>
            
            <div class="aic-field-group">
                <div class="aic-field">
                    <label for="aic_lead_phone"><?php _e('Phone', 'auto-import-core'); ?></label>
                    <input type="tel" id="aic_lead_phone" name="aic_lead_phone" value="<?php echo esc_attr($phone); ?>" readonly>
                </div>
                
                <div class="aic-field">
                    <label for="aic_lead_email"><?php _e('Email', 'auto-import-core'); ?></label>
                    <input type="email" id="aic_lead_email" name="aic_lead_email" value="<?php echo esc_attr($email); ?>" readonly>
                </div>
            </div>
            
            <div class="aic-field">
                <label for="aic_lead_budget"><?php _e('Budget (RUB)', 'auto-import-core'); ?></label>
                <input type="number" id="aic_lead_budget" name="aic_lead_budget" value="<?php echo esc_attr($budget); ?>" readonly>
            </div>
            
            <div class="aic-field">
                <label for="aic_lead_comment"><?php _e('Comment', 'auto-import-core'); ?></label>
                <textarea id="aic_lead_comment" name="aic_lead_comment" rows="4" readonly><?php echo esc_textarea($comment); ?></textarea>
            </div>
            
            <?php if ($source_page) : ?>
                <div class="aic-field">
                    <label><?php _e('Source Page', 'auto-import-core'); ?></label>
                    <p><a href="<?php echo esc_url($source_page); ?>" target="_blank"><?php echo esc_html($source_page); ?></a></p>
                </div>
            <?php endif; ?>
            
            <hr>
            
            <h3><?php _e('Lead Management', 'auto-import-core'); ?></h3>
            
            <div class="aic-field">
                <label for="aic_lead_status"><?php _e('Status', 'auto-import-core'); ?></label>
                <select id="aic_lead_status" name="aic_lead_status">
                    <option value="new" <?php selected($status, 'new'); ?>><?php _e('New', 'auto-import-core'); ?></option>
                    <option value="in_progress" <?php selected($status, 'in_progress'); ?>><?php _e('In Progress', 'auto-import-core'); ?></option>
                    <option value="converted" <?php selected($status, 'converted'); ?>><?php _e('Converted', 'auto-import-core'); ?></option>
                    <option value="lost" <?php selected($status, 'lost'); ?>><?php _e('Lost', 'auto-import-core'); ?></option>
                </select>
            </div>
            
            <div class="aic-field">
                <label for="aic_lead_notes"><?php _e('Manager Notes', 'auto-import-core'); ?></label>
                <textarea id="aic_lead_notes" name="aic_lead_notes" rows="6" placeholder="<?php esc_attr_e('Add your notes about this lead...', 'auto-import-core'); ?>"><?php echo esc_textarea($notes); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonce
        if (!isset($_POST['aic_lead_meta_box_nonce']) || !wp_verify_nonce($_POST['aic_lead_meta_box_nonce'], 'aic_lead_meta_box')) {
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
        
        // Save status (editable)
        if (isset($_POST['aic_lead_status'])) {
            $status = sanitize_text_field($_POST['aic_lead_status']);
            update_post_meta($post_id, 'aic_lead_status', $status);
        }
        
        // Save notes (editable)
        if (isset($_POST['aic_lead_notes'])) {
            $notes = sanitize_textarea_field($_POST['aic_lead_notes']);
            update_post_meta($post_id, 'aic_lead_notes', $notes);
        }
    }
}
