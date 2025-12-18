// Frontend JavaScript for blocks

(function() {
    'use strict';
    
    // FAQ Accordion
    function initFAQ() {
        const faqItems = document.querySelectorAll('[data-faq-item]');
        
        faqItems.forEach(item => {
            const trigger = item.querySelector('[data-faq-trigger]');
            const content = item.querySelector('[data-faq-content]');
            
            if (!trigger || !content) return;
            
            trigger.addEventListener('click', () => {
                const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
                
                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        const otherTrigger = otherItem.querySelector('[data-faq-trigger]');
                        const otherContent = otherItem.querySelector('[data-faq-content]');
                        
                        otherTrigger.setAttribute('aria-expanded', 'false');
                        otherContent.style.maxHeight = null;
                        otherItem.classList.remove('is-active');
                    }
                });
                
                // Toggle current item
                if (isExpanded) {
                    trigger.setAttribute('aria-expanded', 'false');
                    content.style.maxHeight = null;
                    item.classList.remove('is-active');
                } else {
                    trigger.setAttribute('aria-expanded', 'true');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    item.classList.add('is-active');
                }
            });
        });
    }
    
    // Lead Form Submission
    function initLeadForms() {
        const forms = document.querySelectorAll('[data-form="lead"]');
        
        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const messageEl = form.querySelector('.lead-form__message');
                const submitBtn = form.querySelector('[type="submit"]');
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.textContent = 'Отправка...';
                
                try {
                    const response = await fetch('/wp-json/aic/v1/leads', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: formData.get('name'),
                            phone: formData.get('phone'),
                            budget: formData.get('budget'),
                            comment: formData.get('comment'),
                            source_page: window.location.href
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        messageEl.textContent = data.message;
                        messageEl.className = 'lead-form__message lead-form__message--success';
                        messageEl.style.display = 'block';
                        form.reset();
                    } else {
                        throw new Error(data.message || 'Ошибка отправки');
                    }
                } catch (error) {
                    messageEl.textContent = error.message || 'Произошла ошибка. Попробуйте позже.';
                    messageEl.className = 'lead-form__message lead-form__message--error';
                    messageEl.style.display = 'block';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Отправить заявку';
                    
                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageEl.style.display = 'none';
                    }, 5000);
                }
            });
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initFAQ();
            initLeadForms();
        });
    } else {
        initFAQ();
        initLeadForms();
    }
    
})();