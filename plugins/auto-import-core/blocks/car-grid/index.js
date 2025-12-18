import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('aic/car-grid', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Car Grid Settings', 'auto-import-core')}>
                        <RangeControl
                            label={__('Posts Per Page', 'auto-import-core')}
                            value={attributes.postsPerPage}
                            onChange={(value) => setAttributes({ postsPerPage: value })}
                            min={1}
                            max={24}
                        />
                        <SelectControl
                            label={__('Order By', 'auto-import-core')}
                            value={attributes.orderBy}
                            options={[
                                { label: 'Date', value: 'date' },
                                { label: 'Price', value: 'price_rub' },
                                { label: 'Year', value: 'year' },
                            ]}
                            onChange={(value) => setAttributes({ orderBy: value })}
                        />
                        <SelectControl
                            label={__('Order', 'auto-import-core')}
                            value={attributes.order}
                            options={[
                                { label: 'Descending', value: 'DESC' },
                                { label: 'Ascending', value: 'ASC' },
                            ]}
                            onChange={(value) => setAttributes({ order: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                
                <div className="car-grid-placeholder">
                    <p>{__('Car Grid', 'auto-import-core')} ({attributes.postsPerPage} {__('items', 'auto-import-core')})</p>
                </div>
            </div>
        );
    },
    save: () => null
});