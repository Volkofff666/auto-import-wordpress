<?php
/**
 * Template part for car filters
 */

$brands = get_terms(['taxonomy' => 'car_brand', 'hide_empty' => true]);
$body_types = get_terms(['taxonomy' => 'car_body', 'hide_empty' => true]);
$fuel_types = get_terms(['taxonomy' => 'car_fuel', 'hide_empty' => true]);
$transmissions = get_terms(['taxonomy' => 'car_transmission', 'hide_empty' => true]);
$statuses = get_terms(['taxonomy' => 'car_status', 'hide_empty' => true]);

$current_brand = isset($_GET['car_brand']) ? sanitize_text_field($_GET['car_brand']) : '';
$current_body = isset($_GET['car_body']) ? sanitize_text_field($_GET['car_body']) : '';
$current_fuel = isset($_GET['car_fuel']) ? sanitize_text_field($_GET['car_fuel']) : '';
$current_transmission = isset($_GET['car_transmission']) ? sanitize_text_field($_GET['car_transmission']) : '';
$current_status = isset($_GET['car_status']) ? sanitize_text_field($_GET['car_status']) : '';
$price_min = isset($_GET['price_min']) ? absint($_GET['price_min']) : '';
$price_max = isset($_GET['price_max']) ? absint($_GET['price_max']) : '';
$year_min = isset($_GET['year_min']) ? absint($_GET['year_min']) : '';
$year_max = isset($_GET['year_max']) ? absint($_GET['year_max']) : '';
?>

<form class="filters" method="get" action="<?php echo esc_url(get_post_type_archive_link('car')); ?>">
    <h3 class="filters__title"><?php _e('Filters', 'auto-import'); ?></h3>
    
    <div class="filters__grid">
        <?php if ($brands && !is_wp_error($brands)): ?>
            <div class="filters__field">
                <label for="filter-brand"><?php _e('Brand', 'auto-import'); ?></label>
                <select name="car_brand" id="filter-brand">
                    <option value=""><?php _e('All brands', 'auto-import'); ?></option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo esc_attr($brand->slug); ?>" <?php selected($current_brand, $brand->slug); ?>>
                            <?php echo esc_html($brand->name); ?> (<?php echo $brand->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <?php if ($body_types && !is_wp_error($body_types)): ?>
            <div class="filters__field">
                <label for="filter-body"><?php _e('Body Type', 'auto-import'); ?></label>
                <select name="car_body" id="filter-body">
                    <option value=""><?php _e('All types', 'auto-import'); ?></option>
                    <?php foreach ($body_types as $type): ?>
                        <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($current_body, $type->slug); ?>>
                            <?php echo esc_html($type->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <?php if ($fuel_types && !is_wp_error($fuel_types)): ?>
            <div class="filters__field">
                <label for="filter-fuel"><?php _e('Fuel Type', 'auto-import'); ?></label>
                <select name="car_fuel" id="filter-fuel">
                    <option value=""><?php _e('All types', 'auto-import'); ?></option>
                    <?php foreach ($fuel_types as $type): ?>
                        <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($current_fuel, $type->slug); ?>>
                            <?php echo esc_html($type->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <?php if ($transmissions && !is_wp_error($transmissions)): ?>
            <div class="filters__field">
                <label for="filter-transmission"><?php _e('Transmission', 'auto-import'); ?></label>
                <select name="car_transmission" id="filter-transmission">
                    <option value=""><?php _e('All types', 'auto-import'); ?></option>
                    <?php foreach ($transmissions as $type): ?>
                        <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($current_transmission, $type->slug); ?>>
                            <?php echo esc_html($type->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <?php if ($statuses && !is_wp_error($statuses)): ?>
            <div class="filters__field">
                <label for="filter-status"><?php _e('Status', 'auto-import'); ?></label>
                <select name="car_status" id="filter-status">
                    <option value=""><?php _e('All statuses', 'auto-import'); ?></option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo esc_attr($status->slug); ?>" <?php selected($current_status, $status->slug); ?>>
                            <?php echo esc_html($status->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <div class="filters__field">
            <label><?php _e('Price Range, ₽', 'auto-import'); ?></label>
            <div class="filters__range">
                <input type="number" name="price_min" placeholder="<?php _e('From', 'auto-import'); ?>" value="<?php echo esc_attr($price_min); ?>" step="100000">
                <span>—</span>
                <input type="number" name="price_max" placeholder="<?php _e('To', 'auto-import'); ?>" value="<?php echo esc_attr($price_max); ?>" step="100000">
            </div>
        </div>
        
        <div class="filters__field">
            <label><?php _e('Year Range', 'auto-import'); ?></label>
            <div class="filters__range">
                <input type="number" name="year_min" placeholder="<?php _e('From', 'auto-import'); ?>" value="<?php echo esc_attr($year_min); ?>" min="1900" max="<?php echo date('Y'); ?>">
                <span>—</span>
                <input type="number" name="year_max" placeholder="<?php _e('To', 'auto-import'); ?>" value="<?php echo esc_attr($year_max); ?>" min="1900" max="<?php echo date('Y'); ?>">
            </div>
        </div>
    </div>
    
    <div class="filters__actions">
        <button type="submit" class="btn btn--primary">
            <?php _e('Apply Filters', 'auto-import'); ?>
        </button>
        <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--secondary">
            <?php _e('Reset', 'auto-import'); ?>
        </a>
    </div>
</form>