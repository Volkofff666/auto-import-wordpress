<?php
/**
 * Car Archive Template (Catalog)
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="container">
    <?php
    // Breadcrumbs
    if (function_exists('auto_import_breadcrumbs')) {
        auto_import_breadcrumbs();
    }
    ?>
    
    <div class="catalog-layout">
        <!-- Filters Sidebar -->
        <aside class="catalog-layout__sidebar">
            <?php get_template_part('template-parts/filters', 'sidebar'); ?>
        </aside>
        
        <!-- Main Content -->
        <div class="catalog-layout__main">
            <div class="catalog-header">
                <h1 class="catalog-header__title">
                    <?php
                    if (is_tax()) {
                        single_term_title();
                    } else {
                        esc_html_e('Car Catalog', 'auto-import');
                    }
                    ?>
                </h1>
                
                <div class="catalog-header__controls">
                    <div class="catalog-sort">
                        <label for="sort" class="sr-only"><?php esc_html_e('Sort by', 'auto-import'); ?></label>
                        <select id="sort" name="orderby" class="form-control">
                            <option value="date_desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date_desc'); ?>>
                                <?php esc_html_e('Date: Newest First', 'auto-import'); ?>
                            </option>
                            <option value="date_asc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date_asc'); ?>>
                                <?php esc_html_e('Date: Oldest First', 'auto-import'); ?>
                            </option>
                            <option value="price_asc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price_asc'); ?>>
                                <?php esc_html_e('Price: Low to High', 'auto-import'); ?>
                            </option>
                            <option value="price_desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price_desc'); ?>>
                                <?php esc_html_e('Price: High to Low', 'auto-import'); ?>
                            </option>
                            <option value="year_desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'year_desc'); ?>>
                                <?php esc_html_e('Year: Newest First', 'auto-import'); ?>
                            </option>
                            <option value="year_asc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'year_asc'); ?>>
                                <?php esc_html_e('Year: Oldest First', 'auto-import'); ?>
                            </option>
                        </select>
                    </div>
                    
                    <div class="catalog-count">
                        <?php
                        global $wp_query;
                        printf(
                            esc_html__('Found: %s cars', 'auto-import'),
                            '<strong>' . $wp_query->found_posts . '</strong>'
                        );
                        ?>
                    </div>
                </div>
            </div>
            
            <?php if (have_posts()) : ?>
                <div class="car-grid car-grid--columns-3">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/car', 'card'); ?>
                    <?php endwhile; ?>
                </div>
                
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('&laquo; Previous', 'auto-import'),
                    'next_text' => __('Next &raquo;', 'auto-import'),
                    'class' => 'pagination',
                ]);
                ?>
            <?php else : ?>
                <div class="no-results" style="text-align: center; padding: 80px 20px;">
                    <h2><?php esc_html_e('No cars found', 'auto-import'); ?></h2>
                    <p><?php esc_html_e('Try adjusting your filters or search criteria.', 'auto-import'); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('car')); ?>" class="btn btn--primary">
                        <?php esc_html_e('View All Cars', 'auto-import'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();
