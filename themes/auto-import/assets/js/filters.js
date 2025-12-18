/**
 * Catalog Filters with AJAX
 */

(function() {
  'use strict';
  
  const filtersForm = document.getElementById('catalog-filters');
  const catalogGrid = document.querySelector('.car-grid');
  const sortSelect = document.getElementById('sort');
  
  if (!filtersForm || !catalogGrid) return;
  
  // Handle filter changes
  filtersForm.addEventListener('change', debounce(handleFilterChange, 300));
  
  if (sortSelect) {
    sortSelect.addEventListener('change', handleFilterChange);
  }
  
  function handleFilterChange(e) {
    const formData = new FormData(filtersForm);
    const params = new URLSearchParams(formData);
    
    // Add sort parameter
    if (sortSelect) {
      params.set('orderby', sortSelect.value);
    }
    
    // Update URL without reload
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({}, '', newUrl);
    
    // Show loading state
    catalogGrid.classList.add('is-loading');
    
    // Fetch filtered results
    fetch(newUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(html => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newGrid = doc.querySelector('.car-grid');
      
      if (newGrid) {
        catalogGrid.innerHTML = newGrid.innerHTML;
      }
      
      catalogGrid.classList.remove('is-loading');
    })
    .catch(error => {
      console.error('Filter error:', error);
      catalogGrid.classList.remove('is-loading');
    });
  }
  
  // Price range inputs
  const minPriceInput = document.getElementById('min_price');
  const maxPriceInput = document.getElementById('max_price');
  
  if (minPriceInput && maxPriceInput) {
    minPriceInput.addEventListener('input', function() {
      if (maxPriceInput.value && parseInt(this.value) > parseInt(maxPriceInput.value)) {
        this.value = maxPriceInput.value;
      }
    });
    
    maxPriceInput.addEventListener('input', function() {
      if (minPriceInput.value && parseInt(this.value) < parseInt(minPriceInput.value)) {
        this.value = minPriceInput.value;
      }
    });
  }
  
  // Reset filters button
  const resetBtn = document.getElementById('reset-filters');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      filtersForm.reset();
      handleFilterChange();
    });
  }
  
  // Debounce helper
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  
})();
