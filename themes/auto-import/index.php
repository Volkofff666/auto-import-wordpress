<?php
/**
 * The main template file
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;
            
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => __('&larr; Previous', 'auto-import'),
                'next_text' => __('Next &rarr;', 'auto-import'),
            ]);
        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>
</main>

<?php
get_footer();