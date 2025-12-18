/**
 * Theme JavaScript
 */

(function() {
    'use strict';
    
    // Mobile menu toggle
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('.nav');
        
        if (!menuToggle || !nav) return;
        
        menuToggle.addEventListener('click', () => {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('is-active');
        });
    }
    
    // Car gallery
    function initCarGallery() {
        const mainImage = document.getElementById('car-gallery-main');
        const thumbs = document.querySelectorAll('.car-gallery__thumb');
        
        if (!mainImage || thumbs.length === 0) return;
        
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                // Remove active class from all thumbs
                thumbs.forEach(t => t.classList.remove('is-active'));
                
                // Add active class to clicked thumb
                thumb.classList.add('is-active');
                
                // Get image ID and update main image
                const imageId = thumb.dataset.imageId;
                const img = thumb.querySelector('img');
                
                if (img) {
                    const mainImg = mainImage.querySelector('img');
                    if (mainImg) {
                        // Get large version of image
                        const largeSrc = img.src.replace('-150x150', '-800x600');
                        mainImg.src = largeSrc;
                        mainImg.alt = img.alt;
                    }
                }
            });
        });
    }
    
    // Smooth scroll to anchors
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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
    
    // Price range inputs synchronization
    function initPriceRange() {
        const priceMin = document.querySelector('input[name="price_min"]');
        const priceMax = document.querySelector('input[name="price_max"]');
        
        if (!priceMin || !priceMax) return;
        
        priceMin.addEventListener('change', () => {
            const min = parseInt(priceMin.value) || 0;
            const max = parseInt(priceMax.value) || 0;
            
            if (max > 0 && min > max) {
                priceMin.value = max;
            }
        });
        
        priceMax.addEventListener('change', () => {
            const min = parseInt(priceMin.value) || 0;
            const max = parseInt(priceMax.value) || 0;
            
            if (min > 0 && max > 0 && max < min) {
                priceMax.value = min;
            }
        });
    }
    
    // Year range inputs synchronization
    function initYearRange() {
        const yearMin = document.querySelector('input[name="year_min"]');
        const yearMax = document.querySelector('input[name="year_max"]');
        
        if (!yearMin || !yearMax) return;
        
        yearMin.addEventListener('change', () => {
            const min = parseInt(yearMin.value) || 0;
            const max = parseInt(yearMax.value) || 0;
            
            if (max > 0 && min > max) {
                yearMin.value = max;
            }
        });
        
        yearMax.addEventListener('change', () => {
            const min = parseInt(yearMin.value) || 0;
            const max = parseInt(yearMax.value) || 0;
            
            if (min > 0 && max > 0 && max < min) {
                yearMax.value = min;
            }
        });
    }
    
    // Initialize all functions on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initMobileMenu();
            initCarGallery();
            initSmoothScroll();
            initPriceRange();
            initYearRange();
        });
    } else {
        initMobileMenu();
        initCarGallery();
        initSmoothScroll();
        initPriceRange();
        initYearRange();
    }
    
})();