/**
 * Lead Form Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/lead-form', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Form Settings', 'auto-import-core') },
                    wp.element.createElement(TextControl, {
                        label: __('Title', 'auto-import-core'),
                        value: attributes.title,
                        onChange: (value) => setAttributes({ title: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: __('Subtitle', 'auto-import-core'),
                        value: attributes.subtitle,
                        onChange: (value) => setAttributes({ subtitle: value })
                    })
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { className: 'lead-form-placeholder', style: { padding: '40px', background: '#f9f9f9', textAlign: 'center', border: '1px solid #ddd' } },
                    wp.element.createElement('h3', null, attributes.title),
                    wp.element.createElement('p', null, attributes.subtitle),
                    wp.element.createElement('p', { style: { color: '#666' } }, __('Lead form will appear here', 'auto-import-core'))
                )
            )
        ];
    },
    save: () => null
});