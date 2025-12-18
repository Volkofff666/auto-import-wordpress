<?php
/**
 * Template part for displaying a message when no content is found
 */
?>

<section class="no-results">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Nothing Found', 'auto-import'); ?></h1>
    </header>
    
    <div class="page-content">
        <?php if (is_search()): ?>
            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'auto-import'); ?></p>
            <?php get_search_form(); ?>
        <?php else: ?>
            <p><?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'auto-import'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>