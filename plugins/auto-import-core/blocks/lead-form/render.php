<?php
$title = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$unique_id = 'lead-form-' . wp_unique_id();
?>

<section class="lead-form">
    <div class="container">
        <div class="lead-form__wrapper">
            <?php if ($title): ?>
                <h2 class="lead-form__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            
            <?php if ($subtitle): ?>
                <p class="lead-form__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            
            <form class="lead-form__form" id="<?php echo esc_attr($unique_id); ?>" data-form="lead">
                <div class="lead-form__row">
                    <div class="lead-form__field">
                        <input 
                            type="text" 
                            name="name" 
                            placeholder="Ваше имя *" 
                            required 
                            class="lead-form__input"
                        >
                    </div>
                    <div class="lead-form__field">
                        <input 
                            type="tel" 
                            name="phone" 
                            placeholder="Телефон *" 
                            required 
                            class="lead-form__input"
                        >
                    </div>
                </div>
                
                <div class="lead-form__field">
                    <input 
                        type="number" 
                        name="budget" 
                        placeholder="Бюджет, ₽" 
                        class="lead-form__input"
                    >
                </div>
                
                <div class="lead-form__field">
                    <textarea 
                        name="comment" 
                        placeholder="Комментарий" 
                        rows="3" 
                        class="lead-form__input"
                    ></textarea>
                </div>
                
                <button type="submit" class="btn btn--primary btn--large btn--block">
                    Отправить заявку
                </button>
                
                <div class="lead-form__message" style="display: none;"></div>
            </form>
        </div>
    </div>
</section>