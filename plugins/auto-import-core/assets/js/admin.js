(function($) {
    'use strict';
    
    // Gallery Management
    let galleryFrame;
    
    $('#aic-add-gallery-images').on('click', function(e) {
        e.preventDefault();
        
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }
        
        galleryFrame = wp.media({
            title: 'Выберите изображения',
            button: {
                text: 'Добавить в галерею'
            },
            multiple: true
        });
        
        galleryFrame.on('select', function() {
            const selection = galleryFrame.state().get('selection');
            const ids = [];
            
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                
                $('#aic-gallery-images').append(
                    '<div class="aic-gallery-item" data-id="' + attachment.id + '">' +
                    '<img src="' + attachment.sizes.thumbnail.url + '" />' +
                    '<button type="button" class="aic-remove-image">&times;</button>' +
                    '</div>'
                );
            });
            
            updateGalleryField();
        });
        
        galleryFrame.open();
    });
    
    // Remove image from gallery
    $(document).on('click', '.aic-remove-image', function(e) {
        e.preventDefault();
        $(this).closest('.aic-gallery-item').remove();
        updateGalleryField();
    });
    
    // Update hidden gallery field
    function updateGalleryField() {
        const ids = [];
        $('#aic-gallery-images .aic-gallery-item').each(function() {
            ids.push($(this).data('id'));
        });
        $('#gallery').val(ids.join(','));
    }
    
    // Make gallery sortable
    if (typeof $.fn.sortable !== 'undefined') {
        $('#aic-gallery-images').sortable({
            update: function() {
                updateGalleryField();
            }
        });
    }
    
})(jQuery);