<?php
/**
 * The footer template
 */
?>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <?php if (is_active_sidebar('footer-1')): ?>
                    <?php dynamic_sidebar('footer-1'); ?>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-2')): ?>
                    <?php dynamic_sidebar('footer-2'); ?>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-3')): ?>
                    <?php dynamic_sidebar('footer-3'); ?>
                <?php endif; ?>
            </div>
            
            <div class="footer__bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'auto-import'); ?></p>
            </div>
        </div>
    </footer>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>