<?php
/**
 * Car Gallery Template Part
 *
 * @package AutoImport
 */

if (!defined('ABSPATH')) {
    exit;
}

$gallery_ids = get_post_meta(get_the_ID(), 'aic_gallery', true);

if (empty($gallery_ids) && !has_post_thumbnail()) {
    return;
}

// Prepare images array
$images = [];

if (has_post_thumbnail()) {
    $images[] = get_post_thumbnail_id();
}

if (!empty($gallery_ids) && is_array($gallery_ids)) {
    $images = array_merge($images, $gallery_ids);
}

$images = array_unique($images);

if (empty($images)) {
    return;
}
?>

<div class="car-gallery">
    <?php if (count($images) > 0) : ?>
        <div class="car-gallery__main">
            <?php
            $main_image = wp_get_attachment_image_src($images[0], 'car-large');
            if ($main_image) :
            ?>
                <a href="<?php echo esc_url($main_image[0]); ?>" class="car-gallery__main-image">
                    <img src="<?php echo esc_url($main_image[0]); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (count($images) > 1) : ?>
        <div class="car-gallery__thumbnails">
            <?php foreach ($images as $index => $image_id) : ?>
                <?php
                $thumbnail = wp_get_attachment_image_src($image_id, 'thumbnail');
                $full = wp_get_attachment_image_src($image_id, 'full');
                if ($thumbnail && $full) :
                ?>
                    <a href="<?php echo esc_url($full[0]); ?>" class="car-gallery__thumbnail" data-index="<?php echo esc_attr($index); ?>">
                        <img src="<?php echo esc_url($thumbnail[0]); ?>" alt="<?php the_title_attribute(); ?> - <?php echo $index + 1; ?>" loading="lazy">
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.car-gallery {
    margin-bottom: var(--spacing-6);
}

.car-gallery__main-image {
    display: block;
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    border-radius: var(--radius-base);
    background-color: var(--color-bg-gray);
}

.car-gallery__main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.car-gallery__main-image:hover img {
    transform: scale(1.05);
}

.car-gallery__thumbnails {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: var(--spacing-2);
    margin-top: var(--spacing-3);
}

.car-gallery__thumbnail {
    display: block;
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: var(--radius-sm);
    border: 2px solid transparent;
    transition: var(--transition-base);
}

.car-gallery__thumbnail:hover {
    border-color: var(--color-primary);
}

.car-gallery__thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-4);
    padding: var(--spacing-4);
    background-color: var(--color-bg-gray);
    border-radius: var(--radius-base);
}

.spec-item {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-1);
}

.spec-item__label {
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

.spec-item__value {
    font-weight: var(--font-weight-semibold);
    color: var(--color-text);
}

.equipment-list {
    list-style: none;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-2);
}

.equipment-list li {
    padding: var(--spacing-2) var(--spacing-3);
    background-color: var(--color-bg-gray);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-sm);
}

.single-car-section {
    margin-bottom: var(--spacing-8);
}

.single-car-section h2 {
    margin-bottom: var(--spacing-4);
}
</style>
