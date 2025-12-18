/**
 * Lead Form Handler
 */

(function() {
    'use strict';
    
    function initLeadForm() {
        const form = document.getElementById('home-lead-form');
        if (!form) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const messageEl = form.querySelector('.form-message');
            const originalBtnText = submitBtn.textContent;
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Отправка...';
            
            // Hide previous message
            messageEl.style.display = 'none';
            messageEl.className = 'form-message';
            
            // Collect form data
            const formData = {
                name: form.querySelector('#lead-name').value,
                phone: form.querySelector('#lead-phone').value,
                email: form.querySelector('#lead-email').value,
                budget: form.querySelector('#lead-budget').value,
                comment: form.querySelector('#lead-comment').value,
                source_page: window.location.href
            };
            
            try {
                // Send to WordPress REST API
                const response = await fetch('/wp-json/aic/v1/leads', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Success
                    messageEl.className = 'form-message success';
                    messageEl.textContent = 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.';
                    messageEl.style.display = 'block';
                    
                    // Reset form
                    form.reset();
                    
                    // Scroll to message
                    messageEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    // Error
                    throw new Error(data.message || 'Ошибка при отправке заявки');
                }
            } catch (error) {
                // Error
                messageEl.className = 'form-message error';
                messageEl.textContent = 'Произошла ошибка: ' + error.message + '. Пожалуйста, попробуйте позже или позвоните нам.';
                messageEl.style.display = 'block';
            } finally {
                // Enable button
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLeadForm);
    } else {
        initLeadForm();
    }
})();