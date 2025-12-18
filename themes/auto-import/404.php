<?php
/**
 * 404 Error Page Template
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="error-404">
            <h1 class="error-404__title">404</h1>
            <h2 class="error-404__subtitle"><?php _e('Page Not Found', 'auto-import'); ?></h2>
            <p class="error-404__description">
                <?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'auto-import'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary btn--large">
                <?php _e('Go to Homepage', 'auto-import'); ?>
            </a>
            
            <div class="mt-2xl">
                <h3><?php _e('Try searching for what you need:', 'auto-import'); ?></h3>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();