/**
 * Car Grid Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl, SelectControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/car-grid', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Car Grid Settings', 'auto-import-core') },
                    wp.element.createElement(RangeControl, {
                        label: __('Posts Per Page', 'auto-import-core'),
                        value: attributes.postsPerPage,
                        onChange: (value) => setAttributes({ postsPerPage: value }),
                        min: 1,
                        max: 24
                    }),
                    wp.element.createElement(SelectControl, {
                        label: __('Order By', 'auto-import-core'),
                        value: attributes.orderBy,
                        options: [
                            { label: 'Date', value: 'date' },
                            { label: 'Price', value: 'price_rub' },
                            { label: 'Year', value: 'year' },
                        ],
                        onChange: (value) => setAttributes({ orderBy: value })
                    }),
                    wp.element.createElement(SelectControl, {
                        label: __('Order', 'auto-import-core'),
                        value: attributes.order,
                        options: [
                            { label: 'Descending', value: 'DESC' },
                            { label: 'Ascending', value: 'ASC' },
                        ],
                        onChange: (value) => setAttributes({ order: value })
                    })
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { className: 'car-grid-placeholder', style: { padding: '40px', background: '#f0f0f0', textAlign: 'center', border: '2px dashed #ccc' } },
                    wp.element.createElement('p', { style: { margin: 0, fontSize: '16px' } },
                        __('Car Grid', 'auto-import-core') + ' (' + attributes.postsPerPage + ' ' + __('items', 'auto-import-core') + ')'
                    )
                )
            )
        ];
    },
    save: () => null
});