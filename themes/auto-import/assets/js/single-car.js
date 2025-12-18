/**
 * Single Car Page JavaScript
 */

(function() {
    'use strict';
    
    // Gallery Thumbnails
    function initGallery() {
        const thumbs = document.querySelectorAll('.car-gallery__thumb');
        const mainImg = document.querySelector('.car-gallery__main-img');
        const galleryIds = [];
        
        if (!mainImg || thumbs.length === 0) return;
        
        // Collect all image URLs
        thumbs.forEach(function(thumb) {
            const img = thumb.querySelector('img');
            if (img) {
                galleryIds.push(img.src.replace(/-\d+x\d+\./, '.').replace(/\/thumb\//, '/full/'));
            }
        });
        
        // Click handler
        thumbs.forEach(function(thumb, index) {
            thumb.addEventListener('click', function() {
                // Remove active class from all
                thumbs.forEach(t => t.classList.remove('active'));
                
                // Add active to clicked
                thumb.classList.add('active');
                
                // Get full size image
                const thumbImg = thumb.querySelector('img');
                if (thumbImg) {
                    // Extract image ID from thumbnail
                    const thumbSrc = thumbImg.src;
                    // Replace thumbnail size with large size
                    const largeSrc = thumbSrc
                        .replace(/-\d+x\d+\./, '-800x600.')
                        .replace(/\/thumb\//, '/large/');
                    
                    mainImg.src = largeSrc;
                }
            });
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                const activeThumb = document.querySelector('.car-gallery__thumb.active');
                if (!activeThumb) return;
                
                const currentIndex = Array.from(thumbs).indexOf(activeThumb);
                let newIndex;
                
                if (e.key === 'ArrowLeft') {
                    newIndex = currentIndex > 0 ? currentIndex - 1 : thumbs.length - 1;
                } else {
                    newIndex = currentIndex < thumbs.length - 1 ? currentIndex + 1 : 0;
                }
                
                thumbs[newIndex].click();
            }
        });
    }
    
    // Lead Form
    function initLeadForm() {
        const form = document.getElementById('car-lead-form');
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
                name: form.querySelector('#car-lead-name').value,
                phone: form.querySelector('#car-lead-phone').value,
                email: form.querySelector('#car-lead-email').value,
                comment: form.querySelector('#car-lead-comment').value,
                source_page: window.location.href,
                car_id: form.dataset.carId,
                car_title: form.dataset.carTitle,
            };
            
            // Add car info to comment
            if (formData.car_title) {
                const carInfo = 'Интересует автомобиль: ' + formData.car_title;
                formData.comment = formData.comment ? carInfo + '\n\n' + formData.comment : carInfo;
            }
            
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
    
    // Smooth scroll to anchors
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initGallery();
            initLeadForm();
            initSmoothScroll();
        });
    } else {
        initGallery();
        initLeadForm();
        initSmoothScroll();
    }
})();