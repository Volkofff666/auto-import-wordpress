/**
 * Gallery Lightbox
 */

(function() {
  'use strict';
  
  const galleryLinks = document.querySelectorAll('.car-gallery__thumbnail');
  
  if (galleryLinks.length === 0) return;
  
  // Create lightbox element
  const lightbox = document.createElement('div');
  lightbox.className = 'lightbox';
  lightbox.innerHTML = `
    <div class="lightbox__backdrop"></div>
    <div class="lightbox__content">
      <button class="lightbox__close" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      <button class="lightbox__prev" aria-label="Previous">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </button>
      <button class="lightbox__next" aria-label="Next">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </button>
      <img class="lightbox__image" src="" alt="">
    </div>
  `;
  document.body.appendChild(lightbox);
  
  const lightboxImg = lightbox.querySelector('.lightbox__image');
  const closeBtn = lightbox.querySelector('.lightbox__close');
  const prevBtn = lightbox.querySelector('.lightbox__prev');
  const nextBtn = lightbox.querySelector('.lightbox__next');
  const backdrop = lightbox.querySelector('.lightbox__backdrop');
  
  let currentIndex = 0;
  const images = Array.from(galleryLinks).map(link => link.href);
  
  // Open lightbox
  galleryLinks.forEach((link, index) => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      currentIndex = index;
      openLightbox();
    });
  });
  
  function openLightbox() {
    lightboxImg.src = images[currentIndex];
    lightbox.classList.add('lightbox--active');
    document.body.style.overflow = 'hidden';
  }
  
  function closeLightbox() {
    lightbox.classList.remove('lightbox--active');
    document.body.style.overflow = '';
  }
  
  function showPrev() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    lightboxImg.src = images[currentIndex];
  }
  
  function showNext() {
    currentIndex = (currentIndex + 1) % images.length;
    lightboxImg.src = images[currentIndex];
  }
  
  // Event listeners
  closeBtn.addEventListener('click', closeLightbox);
  backdrop.addEventListener('click', closeLightbox);
  prevBtn.addEventListener('click', showPrev);
  nextBtn.addEventListener('click', showNext);
  
  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('lightbox--active')) return;
    
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') showPrev();
    if (e.key === 'ArrowRight') showNext();
  });
  
  // Add lightbox styles
  const style = document.createElement('style');
  style.textContent = `
    .lightbox {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 10000;
      display: none;
      align-items: center;
      justify-content: center;
    }
    .lightbox--active {
      display: flex;
    }
    .lightbox__backdrop {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.9);
    }
    .lightbox__content {
      position: relative;
      max-width: 90%;
      max-height: 90%;
      z-index: 1;
    }
    .lightbox__image {
      max-width: 100%;
      max-height: 90vh;
      display: block;
    }
    .lightbox__close,
    .lightbox__prev,
    .lightbox__next {
      position: absolute;
      background-color: rgba(255, 255, 255, 0.9);
      color: #1F2937;
      border: none;
      border-radius: 50%;
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    .lightbox__close:hover,
    .lightbox__prev:hover,
    .lightbox__next:hover {
      background-color: white;
      transform: scale(1.1);
    }
    .lightbox__close {
      top: 20px;
      right: 20px;
    }
    .lightbox__prev {
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    .lightbox__next {
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    .lightbox__prev:hover,
    .lightbox__next:hover {
      transform: translateY(-50%) scale(1.1);
    }
  `;
  document.head.appendChild(style);
  
})();
