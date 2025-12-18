<?php
/**
 * Search Results Template
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="search-results">
            <header class="search-results__header">
                <h1 class="search-results__title">
                    <?php printf(__('Search Results for: %s', 'auto-import'), '<span>' . get_search_query() . '</span>'); ?>
                </h1>
                <?php
                global $wp_query;
                if ($wp_query->found_posts > 0) {
                    printf(
                        '<p class="search-results__count">' . _n('%s result found', '%s results found', $wp_query->found_posts, 'auto-import') . '</p>',
                        number_format_i18n($wp_query->found_posts)
                    );
                }
                ?>
            </header>
            
            <?php if (have_posts()): ?>
                <div class="search-results__list">
                    <?php
                    while (have_posts()) :
                        the_post();
                        
                        if (get_post_type() === 'car') {
                            get_template_part('template-parts/content', 'car-card');
                        } else {
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
                                <h2 class="search-result__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <?php if (has_excerpt()): ?>
                                    <div class="search-result__excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn--secondary btn--small">
                                    <?php _e('Read More', 'auto-import'); ?>
                                </a>
                            </article>
                            <?php
                        }
                    endwhile;
                    ?>
                </div>
                
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('&larr; Previous', 'auto-import'),
                    'next_text' => __('Next &rarr;', 'auto-import'),
                ]);
                ?>
            <?php else: ?>
                <div class="no-results">
                    <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'auto-import'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();