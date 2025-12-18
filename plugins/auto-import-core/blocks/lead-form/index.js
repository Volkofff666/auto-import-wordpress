import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('aic/lead-form', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Form Settings', 'auto-import-core')}>
                        <TextControl
                            label={__('Title', 'auto-import-core')}
                            value={attributes.title}
                            onChange={(value) => setAttributes({ title: value })}
                        />
                        <TextControl
                            label={__('Subtitle', 'auto-import-core')}
                            value={attributes.subtitle}
                            onChange={(value) => setAttributes({ subtitle: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                
                <div className="lead-form-placeholder">
                    <h3>{attributes.title}</h3>
                    <p>{attributes.subtitle}</p>
                    <p>{__('Lead form will appear here', 'auto-import-core')}</p>
                </div>
            </div>
        );
    },
    save: () => null
});