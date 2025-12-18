/**
 * Auto Import Core - Admin JavaScript
 */

(function($) {
    'use strict';
    
    // Gallery Manager
    var galleryFrame;
    var galleryItems = $('#car-gallery-items');
    var galleryInput = $('#car-gallery-input');
    
    // Add images
    $('#car-gallery-add').on('click', function(e) {
        e.preventDefault();
        
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }
        
        galleryFrame = wp.media({
            title: 'Выберите фотографии автомобиля',
            button: {
                text: 'Добавить в галерею'
            },
            multiple: true
        });
        
        galleryFrame.on('select', function() {
            var selection = galleryFrame.state().get('selection');
            var ids = galleryInput.val() ? galleryInput.val().split(',') : [];
            
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                
                if (ids.indexOf(String(attachment.id)) === -1) {
                    ids.push(attachment.id);
                    
                    var imgUrl = attachment.sizes && attachment.sizes.thumbnail ? 
                        attachment.sizes.thumbnail.url : attachment.url;
                    
                    galleryItems.append(
                        '<div class="aic-gallery__item" data-id="' + attachment.id + '">' +
                            '<img src="' + imgUrl + '" alt="">' +
                            '<button type="button" class="aic-gallery__remove" title="Удалить">' +
                                '<span class="dashicons dashicons-no"></span>' +
                            '</button>' +
                        '</div>'
                    );
                }
            });
            
            galleryInput.val(ids.join(','));
        });
        
        galleryFrame.open();
    });
    
    // Remove image
    galleryItems.on('click', '.aic-gallery__remove', function(e) {
        e.preventDefault();
        
        if (!confirm('Удалить это фото из галереи?')) {
            return;
        }
        
        var item = $(this).closest('.aic-gallery__item');
        var imageId = item.data('id');
        
        item.fadeOut(200, function() {
            item.remove();
            updateGalleryInput();
        });
    });
    
    // Make gallery sortable
    if ($.fn.sortable) {
        galleryItems.sortable({
            items: '.aic-gallery__item',
            cursor: 'move',
            opacity: 0.7,
            update: function() {
                updateGalleryInput();
            }
        });
    }
    
    function updateGalleryInput() {
        var ids = [];
        galleryItems.find('.aic-gallery__item').each(function() {
            ids.push($(this).data('id'));
        });
        galleryInput.val(ids.join(','));
    }
    
    // VIN validation
    $('input[name="vin"]').on('input', function() {
        var vin = $(this).val().toUpperCase();
        $(this).val(vin);
        
        if (vin.length > 0 && vin.length !== 17) {
            $(this).css('border-color', '#d63638');
        } else {
            $(this).css('border-color', '');
        }
    });
    
    // Year validation
    $('input[name="year"]').on('input', function() {
        var year = parseInt($(this).val());
        var currentYear = new Date().getFullYear();
        
        if (year && (year < 1950 || year > currentYear + 1)) {
            $(this).css('border-color', '#d63638');
        } else {
            $(this).css('border-color', '');
        }
    });
    
    // Price formatting
    $('input[name="price_rub"]').on('blur', function() {
        var value = $(this).val();
        if (value) {
            var formatted = Math.round(value / 1000) * 1000;
            $(this).val(formatted);
        }
    });
    
    // Mileage formatting
    $('input[name="mileage_km"]').on('blur', function() {
        var value = $(this).val();
        if (value) {
            var formatted = Math.round(value / 1000) * 1000;
            $(this).val(formatted);
        }
    });
    
    // Auto-save reminder
    var hasChanges = false;
    $('.aic-metabox input, .aic-metabox select, .aic-metabox textarea').on('change', function() {
        hasChanges = true;
    });
    
    $(window).on('beforeunload', function() {
        if (hasChanges && !$('form#post').hasClass('submitting')) {
            return 'У вас есть несохраненные изменения. Вы уверены, что хотите покинуть страницу?';
        }
    });
    
    $('form#post').on('submit', function() {
        $(this).addClass('submitting');
        hasChanges = false;
    });
    
})(jQuery);