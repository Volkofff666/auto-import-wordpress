<?php
$per_page = $attributes['perPage'] ?? 3;
$show_excerpt = $attributes['showExcerpt'] ?? true;

$args = [
    'post_type' => 'article',
    'posts_per_page' => $per_page,
    'orderby' => 'date',
    'order' => 'DESC'
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
                        <time class="article-card__date"><?php echo get_the_date(); ?></time>
                        <h3 class="article-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ($show_excerpt): ?>
                            <div class="article-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="article-card__link">Читать далее</a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>