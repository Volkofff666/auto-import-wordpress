import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('aic/articles-grid', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Articles Grid Settings', 'auto-import-core')}>
                        <RangeControl
                            label={__('Posts Per Page', 'auto-import-core')}
                            value={attributes.postsPerPage}
                            onChange={(value) => setAttributes({ postsPerPage: value })}
                            min={1}
                            max={12}
                        />
                        <ToggleControl
                            label={__('Show Excerpt', 'auto-import-core')}
                            checked={attributes.showExcerpt}
                            onChange={(value) => setAttributes({ showExcerpt: value })}
                        />
                        <ToggleControl
                            label={__('Show Date', 'auto-import-core')}
                            checked={attributes.showDate}
                            onChange={(value) => setAttributes({ showDate: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                
                <div className="articles-grid-placeholder">
                    <p>{__('Articles Grid', 'auto-import-core')} ({attributes.postsPerPage} {__('posts', 'auto-import-core')})</p>
                </div>
            </div>
        );
    },
    save: () => null
});