<?php
/**
 * Car Meta Boxes
 *
 * @package AutoImportCore
 */

namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class CarMetaBoxes {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_car', [$this, 'save_meta_boxes'], 10, 2);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Basic Information
        add_meta_box(
            'aic_car_basic_info',
            __('Basic Information', 'auto-import-core'),
            [$this, 'render_basic_info'],
            'car',
            'normal',
            'high'
        );
        
        // Technical Specifications
        add_meta_box(
            'aic_car_tech_specs',
            __('Technical Specifications', 'auto-import-core'),
            [$this, 'render_tech_specs'],
            'car',
            'normal',
            'default'
        );
        
        // Documents
        add_meta_box(
            'aic_car_documents',
            __('Documents', 'auto-import-core'),
            [$this, 'render_documents'],
            'car',
            'normal',
            'default'
        );
        
        // Gallery
        add_meta_box(
            'aic_car_gallery',
            __('Gallery', 'auto-import-core'),
            [$this, 'render_gallery'],
            'car',
            'side',
            'default'
        );
        
        // Publish Settings
        add_meta_box(
            'aic_car_publish',
            __('Publish Settings', 'auto-import-core'),
            [$this, 'render_publish_settings'],
            'car',
            'side',
            'high'
        );
    }
    
    /**
     * Render Basic Information meta box
     */
    public function render_basic_info($post) {
        wp_nonce_field('aic_car_meta_box', 'aic_car_meta_box_nonce');
        
        $price = get_post_meta($post->ID, 'aic_price', true);
        $year = get_post_meta($post->ID, 'aic_year', true);
        $mileage = get_post_meta($post->ID, 'aic_mileage', true);
        $vin = get_post_meta($post->ID, 'aic_vin', true);
        $color = get_post_meta($post->ID, 'aic_color', true);
        $steering_wheel = get_post_meta($post->ID, 'aic_steering_wheel', true);
        $owners = get_post_meta($post->ID, 'aic_owners', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field-group">
                <div class="aic-field">
                    <label for="aic_price"><?php _e('Price (RUB)', 'auto-import-core'); ?> <span class="required">*</span></label>
                    <input type="number" id="aic_price" name="aic_price" value="<?php echo esc_attr($price); ?>" step="1" min="0" required>
                </div>
                
                <div class="aic-field">
                    <label for="aic_year"><?php _e('Year', 'auto-import-core'); ?> <span class="required">*</span></label>
                    <input type="number" id="aic_year" name="aic_year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y') + 1; ?>" required>
                </div>
            </div>
            
            <div class="aic-field-group">
                <div class="aic-field">
                    <label for="aic_mileage"><?php _e('Mileage (km)', 'auto-import-core'); ?></label>
                    <input type="number" id="aic_mileage" name="aic_mileage" value="<?php echo esc_attr($mileage); ?>" step="1" min="0">
                </div>
                
                <div class="aic-field">
                    <label for="aic_vin"><?php _e('VIN Number', 'auto-import-core'); ?></label>
                    <input type="text" id="aic_vin" name="aic_vin" value="<?php echo esc_attr($vin); ?>" maxlength="17" style="text-transform: uppercase;">
                </div>
            </div>
            
            <div class="aic-field-group">
                <div class="aic-field">
                    <label for="aic_color"><?php _e('Color', 'auto-import-core'); ?></label>
                    <input type="text" id="aic_color" name="aic_color" value="<?php echo esc_attr($color); ?>">
                </div>
                
                <div class="aic-field">
                    <label for="aic_steering_wheel"><?php _e('Steering Wheel', 'auto-import-core'); ?></label>
                    <select id="aic_steering_wheel" name="aic_steering_wheel">
                        <option value=""><?php _e('Select...', 'auto-import-core'); ?></option>
                        <option value="left" <?php selected($steering_wheel, 'left'); ?>><?php _e('Left', 'auto-import-core'); ?></option>
                        <option value="right" <?php selected($steering_wheel, 'right'); ?>><?php _e('Right', 'auto-import-core'); ?></option>
                    </select>
                </div>
                
                <div class="aic-field">
                    <label for="aic_owners"><?php _e('Number of Owners', 'auto-import-core'); ?></label>
                    <input type="number" id="aic_owners" name="aic_owners" value="<?php echo esc_attr($owners); ?>" min="0" max="10">
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Technical Specifications meta box
     */
    public function render_tech_specs($post) {
        $engine_volume = get_post_meta($post->ID, 'aic_engine_volume', true);
        $engine_power = get_post_meta($post->ID, 'aic_engine_power', true);
        $equipment = get_post_meta($post->ID, 'aic_equipment', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field-group">
                <div class="aic-field">
                    <label for="aic_engine_volume"><?php _e('Engine Volume (L)', 'auto-import-core'); ?></label>
                    <input type="number" id="aic_engine_volume" name="aic_engine_volume" value="<?php echo esc_attr($engine_volume); ?>" step="0.1" min="0" max="10">
                </div>
                
                <div class="aic-field">
                    <label for="aic_engine_power"><?php _e('Engine Power (HP)', 'auto-import-core'); ?></label>
                    <input type="number" id="aic_engine_power" name="aic_engine_power" value="<?php echo esc_attr($engine_power); ?>" step="1" min="0">
                </div>
            </div>
            
            <div class="aic-field">
                <label for="aic_equipment"><?php _e('Equipment (one per line)', 'auto-import-core'); ?></label>
                <textarea id="aic_equipment" name="aic_equipment" rows="8" placeholder="<?php esc_attr_e('Leather seats\nSunroof\nNavigation system\nBackup camera', 'auto-import-core'); ?>"><?php echo esc_textarea($equipment); ?></textarea>
                <p class="description"><?php _e('Enter each feature on a new line', 'auto-import-core'); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Documents meta box
     */
    public function render_documents($post) {
        $customs_cleared = get_post_meta($post->ID, 'aic_customs_cleared', true);
        $title_status = get_post_meta($post->ID, 'aic_title_status', true);
        $condition = get_post_meta($post->ID, 'aic_condition', true);
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field">
                <label>
                    <input type="checkbox" name="aic_customs_cleared" value="1" <?php checked($customs_cleared, '1'); ?>>
                    <?php _e('Customs Cleared', 'auto-import-core'); ?>
                </label>
            </div>
            
            <div class="aic-field">
                <label for="aic_title_status"><?php _e('Title Status', 'auto-import-core'); ?></label>
                <input type="text" id="aic_title_status" name="aic_title_status" value="<?php echo esc_attr($title_status); ?>" placeholder="<?php esc_attr_e('Available, Pending, etc.', 'auto-import-core'); ?>">
            </div>
            
            <div class="aic-field">
                <label for="aic_condition"><?php _e('Condition', 'auto-import-core'); ?></label>
                <textarea id="aic_condition" name="aic_condition" rows="4"><?php echo esc_textarea($condition); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Gallery meta box
     */
    public function render_gallery($post) {
        $gallery_ids = get_post_meta($post->ID, 'aic_gallery', true);
        $gallery_ids = is_array($gallery_ids) ? $gallery_ids : [];
        
        ?>
        <div class="aic-meta-box">
            <div id="aic-gallery-container">
                <?php if (!empty($gallery_ids)) : ?>
                    <?php foreach ($gallery_ids as $image_id) : ?>
                        <?php $image_url = wp_get_attachment_image_url($image_id, 'thumbnail'); ?>
                        <?php if ($image_url) : ?>
                            <div class="aic-gallery-item" data-id="<?php echo esc_attr($image_id); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="">
                                <button type="button" class="aic-remove-image">&times;</button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <input type="hidden" id="aic_gallery" name="aic_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
            
            <button type="button" class="button button-secondary" id="aic-add-gallery-images">
                <?php _e('Add Gallery Images', 'auto-import-core'); ?>
            </button>
            
            <p class="description"><?php _e('Upload or select multiple images from media library. Drag to reorder.', 'auto-import-core'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Render Publish Settings meta box
     */
    public function render_publish_settings($post) {
        $show_in_catalog = get_post_meta($post->ID, 'aic_show_in_catalog', true);
        $show_in_catalog = $show_in_catalog === '' ? '1' : $show_in_catalog; // Default to checked
        
        ?>
        <div class="aic-meta-box">
            <div class="aic-field">
                <label>
                    <input type="checkbox" name="aic_show_in_catalog" value="1" <?php checked($show_in_catalog, '1'); ?>>
                    <strong><?php _e('Show in Catalog', 'auto-import-core'); ?></strong>
                </label>
                <p class="description"><?php _e('Display this car in the public catalog', 'auto-import-core'); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonce
        if (!isset($_POST['aic_car_meta_box_nonce']) || !wp_verify_nonce($_POST['aic_car_meta_box_nonce'], 'aic_car_meta_box')) {
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
        
        // Save basic info
        $this->save_field($post_id, 'aic_price', 'absint');
        $this->save_field($post_id, 'aic_year', 'absint');
        $this->save_field($post_id, 'aic_mileage', 'absint');
        $this->save_field($post_id, 'aic_vin', 'sanitize_text_field');
        $this->save_field($post_id, 'aic_color', 'sanitize_text_field');
        $this->save_field($post_id, 'aic_steering_wheel', 'sanitize_text_field');
        $this->save_field($post_id, 'aic_owners', 'absint');
        
        // Save technical specs
        $this->save_field($post_id, 'aic_engine_volume', 'floatval');
        $this->save_field($post_id, 'aic_engine_power', 'absint');
        $this->save_field($post_id, 'aic_equipment', 'sanitize_textarea_field');
        
        // Save documents
        $customs_cleared = isset($_POST['aic_customs_cleared']) ? '1' : '0';
        update_post_meta($post_id, 'aic_customs_cleared', $customs_cleared);
        
        $this->save_field($post_id, 'aic_title_status', 'sanitize_text_field');
        $this->save_field($post_id, 'aic_condition', 'sanitize_textarea_field');
        
        // Save gallery
        if (isset($_POST['aic_gallery'])) {
            $gallery_ids = array_filter(array_map('absint', explode(',', $_POST['aic_gallery'])));
            update_post_meta($post_id, 'aic_gallery', $gallery_ids);
        }
        
        // Save publish settings
        $show_in_catalog = isset($_POST['aic_show_in_catalog']) ? '1' : '0';
        update_post_meta($post_id, 'aic_show_in_catalog', $show_in_catalog);
    }
    
    /**
     * Helper: Save field
     */
    private function save_field($post_id, $field_name, $sanitize_callback = 'sanitize_text_field') {
        if (isset($_POST[$field_name])) {
            $value = call_user_func($sanitize_callback, $_POST[$field_name]);
            update_post_meta($post_id, $field_name, $value);
        } else {
            delete_post_meta($post_id, $field_name);
        }
    }
}
