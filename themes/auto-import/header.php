<?php
/**
 * Header Template
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link sr-only" href="#primary"><?php esc_html_e('Skip to content', 'auto-import'); ?></a>

    <header id="masthead" class="site-header">
        <div class="site-header__container container">
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__logo" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <button class="mobile-menu-toggle" aria-expanded="false" aria-controls="primary-menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <span class="sr-only"><?php esc_html_e('Menu', 'auto-import'); ?></span>
            </button>

            <nav id="primary-menu" class="main-nav" aria-label="<?php esc_attr_e('Primary menu', 'auto-import'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class' => 'main-nav__list',
                    'container' => false,
                    'fallback_cb' => false,
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'link_before' => '<span class="main-nav__link">',
                    'link_after' => '</span>',
                ]);
                ?>
            </nav>
        </div>
    </header>

    <main id="primary" class="site-main">
