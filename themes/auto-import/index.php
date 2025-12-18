<?php
/**
 * Index Template (Fallback)
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="container">
    <div class="content-area" style="padding: 80px 0;">
        <?php if (have_posts()) : ?>
            
            <div class="grid grid--3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="card__body">
                            <h2 class="card__title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="card__meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </div>
                            
                            <?php if (has_excerpt()) : ?>
                                <div class="card__excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card__footer">
                            <a href="<?php the_permalink(); ?>" class="btn btn--secondary">
                                <?php esc_html_e('Read More', 'auto-import'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => __('&laquo; Previous', 'auto-import'),
                'next_text' => __('Next &raquo;', 'auto-import'),
            ]);
            ?>
            
        <?php else : ?>
            
            <div class="no-results">
                <h1><?php esc_html_e('Nothing Found', 'auto-import'); ?></h1>
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'auto-import'); ?></p>
                <?php get_search_form(); ?>
            </div>
            
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
