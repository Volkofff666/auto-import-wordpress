/**
 * Lead Form Submission
 */

(function() {
  'use strict';
  
  const forms = document.querySelectorAll('.lead-form, #aic-lead-form');
  
  forms.forEach(form => {
    form.addEventListener('submit', handleSubmit);
  });
  
  async function handleSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('[type="submit"]');
    const messageEl = form.querySelector('.lead-form__message');
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Отправка...';
    
    // Collect form data
    const formData = {
      name: form.querySelector('[name="name"]').value,
      phone: form.querySelector('[name="phone"]').value,
      email: form.querySelector('[name="email"]')?.value || '',
      budget: form.querySelector('[name="budget"]')?.value || '',
      comment: form.querySelector('[name="comment"]')?.value || '',
      source_page: window.location.href
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
      
      if (response.ok) {
        // Success
        showMessage(messageEl, 'success', data.message || 'Спасибо! Мы свяжемся с вами в ближайшее время.');
        form.reset();
        
        // Track conversion (if GTM or analytics present)
        if (typeof gtag !== 'undefined') {
          gtag('event', 'generate_lead', {
            event_category: 'form',
            event_label: 'lead_submission'
          });
        }
      } else {
        // Error
        showMessage(messageEl, 'error', data.message || 'Произошла ошибка. Попробуйте еще раз.');
      }
    } catch (error) {
      console.error('Form submission error:', error);
      showMessage(messageEl, 'error', 'Произошла ошибка. Попробуйте еще раз.');
    } finally {
      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.textContent = 'Отправить';
    }
  }
  
  function showMessage(element, type, message) {
    if (!element) return;
    
    element.textContent = message;
    element.className = `lead-form__message lead-form__message--${type}`;
    element.style.display = 'block';
    
    // Hide after 5 seconds
    setTimeout(() => {
      element.style.display = 'none';
    }, 5000);
  }
  
})();
