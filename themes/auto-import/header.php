<?php
/**
 * The header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site">
    <header class="header">
        <div class="container">
            <div class="header__wrapper">
                <div class="header__logo">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                
                <?php if (has_nav_menu('primary')): ?>
                    <nav class="nav" aria-label="<?php esc_attr_e('Primary Navigation', 'auto-import'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'nav__list',
                            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'link_before' => '<span class="nav__link">',
                            'link_after' => '</span>',
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>
                
                <button class="menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'auto-import'); ?>" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    
    <?php
    // Show breadcrumbs on all pages except front page
    if (!is_front_page() && function_exists('ai_breadcrumbs')) {
        ai_breadcrumbs();
    }
    ?>