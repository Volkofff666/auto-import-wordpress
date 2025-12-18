/**
 * Single Car Page JavaScript - Version 2.0
 */

(function($) {
    'use strict';
    
    // Gallery Slider
    function initGallery() {
        const thumbs = $('.gallery-thumb');
        const slides = $('.gallery-slide');
        
        if (thumbs.length === 0) return;
        
        thumbs.on('click', function() {
            const index = $(this).data('index');
            
            thumbs.removeClass('active');
            $(this).addClass('active');
            
            slides.removeClass('active');
            slides.eq(index).addClass('active');
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                const activeIndex = thumbs.filter('.active').index();
                let newIndex;
                
                if (e.key === 'ArrowLeft') {
                    newIndex = activeIndex > 0 ? activeIndex - 1 : thumbs.length - 1;
                } else {
                    newIndex = activeIndex < thumbs.length - 1 ? activeIndex + 1 : 0;
                }
                
                thumbs.eq(newIndex).click();
            }
        });
    }
    
    // Tabs
    function initTabs() {
        const tabs = $('.car-tabs__tab');
        const panes = $('.car-tabs__pane');
        
        tabs.on('click', function() {
            const targetTab = $(this).data('tab');
            
            tabs.removeClass('active');
            $(this).addClass('active');
            
            panes.removeClass('active');
            panes.filter('[data-pane="' + targetTab + '"]').addClass('active');
        });
    }
    
    // Countdown Timer
    function initCountdown() {
        const timer = $('.deal-badge__timer');
        if (timer.length === 0) return;
        
        const endDate = new Date(timer.data('end')).getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = endDate - now;
            
            if (distance < 0) {
                timer.find('.timer-countdown').text('00:00:00');
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            const formatted = 
                String(days * 24 + hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
            
            timer.find('.timer-countdown').text(formatted);
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    }
    
    // Credit Calculator
    function initCalculator() {
        const downPaymentSlider = $('#down-payment');
        const loanTermSlider = $('#loan-term');
        const monthlyPayment = $('#monthly-payment');
        
        if (downPaymentSlider.length === 0) return;
        
        function calculatePayment() {
            const carPrice = parseFloat(downPaymentSlider.attr('max'));
            const downPayment = parseFloat(downPaymentSlider.val());
            const loanTerm = parseInt(loanTermSlider.val());
            const annualRate = 0.065; // 6.5%
            
            const loanAmount = carPrice - downPayment;
            const monthlyRate = annualRate / 12;
            
            let payment;
            if (monthlyRate === 0) {
                payment = loanAmount / loanTerm;
            } else {
                payment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, loanTerm)) / 
                         (Math.pow(1 + monthlyRate, loanTerm) - 1);
            }
            
            monthlyPayment.text(Math.round(payment).toLocaleString('ru-RU') + ' ₽');
        }
        
        // Update outputs
        function updateOutputs() {
            const downPaymentValue = parseFloat(downPaymentSlider.val());
            const loanTermValue = parseInt(loanTermSlider.val());
            
            $('output[for="down-payment"]').text(Math.round(downPaymentValue).toLocaleString('ru-RU') + ' ₽');
            $('output[for="loan-term"]').text(loanTermValue + ' мес.');
            
            calculatePayment();
        }
        
        downPaymentSlider.on('input', updateOutputs);
        loanTermSlider.on('input', updateOutputs);
        
        // Initial calculation
        updateOutputs();
    }
    
    // Lead Form
    function initLeadForm() {
        const form = $('#car-lead-form');
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
                car_id: form.data('car-id'),
                car_title: form.data('car-title'),
            };
            
            // Add car info to comment
            if (formData.car_title) {
                const carInfo = 'Интересует автомобиль: ' + formData.car_title;
                formData.comment = formData.comment ? carInfo + '\n\n' + formData.comment : carInfo;
            }
            
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
        initGallery();
        initTabs();
        initCountdown();
        initCalculator();
        initLeadForm();
        initSmoothScroll();
    });
    
})(jQuery);