<?php
/**
 * Template for displaying car archive (catalog)
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="catalog-header">
            <h1 class="catalog-header__title"><?php _e('Car Catalog', 'auto-import'); ?></h1>
            <p class="catalog-header__description"><?php _e('Choose from our selection of quality imported vehicles', 'auto-import'); ?></p>
        </div>
        
        <?php get_template_part('template-parts/car-filters'); ?>
        
        <?php if (have_posts()): ?>
            <div class="catalog-controls">
                <div class="catalog-controls__count">
                    <?php
                    global $wp_query;
                    printf(
                        _n('%s vehicle found', '%s vehicles found', $wp_query->found_posts, 'auto-import'),
                        number_format_i18n($wp_query->found_posts)
                    );
                    ?>
                </div>
                
                <div class="catalog-controls__sort">
                    <label for="sort-select"><?php _e('Sort by:', 'auto-import'); ?></label>
                    <select id="sort-select" onchange="location = this.value;">
                        <?php
                        $current_url = home_url($_SERVER['REQUEST_URI']);
                        $sort_options = [
                            'date_desc' => __('Newest first', 'auto-import'),
                            'date_asc' => __('Oldest first', 'auto-import'),
                            'price_asc' => __('Price: Low to High', 'auto-import'),
                            'price_desc' => __('Price: High to Low', 'auto-import'),
                            'year_desc' => __('Year: Newest', 'auto-import'),
                            'year_asc' => __('Year: Oldest', 'auto-import'),
                        ];
                        
                        $current_sort = isset($_GET['orderby']) ? $_GET['orderby'] : 'date_desc';
                        
                        foreach ($sort_options as $value => $label) {
                            $url = add_query_arg('orderby', $value, remove_query_arg('orderby', $current_url));
                            printf(
                                '<option value="%s"%s>%s</option>',
                                esc_url($url),
                                selected($current_sort, $value, false),
                                esc_html($label)
                            );
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="car-grid__items">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'car-card');
                endwhile;
                ?>
            </div>
            
            <?php
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => __('&larr; Previous', 'auto-import'),
                'next_text' => __('Next &rarr;', 'auto-import'),
                'class' => 'pagination',
            ]);
            ?>
        <?php else: ?>
            <div class="no-results">
                <p><?php _e('No vehicles found matching your criteria.', 'auto-import'); ?></p>
                <a href="<?php echo get_post_type_archive_link('car'); ?>" class="btn btn--primary">
                    <?php _e('View all vehicles', 'auto-import'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();