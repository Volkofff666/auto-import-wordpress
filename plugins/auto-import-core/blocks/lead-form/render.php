<?php
$title = $attributes['title'] ?? 'Оставьте заявку';
$subtitle = $attributes['subtitle'] ?? '';
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
            
            <form class="lead-form__form" id="lead-form" data-nonce="<?php echo wp_create_nonce('aic_lead_form'); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="lead_name">Ваше имя *</label>
                        <input type="text" id="lead_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="lead_phone">Телефон *</label>
                        <input type="tel" id="lead_phone" name="phone" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="lead_email">Email</label>
                        <input type="email" id="lead_email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="lead_budget">Бюджет (₽)</label>
                        <input type="number" id="lead_budget" name="budget">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lead_comment">Комментарий</label>
                    <textarea id="lead_comment" name="comment" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn--primary btn--large btn--block">Отправить заявку</button>
                <div class="lead-form__message" style="display:none;"></div>
            </form>
        </div>
    </div>
</section>