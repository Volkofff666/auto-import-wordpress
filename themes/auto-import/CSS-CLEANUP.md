# CSS Cleanup Notes

## Old CSS files (to be removed in next commit):
- archive-car.css → replaced by layout.css + components.css
- single-car.css → replaced by layout.css + components.css  
- front-page.css → replaced by blocks.css
- home-page.css → replaced by blocks.css
- pages.css → replaced by blocks.css + layout.css
- components-extended.css → merged into components.css

## New CSS Architecture (v2.0.0):

### Core Files (7 files):
1. **tokens.css** - Design tokens (colors, spacing, typography)
2. **reset.css** - Modern CSS reset
3. **base.css** - Base typography and elements
4. **components.css** - BEM components (buttons, cards, forms)
5. **layout.css** - Layout system (grid, container, header, footer)
6. **blocks.css** - Gutenberg blocks styling
7. **utilities.css** - Helper/utility classes

### Import Order (in style.css):
```css
@import url('assets/css/tokens.css');
@import url('assets/css/reset.css');
@import url('assets/css/base.css');
@import url('assets/css/components.css');
@import url('assets/css/layout.css');
@import url('assets/css/blocks.css');
@import url('assets/css/utilities.css');
```

## Benefits:
- Modular structure (easy to maintain)
- BEM methodology
- Mobile-first
- No duplicate styles
- Better performance (smaller file sizes)
