<?php
/**
 * Admin Columns
 *
 * @package AutoImportCore
 */

namespace AIC\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class AdminColumns {
    
    public function __construct() {
        // Car columns
        add_filter('manage_car_posts_columns', [$this, 'car_columns']);
        add_action('manage_car_posts_custom_column', [$this, 'car_column_content'], 10, 2);
        add_filter('manage_edit-car_sortable_columns', [$this, 'car_sortable_columns']);
        
        // Lead columns
        add_filter('manage_lead_posts_columns', [$this, 'lead_columns']);
        add_action('manage_lead_posts_custom_column', [$this, 'lead_column_content'], 10, 2);
        
        // Filters
        add_action('restrict_manage_posts', [$this, 'add_filters']);
    }
    
    /**
     * Car columns
     */
    public function car_columns($columns) {
        $new_columns = [];
        
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumbnail'] = __('Image', 'auto-import-core');
        $new_columns['title'] = $columns['title'];
        $new_columns['brand_model'] = __('Brand / Model', 'auto-import-core');
        $new_columns['year'] = __('Year', 'auto-import-core');
        $new_columns['price'] = __('Price', 'auto-import-core');
        $new_columns['status'] = __('Status', 'auto-import-core');
        $new_columns['location'] = __('Location', 'auto-import-core');
        $new_columns['catalog'] = __('In Catalog', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Car column content
     */
    public function car_column_content($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [60, 60]);
                } else {
                    echo '<span class="dashicons dashicons-format-image" style="font-size: 40px; color: #ddd;"></span>';
                }
                break;
                
            case 'brand_model':
                $brands = get_the_terms($post_id, 'brand');
                $models = get_the_terms($post_id, 'model');
                
                if ($brands && !is_wp_error($brands)) {
                    echo esc_html($brands[0]->name);
                }
                if ($models && !is_wp_error($models)) {
                    echo ' / ' . esc_html($models[0]->name);
                }
                break;
                
            case 'year':
                $year = get_post_meta($post_id, 'aic_year', true);
                echo $year ? esc_html($year) : '—';
                break;
                
            case 'price':
                $price = get_post_meta($post_id, 'aic_price', true);
                if ($price) {
                    echo number_format($price, 0, '.', ' ') . ' ₽';
                } else {
                    echo '—';
                }
                break;
                
            case 'status':
                $terms = get_the_terms($post_id, 'status');
                if ($terms && !is_wp_error($terms)) {
                    $status_classes = [
                        'в-наличии' => 'success',
                        'в-пути' => 'info',
                        'под-заказ' => 'warning',
                        'продан' => 'danger',
                    ];
                    $slug = $terms[0]->slug;
                    $class = isset($status_classes[$slug]) ? $status_classes[$slug] : 'default';
                    echo '<span class="badge badge--' . esc_attr($class) . '">' . esc_html($terms[0]->name) . '</span>';
                } else {
                    echo '—';
                }
                break;
                
            case 'location':
                $terms = get_the_terms($post_id, 'location');
                if ($terms && !is_wp_error($terms)) {
                    echo esc_html($terms[0]->name);
                } else {
                    echo '—';
                }
                break;
                
            case 'catalog':
                $show = get_post_meta($post_id, 'aic_show_in_catalog', true);
                if ($show == '1') {
                    echo '<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>';
                } else {
                    echo '<span class="dashicons dashicons-dismiss" style="color: #dc3232;"></span>';
                }
                break;
        }
    }
    
    /**
     * Car sortable columns
     */
    public function car_sortable_columns($columns) {
        $columns['year'] = 'aic_year';
        $columns['price'] = 'aic_price';
        return $columns;
    }
    
    /**
     * Lead columns
     */
    public function lead_columns($columns) {
        $new_columns = [];
        
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Name', 'auto-import-core');
        $new_columns['phone'] = __('Phone', 'auto-import-core');
        $new_columns['email'] = __('Email', 'auto-import-core');
        $new_columns['budget'] = __('Budget', 'auto-import-core');
        $new_columns['status'] = __('Status', 'auto-import-core');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Lead column content
     */
    public function lead_column_content($column, $post_id) {
        switch ($column) {
            case 'phone':
                $phone = get_post_meta($post_id, 'aic_lead_phone', true);
                echo $phone ? esc_html($phone) : '—';
                break;
                
            case 'email':
                $email = get_post_meta($post_id, 'aic_lead_email', true);
                if ($email) {
                    echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                } else {
                    echo '—';
                }
                break;
                
            case 'budget':
                $budget = get_post_meta($post_id, 'aic_lead_budget', true);
                if ($budget) {
                    echo number_format($budget, 0, '.', ' ') . ' ₽';
                } else {
                    echo '—';
                }
                break;
                
            case 'status':
                $status = get_post_meta($post_id, 'aic_lead_status', true);
                $statuses = [
                    'new' => ['label' => __('New', 'auto-import-core'), 'class' => 'info'],
                    'in_progress' => ['label' => __('In Progress', 'auto-import-core'), 'class' => 'warning'],
                    'converted' => ['label' => __('Converted', 'auto-import-core'), 'class' => 'success'],
                    'lost' => ['label' => __('Lost', 'auto-import-core'), 'class' => 'danger'],
                ];
                
                if (isset($statuses[$status])) {
                    echo '<span class="badge badge--' . esc_attr($statuses[$status]['class']) . '">' . esc_html($statuses[$status]['label']) . '</span>';
                } else {
                    echo '—';
                }
                break;
        }
    }
    
    /**
     * Add filters
     */
    public function add_filters() {
        global $typenow;
        
        if ($typenow === 'car') {
            // Brand filter
            $brands = get_terms(['taxonomy' => 'brand', 'hide_empty' => true]);
            if ($brands) {
                $current = isset($_GET['brand']) ? $_GET['brand'] : '';
                echo '<select name="brand">';
                echo '<option value="">' . __('All Brands', 'auto-import-core') . '</option>';
                foreach ($brands as $brand) {
                    printf(
                        '<option value="%s" %s>%s (%d)</option>',
                        esc_attr($brand->slug),
                        selected($current, $brand->slug, false),
                        esc_html($brand->name),
                        $brand->count
                    );
                }
                echo '</select>';
            }
            
            // Status filter
            $statuses = get_terms(['taxonomy' => 'status', 'hide_empty' => true]);
            if ($statuses) {
                $current = isset($_GET['status']) ? $_GET['status'] : '';
                echo '<select name="status">';
                echo '<option value="">' . __('All Statuses', 'auto-import-core') . '</option>';
                foreach ($statuses as $status) {
                    printf(
                        '<option value="%s" %s>%s (%d)</option>',
                        esc_attr($status->slug),
                        selected($current, $status->slug, false),
                        esc_html($status->name),
                        $status->count
                    );
                }
                echo '</select>';
            }
        }
        
        if ($typenow === 'lead') {
            // Status filter
            $current = isset($_GET['lead_status']) ? $_GET['lead_status'] : '';
            echo '<select name="lead_status">';
            echo '<option value="">' . __('All Statuses', 'auto-import-core') . '</option>';
            echo '<option value="new" ' . selected($current, 'new', false) . '>' . __('New', 'auto-import-core') . '</option>';
            echo '<option value="in_progress" ' . selected($current, 'in_progress', false) . '>' . __('In Progress', 'auto-import-core') . '</option>';
            echo '<option value="converted" ' . selected($current, 'converted', false) . '>' . __('Converted', 'auto-import-core') . '</option>';
            echo '<option value="lost" ' . selected($current, 'lost', false) . '>' . __('Lost', 'auto-import-core') . '</option>';
            echo '</select>';
        }
    }
}
