/**
 * FAQ Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/faq', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        const updateItem = (index, key, value) => {
            const newItems = [...attributes.items];
            newItems[index][key] = value;
            setAttributes({ items: newItems });
        };
        
        const addItem = () => {
            setAttributes({ 
                items: [...attributes.items, { question: '', answer: '' }] 
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
                    { title: __('FAQ Settings', 'auto-import-core') },
                    wp.element.createElement(TextControl, {
                        label: __('Title', 'auto-import-core'),
                        value: attributes.title,
                        onChange: (value) => setAttributes({ title: value })
                    })
                ),
                wp.element.createElement(
                    PanelBody,
                    { title: __('FAQ Items', 'auto-import-core'), initialOpen: false },
                    attributes.items.map((item, index) => 
                        wp.element.createElement(
                            'div',
                            { key: index, style: { marginBottom: '20px', padding: '10px', border: '1px solid #ddd' } },
                            wp.element.createElement(TextControl, {
                                label: __('Question', 'auto-import-core'),
                                value: item.question,
                                onChange: (value) => updateItem(index, 'question', value)
                            }),
                            wp.element.createElement(TextareaControl, {
                                label: __('Answer', 'auto-import-core'),
                                value: item.answer,
                                onChange: (value) => updateItem(index, 'answer', value),
                                rows: 3
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
                        __('Add Question', 'auto-import-core')
                    )
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { className: 'faq-placeholder', style: { padding: '20px', background: '#f9f9f9' } },
                    wp.element.createElement('h3', { style: { textAlign: 'center' } }, attributes.title),
                    attributes.items.map((item, index) => 
                        wp.element.createElement(
                            'div',
                            { key: index, className: 'faq-item', style: { marginBottom: '15px', padding: '15px', background: '#fff', border: '1px solid #ddd' } },
                            wp.element.createElement('strong', null, item.question),
                            wp.element.createElement('p', { style: { marginTop: '10px', marginBottom: 0 } }, item.answer)
                        )
                    )
                )
            )
        ];
    },
    save: () => null
});