/**
 * Homepage JavaScript - Version 2.0
 */

(function($) {
    'use strict';
    
    // Countdown Timer
    function initCountdown() {
        const timer = $('.hero-liquidation__timer');
        if (timer.length === 0) return;
        
        const endDate = new Date(timer.data('end')).getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = endDate - now;
            
            if (distance < 0) {
                $('#days').text('00');
                $('#hours').text('00');
                $('#minutes').text('00');
                $('#seconds').text('00');
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $('#days').text(String(days).padStart(2, '0'));
            $('#hours').text(String(hours).padStart(2, '0'));
            $('#minutes').text(String(minutes).padStart(2, '0'));
            $('#seconds').text(String(seconds).padStart(2, '0'));
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    }
    
    // Competitor Form
    function initCompetitorForm() {
        const form = $('#competitor-form');
        if (form.length === 0) return;
        
        form.on('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.find('button[type="submit"]');
            const messageEl = form.find('.form-message');
            const originalBtnText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Отправка...');
            messageEl.hide().removeClass('success error');
            
            const formData = {
                name: form.find('[name="name"]').val(),
                phone: form.find('[name="phone"]').val(),
                comment: 'Цена конкурента: ' + form.find('[name="competitor_price"]').val(),
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
                        .text('Спасибо! Мы свяжемся с вами и предложим лучшие условия.')
                        .show();
                    form[0].reset();
                } else {
                    throw new Error(data.message || 'Ошибка при отправке заявки');
                }
            } catch (error) {
                messageEl.addClass('error')
                    .text('Произошла ошибка: ' + error.message)
                    .show();
            } finally {
                submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    }
    
    // Main Contact Form
    function initContactForm() {
        const form = $('#main-contact-form');
        if (form.length === 0) return;
        
        form.on('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.find('button[type="submit"]');
            const messageEl = form.find('.form-message');
            const originalBtnText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Отправка...');
            messageEl.hide().removeClass('success error');
            
            const formData = {
                name: form.find('[name="name"]').val(),
                phone: form.find('[name="phone"]').val(),
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
    
    // Phone mask
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
    
    // Smooth scroll
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
    
    // Initialize all
    $(document).ready(function() {
        initCountdown();
        initCompetitorForm();
        initContactForm();
        initPhoneMask();
        initSmoothScroll();
    });
    
})(jQuery);