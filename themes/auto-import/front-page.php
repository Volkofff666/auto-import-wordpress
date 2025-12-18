<?php
/**
 * Front Page Template
 * This template is used for the homepage when set to display a static page.
 * If you want to use Gutenberg blocks, just create a page and assign it as homepage.
 */

get_header();

while (have_posts()) : the_post();
    ?>
    <main class="site-main">
        <?php the_content(); ?>
    </main>
    <?php
endwhile;

get_footer();