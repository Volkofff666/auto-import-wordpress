/**
 * Catalog Page JavaScript
 */

(function($) {
    'use strict';
    
    // Contact Form
    function initContactForm() {
        const form = $('#catalog-contact-form');
        if (form.length === 0) return;
        
        form.on('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.find('button[type="submit"]');
            const messageEl = form.find('.form-message');
            const originalBtnText = submitBtn.text();
            
            // Disable button
            submitBtn.prop('disabled', true).text('Отправка...');
            messageEl.hide().removeClass('success error');
            
            // Collect form data
            const formData = {
                name: form.find('[name="name"]').val(),
                phone: form.find('[name="phone"]').val(),
                email: form.find('[name="email"]').val(),
                comment: form.find('[name="comment"]').val(),
                source_page: window.location.href,
            };
            
            try {
                const response = await fetch('/wp-json/aic/v1/leads', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    messageEl.addClass('success')
                        .text('Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.')
                        .show();
                    form[0].reset();
                } else {
                    throw new Error(data.message || 'Ошибка при отправке заявки');
                }
            } catch (error) {
                messageEl.addClass('error')
                    .text('Произошла ошибка: ' + error.message + '. Пожалуйста, попробуйте позже или позвоните нам.')
                    .show();
            } finally {
                submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    }
    
    // Smooth scroll to form
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');
            if (href === '#') return;
            
            const target = $(href);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    }
    
    // Phone mask (simple version)
    function initPhoneMask() {
        $('input[type="tel"]').on('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value[0] === '8') value = '7' + value.slice(1);
                if (value[0] !== '7') value = '7' + value;
                
                let formatted = '+7';
                if (value.length > 1) formatted += ' (' + value.slice(1, 4);
                if (value.length >= 5) formatted += ') ' + value.slice(4, 7);
                if (value.length >= 8) formatted += '-' + value.slice(7, 9);
                if (value.length >= 10) formatted += '-' + value.slice(9, 11);
                
                this.value = formatted;
            }
        });
    }
    
    // Initialize all
    $(document).ready(function() {
        initContactForm();
        initSmoothScroll();
        initPhoneMask();
    });
    
})(jQuery);