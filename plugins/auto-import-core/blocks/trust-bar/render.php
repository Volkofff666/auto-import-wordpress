<?php
$items = $attributes['items'] ?? [];
if (empty($items)) return;

$icons = [
    'shield' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'truck' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'clock' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
    'check' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>',
];
?>
<section class="trust-bar">
    <div class="container">
        <div class="trust-bar__grid">
            <?php foreach ($items as $item): ?>
                <div class="trust-bar__item">
                    <div class="trust-bar__icon">
                        <?php echo $icons[$item['icon']] ?? $icons['check']; ?>
                    </div>
                    <p class="trust-bar__text"><?php echo esc_html($item['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>