/**
 * Hero Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { PanelBody, TextControl, Button } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/hero', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Hero Settings', 'auto-import-core') },
                    wp.element.createElement(TextControl, {
                        label: __('Title', 'auto-import-core'),
                        value: attributes.title,
                        onChange: (value) => setAttributes({ title: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: __('Subtitle', 'auto-import-core'),
                        value: attributes.subtitle,
                        onChange: (value) => setAttributes({ subtitle: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: __('Button Text', 'auto-import-core'),
                        value: attributes.buttonText,
                        onChange: (value) => setAttributes({ buttonText: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: __('Button URL', 'auto-import-core'),
                        value: attributes.buttonUrl,
                        onChange: (value) => setAttributes({ buttonUrl: value })
                    }),
                    wp.element.createElement(
                        MediaUploadCheck,
                        null,
                        wp.element.createElement(MediaUpload, {
                            onSelect: (media) => setAttributes({ 
                                backgroundImageUrl: media.url,
                                backgroundImageId: media.id 
                            }),
                            allowedTypes: ['image'],
                            value: attributes.backgroundImageId,
                            render: ({ open }) => wp.element.createElement(
                                Button,
                                { onClick: open, variant: 'secondary' },
                                attributes.backgroundImageUrl ? __('Change Image', 'auto-import-core') : __('Select Image', 'auto-import-core')
                            )
                        })
                    )
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { 
                        className: 'hero',
                        style: { 
                            backgroundImage: attributes.backgroundImageUrl ? `url(${attributes.backgroundImageUrl})` : 'none',
                            minHeight: '300px',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            padding: '40px',
                            color: '#fff',
                            textAlign: 'center'
                        }
                    },
                    wp.element.createElement('div', null,
                        wp.element.createElement('h1', null, attributes.title),
                        wp.element.createElement('p', null, attributes.subtitle),
                        wp.element.createElement('button', { className: 'btn btn--primary' }, attributes.buttonText)
                    )
                )
            )
        ];
    },
    save: () => null
});