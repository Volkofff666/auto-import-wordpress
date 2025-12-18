<?php
/**
 * Footer Template
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
    </main><!-- #primary -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="container">
                <div class="site-footer__container">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="site-footer__column">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="site-footer__column">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="site-footer__column">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <div class="site-footer__column">
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="site-footer__bottom">
                <p>
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
                    <?php esc_html_e('All rights reserved.', 'auto-import'); ?>
                </p>
                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'container' => 'nav',
                        'container_class' => 'footer-navigation',
                        'depth' => 1,
                    ]);
                }
                ?>
            </div>
        </div>
    </footer>

    <?php
    // Output Schema.org structured data
    if (function_exists('auto_import_output_schema')) {
        auto_import_output_schema();
    }
    ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
