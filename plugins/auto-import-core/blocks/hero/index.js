import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('aic/hero', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Hero Settings', 'auto-import-core')}>
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
                        <TextControl
                            label={__('Button Text', 'auto-import-core')}
                            value={attributes.buttonText}
                            onChange={(value) => setAttributes({ buttonText: value })}
                        />
                        <TextControl
                            label={__('Button URL', 'auto-import-core')}
                            value={attributes.buttonUrl}
                            onChange={(value) => setAttributes({ buttonUrl: value })}
                        />
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={(media) => setAttributes({ 
                                    backgroundImageUrl: media.url,
                                    backgroundImageId: media.id 
                                })}
                                allowedTypes={['image']}
                                value={attributes.backgroundImageId}
                                render={({ open }) => (
                                    <Button onClick={open} variant="secondary">
                                        {attributes.backgroundImageUrl ? __('Change Image', 'auto-import-core') : __('Select Image', 'auto-import-core')}
                                    </Button>
                                )}
                            />
                        </MediaUploadCheck>
                    </PanelBody>
                </InspectorControls>
                
                <div className="hero" style={{ backgroundImage: attributes.backgroundImageUrl ? `url(${attributes.backgroundImageUrl})` : 'none' }}>
                    <h1>{attributes.title}</h1>
                    <p>{attributes.subtitle}</p>
                    <button className="btn btn--primary">{attributes.buttonText}</button>
                </div>
            </div>
        );
    },
    save: () => null
});