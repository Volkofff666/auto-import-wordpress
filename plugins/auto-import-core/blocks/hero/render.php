<?php
$title = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$button_text = $attributes['buttonText'] ?? '';
$button_url = $attributes['buttonUrl'] ?? '';
$bg_id = $attributes['backgroundImageId'] ?? 0;
$bg_url = $bg_id ? wp_get_attachment_image_url($bg_id, 'full') : '';

$style = $bg_url ? 'background-image: url(' . esc_url($bg_url) . ');' : '';
?>
<section class="hero" style="<?php echo esc_attr($style); ?>">
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <h1 class="hero__title"><?php echo esc_html($title); ?></h1>
        <p class="hero__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php if ($button_text && $button_url): ?>
            <a href="<?php echo esc_url($button_url); ?>" class="btn btn--large btn--primary"><?php echo esc_html($button_text); ?></a>
        <?php endif; ?>
    </div>
</section>