<?php
/**
 * Template for displaying article archive (blog)
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php _e('Blog', 'auto-import'); ?></h1>
        </header>
        
        <?php if (have_posts()): ?>
            <div class="articles-grid__items">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="article-card__image">
                                <?php the_post_thumbnail('article-thumbnail'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="article-card__content">
                            <time class="article-card__date" datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date('d.m.Y'); ?>
                            </time>
                            
                            <h2 class="article-card__title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <?php if (has_excerpt()): ?>
                                <div class="article-card__excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="article-card__link">
                                <?php _e('Read More', 'auto-import'); ?>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php
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
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();