<?php
$title = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$button_text = $attributes['buttonText'] ?? '';
$button_url = $attributes['buttonUrl'] ?? '';
$bg_image = $attributes['backgroundImageUrl'] ?? '';

$style = $bg_image ? 'style="background-image: url(' . esc_url($bg_image) . ');"' : '';
?>

<section class="hero" <?php echo $style; ?>>
    <div class="container">
        <div class="hero__content">
            <?php if ($title): ?>
                <h1 class="hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            
            <?php if ($subtitle): ?>
                <p class="hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($button_text && $button_url): ?>
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn--primary btn--large">
                    <?php echo esc_html($button_text); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>