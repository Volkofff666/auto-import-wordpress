import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('auto-import/hero', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Hero Settings', 'auto-import-core')}>
                        <TextControl
                            label={__('Title', 'auto-import-core')}
                            value={attributes.title}
                            onChange={(val) => setAttributes({ title: val })}
                        />
                        <TextControl
                            label={__('Subtitle', 'auto-import-core')}
                            value={attributes.subtitle}
                            onChange={(val) => setAttributes({ subtitle: val })}
                        />
                        <TextControl
                            label={__('Button Text', 'auto-import-core')}
                            value={attributes.buttonText}
                            onChange={(val) => setAttributes({ buttonText: val })}
                        />
                        <TextControl
                            label={__('Button URL', 'auto-import-core')}
                            value={attributes.buttonUrl}
                            onChange={(val) => setAttributes({ buttonUrl: val })}
                        />
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={(media) => setAttributes({ backgroundImageId: media.id })}
                                allowedTypes={['image']}
                                value={attributes.backgroundImageId}
                                render={({ open }) => (
                                    <Button onClick={open} variant="secondary">
                                        {__('Select Background Image', 'auto-import-core')}
                                    </Button>
                                )}
                            />
                        </MediaUploadCheck>
                    </PanelBody>
                </InspectorControls>
                <div style={{ padding: '40px', background: '#f5f5f5', textAlign: 'center' }}>
                    <h2>{attributes.title}</h2>
                    <p>{attributes.subtitle}</p>
                    <button className="button button-primary">{attributes.buttonText}</button>
                </div>
            </div>
        );
    }
});