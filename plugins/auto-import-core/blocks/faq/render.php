<?php
$title = $attributes['title'] ?? '';
$items = $attributes['items'] ?? [];

if (empty($items)) {
    return;
}

$unique_id = 'faq-' . wp_unique_id();
?>

<section class="faq">
    <div class="container">
        <?php if ($title): ?>
            <h2 class="faq__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <div class="faq__items">
            <?php foreach ($items as $index => $item): 
                $item_id = $unique_id . '-' . $index;
            ?>
                <div class="faq__item" data-faq-item>
                    <button 
                        class="faq__question" 
                        type="button"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr($item_id); ?>"
                        data-faq-trigger
                    >
                        <span><?php echo esc_html($item['question']); ?></span>
                        <svg class="faq__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    
                    <div 
                        class="faq__answer" 
                        id="<?php echo esc_attr($item_id); ?>"
                        data-faq-content
                    >
                        <div class="faq__answer-inner">
                            <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>