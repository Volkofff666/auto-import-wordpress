<?php
$posts_per_page = $attributes['postsPerPage'] ?? 3;
$show_excerpt = $attributes['showExcerpt'] ?? true;
$show_date = $attributes['showDate'] ?? true;

$args = [
    'post_type' => 'article',
    'posts_per_page' => $posts_per_page,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
];

$query = new WP_Query($args);

if (!$query->have_posts()) {
    return;
}
?>

<section class="articles-grid">
    <div class="container">
        <div class="articles-grid__items">
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <article class="article-card">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" class="article-card__image">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="article-card__content">
                        <?php if ($show_date): ?>
                            <time class="article-card__date" datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date('d.m.Y'); ?>
                            </time>
                        <?php endif; ?>
                        
                        <h3 class="article-card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ($show_excerpt && has_excerpt()): ?>
                            <div class="article-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php the_permalink(); ?>" class="article-card__link">
                            Читать далее
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>