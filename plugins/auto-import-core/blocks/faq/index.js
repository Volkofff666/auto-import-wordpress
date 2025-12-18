import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

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
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('FAQ Settings', 'auto-import-core')}>
                        <TextControl
                            label={__('Title', 'auto-import-core')}
                            value={attributes.title}
                            onChange={(value) => setAttributes({ title: value })}
                        />
                    </PanelBody>
                    <PanelBody title={__('FAQ Items', 'auto-import-core')} initialOpen={false}>
                        {attributes.items.map((item, index) => (
                            <div key={index} style={{ marginBottom: '20px', padding: '10px', border: '1px solid #ddd' }}>
                                <TextControl
                                    label={__('Question', 'auto-import-core')}
                                    value={item.question}
                                    onChange={(value) => updateItem(index, 'question', value)}
                                />
                                <TextareaControl
                                    label={__('Answer', 'auto-import-core')}
                                    value={item.answer}
                                    onChange={(value) => updateItem(index, 'answer', value)}
                                    rows={3}
                                />
                                <Button 
                                    isDestructive 
                                    onClick={() => removeItem(index)}
                                >
                                    {__('Remove', 'auto-import-core')}
                                </Button>
                            </div>
                        ))}
                        <Button isPrimary onClick={addItem}>
                            {__('Add Question', 'auto-import-core')}
                        </Button>
                    </PanelBody>
                </InspectorControls>
                
                <div className="faq-placeholder">
                    <h3>{attributes.title}</h3>
                    {attributes.items.map((item, index) => (
                        <div key={index} className="faq-item">
                            <strong>{item.question}</strong>
                            <p>{item.answer}</p>
                        </div>
                    ))}
                </div>
            </div>
        );
    },
    save: () => null
});