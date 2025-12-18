<?php
$items = $attributes['items'] ?? [];
if (empty($items)) return;
?>
<section class="faq">
    <div class="container">
        <div class="faq__items">
            <?php foreach ($items as $index => $item): ?>
                <div class="faq__item">
                    <button class="faq__question" aria-expanded="false" data-index="<?php echo $index; ?>">
                        <?php echo esc_html($item['question']); ?>
                        <svg class="faq__icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                    <div class="faq__answer" style="display:none;">
                        <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>