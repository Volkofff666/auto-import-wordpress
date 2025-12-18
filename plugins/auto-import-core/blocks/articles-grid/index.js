/**
 * Articles Grid Block
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl, ToggleControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType('aic/articles-grid', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps();
        
        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Articles Grid Settings', 'auto-import-core') },
                    wp.element.createElement(RangeControl, {
                        label: __('Posts Per Page', 'auto-import-core'),
                        value: attributes.postsPerPage,
                        onChange: (value) => setAttributes({ postsPerPage: value }),
                        min: 1,
                        max: 12
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: __('Show Excerpt', 'auto-import-core'),
                        checked: attributes.showExcerpt,
                        onChange: (value) => setAttributes({ showExcerpt: value })
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: __('Show Date', 'auto-import-core'),
                        checked: attributes.showDate,
                        onChange: (value) => setAttributes({ showDate: value })
                    })
                )
            ),
            wp.element.createElement(
                'div',
                blockProps,
                wp.element.createElement(
                    'div',
                    { className: 'articles-grid-placeholder', style: { padding: '40px', background: '#f0f0f0', textAlign: 'center', border: '2px dashed #ccc' } },
                    wp.element.createElement('p', { style: { margin: 0 } },
                        __('Articles Grid', 'auto-import-core') + ' (' + attributes.postsPerPage + ' ' + __('posts', 'auto-import-core') + ')'
                    )
                )
            )
        ];
    },
    save: () => null
});