/**
 * Trust Bar Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, SelectControl, Button } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/trust-bar', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        const updateItem = (index, key, value) => {
            const newItems = [...attributes.items];
            newItems[index][key] = value;
            setAttributes({ items: newItems });
        };
        
        const addItem = () => {
            setAttributes({ 
                items: [...attributes.items, { icon: 'check', text: '' }] 
            });
        };
        
        const removeItem = (index) => {
            const newItems = attributes.items.filter((_, i) => i !== index);
            setAttributes({ items: newItems });
        };
        
        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Trust Items', 'auto-import-core') },
                    attributes.items.map((item, index) => 
                        wp.element.createElement(
                            'div',
                            { key: index, style: { marginBottom: '20px', padding: '10px', border: '1px solid #ddd' } },
                            wp.element.createElement(SelectControl, {
                                label: __('Icon', 'auto-import-core'),
                                value: item.icon,
                                options: [
                                    { label: 'Shield', value: 'shield' },
                                    { label: 'Clock', value: 'clock' },
                                    { label: 'Check', value: 'check' },
                                    { label: 'Star', value: 'star' },
                                    { label: 'Award', value: 'award' },
                                    { label: 'Truck', value: 'truck' },
                                ],
                                onChange: (value) => updateItem(index, 'icon', value)
                            }),
                            wp.element.createElement(TextControl, {
                                label: __('Text', 'auto-import-core'),
                                value: item.text,
                                onChange: (value) => updateItem(index, 'text', value)
                            }),
                            wp.element.createElement(
                                Button,
                                { 
                                    isDestructive: true,
                                    onClick: () => removeItem(index)
                                },
                                __('Remove', 'auto-import-core')
                            )
                        )
                    ),
                    wp.element.createElement(
                        Button,
                        { isPrimary: true, onClick: addItem },
                        __('Add Item', 'auto-import-core')
                    )
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { className: 'trust-bar', style: { padding: '20px', background: '#f9f9f9' } },
                    wp.element.createElement(
                        'div',
                        { style: { display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '20px' } },
                        attributes.items.map((item, index) => 
                            wp.element.createElement(
                                'div',
                                { key: index, className: 'trust-bar__item', style: { textAlign: 'center' } },
                                wp.element.createElement('div', { style: { fontSize: '24px', marginBottom: '10px' } }, item.icon),
                                wp.element.createElement('p', null, item.text)
                            )
                        )
                    )
                )
            )
        ];
    },
    save: () => null
});