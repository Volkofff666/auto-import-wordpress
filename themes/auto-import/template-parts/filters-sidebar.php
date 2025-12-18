<?php
/**
 * Filters Sidebar Template Part
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="filters-sidebar">
    <form id="catalog-filters" method="get" action="<?php echo esc_url(get_post_type_archive_link('car')); ?>">
        <h2 class="filters-sidebar__title"><?php esc_html_e('Filters', 'auto-import'); ?></h2>
        
        <!-- Price Range -->
        <div class="filter-group">
            <h3 class="filter-group__title"><?php esc_html_e('Price', 'auto-import'); ?></h3>
            <div class="filter-group__content">
                <div class="form-row">
                    <input type="number" name="min_price" id="min_price" placeholder="<?php esc_attr_e('From', 'auto-import'); ?>" value="<?php echo esc_attr($_GET['min_price'] ?? ''); ?>" class="form-control">
                    <input type="number" name="max_price" id="max_price" placeholder="<?php esc_attr_e('To', 'auto-import'); ?>" value="<?php echo esc_attr($_GET['max_price'] ?? ''); ?>" class="form-control">
                </div>
            </div>
        </div>
        
        <!-- Year Range -->
        <div class="filter-group">
            <h3 class="filter-group__title"><?php esc_html_e('Year', 'auto-import'); ?></h3>
            <div class="filter-group__content">
                <div class="form-row">
                    <input type="number" name="min_year" placeholder="<?php esc_attr_e('From', 'auto-import'); ?>" value="<?php echo esc_attr($_GET['min_year'] ?? ''); ?>" class="form-control" min="1900" max="<?php echo date('Y') + 1; ?>">
                    <input type="number" name="max_year" placeholder="<?php esc_attr_e('To', 'auto-import'); ?>" value="<?php echo esc_attr($_GET['max_year'] ?? ''); ?>" class="form-control" min="1900" max="<?php echo date('Y') + 1; ?>">
                </div>
            </div>
        </div>
        
        <!-- Brand -->
        <?php
        $brands = get_terms(['taxonomy' => 'brand', 'hide_empty' => true]);
        if ($brands && !is_wp_error($brands)) :
        ?>
            <div class="filter-group">
                <h3 class="filter-group__title"><?php esc_html_e('Brand', 'auto-import'); ?></h3>
                <div class="filter-group__content">
                    <select name="brand" class="form-control">
                        <option value=""><?php esc_html_e('All Brands', 'auto-import'); ?></option>
                        <?php foreach ($brands as $brand) : ?>
                            <option value="<?php echo esc_attr($brand->slug); ?>" <?php selected($_GET['brand'] ?? '', $brand->slug); ?>>
                                <?php echo esc_html($brand->name); ?> (<?php echo $brand->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Body Type -->
        <?php
        $body_types = get_terms(['taxonomy' => 'body_type', 'hide_empty' => true]);
        if ($body_types && !is_wp_error($body_types)) :
        ?>
            <div class="filter-group">
                <h3 class="filter-group__title"><?php esc_html_e('Body Type', 'auto-import'); ?></h3>
                <div class="filter-group__content">
                    <?php foreach ($body_types as $body_type) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="body_type[]" value="<?php echo esc_attr($body_type->slug); ?>" <?php checked(in_array($body_type->slug, $_GET['body_type'] ?? [])); ?>>
                            <span><?php echo esc_html($body_type->name); ?> (<?php echo $body_type->count; ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Fuel Type -->
        <?php
        $fuel_types = get_terms(['taxonomy' => 'fuel', 'hide_empty' => true]);
        if ($fuel_types && !is_wp_error($fuel_types)) :
        ?>
            <div class="filter-group">
                <h3 class="filter-group__title"><?php esc_html_e('Fuel Type', 'auto-import'); ?></h3>
                <div class="filter-group__content">
                    <?php foreach ($fuel_types as $fuel_type) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="fuel[]" value="<?php echo esc_attr($fuel_type->slug); ?>" <?php checked(in_array($fuel_type->slug, $_GET['fuel'] ?? [])); ?>>
                            <span><?php echo esc_html($fuel_type->name); ?> (<?php echo $fuel_type->count; ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Transmission -->
        <?php
        $transmissions = get_terms(['taxonomy' => 'transmission', 'hide_empty' => true]);
        if ($transmissions && !is_wp_error($transmissions)) :
        ?>
            <div class="filter-group">
                <h3 class="filter-group__title"><?php esc_html_e('Transmission', 'auto-import'); ?></h3>
                <div class="filter-group__content">
                    <?php foreach ($transmissions as $transmission) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="transmission[]" value="<?php echo esc_attr($transmission->slug); ?>" <?php checked(in_array($transmission->slug, $_GET['transmission'] ?? [])); ?>>
                            <span><?php echo esc_html($transmission->name); ?> (<?php echo $transmission->count; ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Drive Type -->
        <?php
        $drives = get_terms(['taxonomy' => 'drive', 'hide_empty' => true]);
        if ($drives && !is_wp_error($drives)) :
        ?>
            <div class="filter-group">
                <h3 class="filter-group__title"><?php esc_html_e('Drive Type', 'auto-import'); ?></h3>
                <div class="filter-group__content">
                    <?php foreach ($drives as $drive) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="drive[]" value="<?php echo esc_attr($drive->slug); ?>" <?php checked(in_array($drive->slug, $_GET['drive'] ?? [])); ?>>
                            <span><?php echo esc_html($drive->name); ?> (<?php echo $drive->count; ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="btn btn--primary btn--block">
            <?php esc_html_e('Apply Filters', 'auto-import'); ?>
        </button>
        
        <button type="button" id="reset-filters" class="btn btn--secondary btn--block mt-2">
            <?php esc_html_e('Reset Filters', 'auto-import'); ?>
        </button>
    </form>
</div>

<style>
.filters-sidebar {
    background-color: var(--color-bg);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-base);
    padding: var(--spacing-6);
}

.filters-sidebar__title {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: var(--spacing-4);
}

.filter-group {
    margin-bottom: var(--spacing-6);
    padding-bottom: var(--spacing-6);
    border-bottom: 1px solid var(--color-border);
}

.filter-group:last-of-type {
    border-bottom: none;
}

.filter-group__title {
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    margin-bottom: var(--spacing-3);
}

.filter-group__content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    cursor: pointer;
    padding: var(--spacing-1) 0;
}

.filter-checkbox input[type="checkbox"] {
    margin: 0;
}

.filter-checkbox:hover {
    color: var(--color-primary);
}

.catalog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-6);
    flex-wrap: wrap;
    gap: var(--spacing-4);
}

.catalog-header__title {
    margin: 0;
}

.catalog-header__controls {
    display: flex;
    align-items: center;
    gap: var(--spacing-4);
}

.catalog-sort select {
    min-width: 200px;
}

.catalog-count {
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

@media (max-width: 768px) {
    .catalog-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .catalog-header__controls {
        width: 100%;
        flex-direction: column;
    }
    
    .catalog-sort {
        width: 100%;
    }
    
    .catalog-sort select {
        width: 100%;
    }
}
</style>
