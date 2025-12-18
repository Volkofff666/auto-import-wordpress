import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

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
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Trust Items', 'auto-import-core')}>
                        {attributes.items.map((item, index) => (
                            <div key={index} style={{ marginBottom: '20px', padding: '10px', border: '1px solid #ddd' }}>
                                <SelectControl
                                    label={__('Icon', 'auto-import-core')}
                                    value={item.icon}
                                    options={[
                                        { label: 'Shield', value: 'shield' },
                                        { label: 'Clock', value: 'clock' },
                                        { label: 'Check', value: 'check' },
                                        { label: 'Star', value: 'star' },
                                        { label: 'Award', value: 'award' },
                                        { label: 'Truck', value: 'truck' },
                                    ]}
                                    onChange={(value) => updateItem(index, 'icon', value)}
                                />
                                <TextControl
                                    label={__('Text', 'auto-import-core')}
                                    value={item.text}
                                    onChange={(value) => updateItem(index, 'text', value)}
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
                            {__('Add Item', 'auto-import-core')}
                        </Button>
                    </PanelBody>
                </InspectorControls>
                
                <div className="trust-bar">
                    {attributes.items.map((item, index) => (
                        <div key={index} className="trust-bar__item">
                            <span>{item.icon}</span>
                            <p>{item.text}</p>
                        </div>
                    ))}
                </div>
            </div>
        );
    },
    save: () => null
});