<?php
/**
 * Template for displaying single article
 */

get_header();

while (have_posts()) : the_post();
?>

<main class="site-main">
    <div class="container">
        <article id="post-<?php the_ID(); ?>" <?php post_class('article-single'); ?>>
            <header class="article-header">
                <h1 class="article-title"><?php the_title(); ?></h1>
                
                <div class="article-meta">
                    <time class="article-date" datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date('d F Y'); ?>
                    </time>
                    
                    <?php
                    $categories = get_the_category();
                    if ($categories) {
                        echo '<span class="article-categories">';
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="badge badge--info">' . esc_html($category->name) . '</a> ';
                        }
                        echo '</span>';
                    }
                    ?>
                </div>
            </header>
            
            <?php if (has_post_thumbnail()): ?>
                <div class="article-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="article-content content-narrow">
                <?php the_content(); ?>
                
                <?php
                wp_link_pages([
                    'before' => '<div class="page-links">' . __('Pages:', 'auto-import'),
                    'after' => '</div>',
                ]);
                ?>
            </div>
            
            <?php
            $tags = get_the_tags();
            if ($tags) {
                echo '<footer class="article-footer">';
                echo '<div class="article-tags">';
                echo '<span>' . __('Tags:', 'auto-import') . '</span> ';
                foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge">' . esc_html($tag->name) . '</a> ';
                }
                echo '</div>';
                echo '</footer>';
            }
            ?>
        </article>
        
        <?php
        // Navigation to previous/next article
        the_post_navigation([
            'prev_text' => '<span class="nav-subtitle">' . __('Previous:', 'auto-import') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . __('Next:', 'auto-import') . '</span> <span class="nav-title">%title</span>',
        ]);
        ?>
        
        <?php
        // If comments are open or there is at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
    </div>
</main>

<?php
endwhile;

get_footer();