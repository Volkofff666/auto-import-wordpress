# Smoke Test Checklist - Auto Import WordPress v2.0.0

**Run this checklist after installation to verify everything works correctly.**

---

## ✅ PRE-FLIGHT CHECKS

### Environment
- [ ] WordPress version 6.0+
- [ ] PHP version 7.4+
- [ ] MySQL version 5.7+
- [ ] HTTPS enabled (recommended)
- [ ] Debug mode enabled for testing: `WP_DEBUG = true`

---

## 🔌 PLUGIN ACTIVATION

### Installation
- [ ] Plugin files copied to `/wp-content/plugins/auto-import-core/`
- [ ] Theme files copied to `/wp-content/themes/auto-import/`
- [ ] File permissions correct (755 folders, 644 files)

### Activation
- [ ] Plugin activates without fatal errors
- [ ] No PHP warnings/notices in `debug.log`
- [ ] Admin notices appear (if any) are informational only

### Deactivation/Reactivation
- [ ] Plugin deactivates cleanly
- [ ] Plugin reactivates without errors
- [ ] Data persists after deactivation

---

## 🎨 THEME ACTIVATION

### Activation
- [ ] Theme appears in **Appearance → Themes**
- [ ] Theme activates without errors
- [ ] No console errors on frontend (F12)
- [ ] No 404 errors for CSS/JS files

### Style Check
- [ ] Header displays correctly
- [ ] Footer displays correctly
- [ ] Typography loads (Inter font)
- [ ] Colors match design system
- [ ] No emoji visible
- [ ] SVG icons display

---

## 📋 ADMIN MENU ITEMS

### Custom Post Types
- [ ] **Автомобили** menu visible
  - [ ] All Cars
  - [ ] Add New
  - [ ] Brands (taxonomy)
  - [ ] Models (taxonomy)
  - [ ] Body Types (taxonomy)
  - [ ] Fuel Types (taxonomy)
  - [ ] Transmissions (taxonomy)
  - [ ] Drive Types (taxonomy)
  - [ ] Statuses (taxonomy)
  - [ ] Locations (taxonomy)

- [ ] **Заявки** menu visible
  - [ ] All Leads
  - [ ] Add New

- [ ] **Статьи** menu visible
  - [ ] All Articles
  - [ ] Add New
  - [ ] Categories
  - [ ] Tags

### Settings
- [ ] **Auto Import** menu visible
- [ ] Settings page loads without errors
- [ ] All tabs present: Company, Email, Catalog, Forms

---

## 🚗 CAR POST TYPE

### Create Car
- [ ] Can create new car: **Автомобили → Add New**
- [ ] Title field works
- [ ] Content editor works
- [ ] All meta boxes display:
  - [ ] Basic Information
  - [ ] Technical Specifications
  - [ ] Documents
  - [ ] Gallery
  - [ ] Publish Settings

### Basic Information Meta Box
- [ ] Price field accepts numbers
- [ ] Year field accepts 4-digit year
- [ ] Mileage field accepts numbers
- [ ] VIN field accepts alphanumeric
- [ ] Color field works
- [ ] Steering wheel dropdown works
- [ ] Owners field accepts numbers

### Technical Specifications
- [ ] Engine volume field
- [ ] Engine power field
- [ ] Transmission dropdown
- [ ] Drive type dropdown
- [ ] Fuel type dropdown

### Documents
- [ ] Customs cleared checkbox
- [ ] Title status field
- [ ] Condition textarea

### Gallery
- [ ] Can upload multiple images
- [ ] Can reorder images (drag-drop)
- [ ] Can delete images
- [ ] Images save correctly

### Taxonomies
- [ ] Can select brand
- [ ] Can select model
- [ ] Can select body type
- [ ] Can select fuel type
- [ ] Can select transmission
- [ ] Can select drive type
- [ ] Can select status
- [ ] Can select location

### Publish Settings
- [ ] "Show in catalog" checkbox visible
- [ ] Featured image can be set

### Save & Publish
- [ ] Save Draft works
- [ ] Publish works
- [ ] All data persists after save
- [ ] No errors in console/debug.log

---

## 📝 LEAD POST TYPE

### View Leads
- [ ] Can view all leads: **Заявки → All Leads**
- [ ] Custom columns display:
  - [ ] Name
  - [ ] Phone
  - [ ] Email
  - [ ] Budget
  - [ ] Source Page
  - [ ] Status
  - [ ] Date

### Edit Lead
- [ ] Can open lead for editing
- [ ] Lead details meta box displays
- [ ] Can change status dropdown
- [ ] Can add manager notes
- [ ] Save works correctly

### Filters
- [ ] Status filter dropdown works
- [ ] Can filter by: New, In Progress, Converted, Lost
- [ ] Date filter works

---

## 🎛️ ADMIN COLUMNS & FILTERS

### Car List Columns
- [ ] Thumbnail column shows image
- [ ] Brand/Model column shows correct data
- [ ] Year column displays
- [ ] Price column displays formatted
- [ ] Status column shows badge
- [ ] Location column displays

### Car List Filters
- [ ] Brand filter dropdown populated
- [ ] Status filter dropdown populated
- [ ] Year filter dropdown populated
- [ ] Filters update results correctly

### Bulk Actions
- [ ] Can select multiple cars
- [ ] Bulk edit works
- [ ] Bulk delete works (moves to trash)

---

## 🧱 GUTENBERG BLOCKS

### Block Availability
- [ ] Create test page: **Pages → Add New**
- [ ] Click `+` to add block
- [ ] "Auto Import Blocks" category visible
- [ ] All 6 blocks present:
  - [ ] Hero Section
  - [ ] Trust Bar
  - [ ] Car Grid
  - [ ] Lead Form
  - [ ] Articles Grid
  - [ ] FAQ

### Hero Section Block
- [ ] Block inserts without error
- [ ] Title field editable
- [ ] Subtitle field editable
- [ ] Button text editable
- [ ] Button URL editable
- [ ] Background image uploader works
- [ ] Preview shows correctly in editor

### Trust Bar Block
- [ ] Block inserts without error
- [ ] Title editable
- [ ] Can add items (icon, title, text)
- [ ] Can reorder items
- [ ] Can delete items
- [ ] Preview shows correctly

### Car Grid Block
- [ ] Block inserts without error
- [ ] Settings panel appears
- [ ] "Number of cars" control works
- [ ] "Columns" control works
- [ ] "Order by" dropdown works
- [ ] "Show filters" toggle works
- [ ] Preview loads cars (if exist)

### Lead Form Block
- [ ] Block inserts without error
- [ ] Title editable
- [ ] Subtitle editable
- [ ] "Show budget field" toggle works
- [ ] Button text editable
- [ ] Preview shows form

### Articles Grid Block
- [ ] Block inserts without error
- [ ] "Number of posts" control works
- [ ] "Columns" control works
- [ ] Preview loads posts (if exist)

### FAQ Block
- [ ] Block inserts without error
- [ ] Can add FAQ items
- [ ] Can edit question/answer
- [ ] Can reorder items
- [ ] Can delete items
- [ ] Preview shows accordion

### Block Saving
- [ ] Page saves without errors
- [ ] Blocks persist after reload
- [ ] No console errors

---

## 🌐 FRONTEND - HOMEPAGE

### Page Creation
- [ ] Create homepage with blocks
- [ ] Publish page
- [ ] Set as homepage: **Settings → Reading**
- [ ] Visit homepage (not logged in)

### Block Rendering
- [ ] Hero section displays correctly
- [ ] Trust bar displays with icons
- [ ] Car grid displays cars (if created)
- [ ] Lead form displays and is functional
- [ ] Articles grid displays posts (if created)
- [ ] FAQ displays with accordion functionality

### Visual Check
- [ ] No layout breaks
- [ ] Colors match design system
- [ ] Typography correct (Inter font)
- [ ] SVG icons display (no emoji)
- [ ] Images load correctly
- [ ] Spacing consistent

---

## 🚗 FRONTEND - CATALOG PAGE

### Page Access
- [ ] Visit `/cars/` or click catalog menu
- [ ] Page loads without errors
- [ ] No 404 errors in console

### Car Display
- [ ] Cars display in grid layout
- [ ] Car cards show:
  - [ ] Image
  - [ ] Title (brand + model + year)
  - [ ] Price
  - [ ] Year
  - [ ] Mileage
  - [ ] Key specs (engine, transmission, drive)
  - [ ] Status badge
  - [ ] Location
  - [ ] "View Details" button

### Filters Sidebar
- [ ] Filters sidebar visible (desktop)
- [ ] Price range slider works
- [ ] Year range slider works
- [ ] Brand dropdown populated
- [ ] Model dropdown populated
- [ ] Body type checkboxes work
- [ ] Fuel type checkboxes work
- [ ] Transmission checkboxes work
- [ ] Drive type checkboxes work
- [ ] Status checkboxes work
- [ ] Location checkboxes work

### Filter Functionality
- [ ] Selecting filter updates URL params
- [ ] Results update on filter change
- [ ] Results count displays correctly
- [ ] "Reset filters" button works
- [ ] No page reload (AJAX - if enabled)

### Sorting
- [ ] Sort dropdown visible
- [ ] Sort options:
  - [ ] Price: Low to High
  - [ ] Price: High to Low
  - [ ] Year: Newest First
  - [ ] Year: Oldest First
  - [ ] Date: Newest First
- [ ] Sorting updates results

### Pagination
- [ ] Pagination displays (if >12 cars)
- [ ] Page 2 link works
- [ ] URL updates: `/cars/page/2/`
- [ ] Correct cars display on page 2

### Mobile Responsive
- [ ] Resize to 360px width
- [ ] Filters collapse to button/drawer
- [ ] Car grid becomes 1 column
- [ ] Cards display correctly
- [ ] No horizontal scroll

---

## 🚙 FRONTEND - SINGLE CAR PAGE

### Page Access
- [ ] Click car card from catalog
- [ ] Single car page loads
- [ ] URL format: `/cars/car-name/`
- [ ] No errors in console

### Content Sections
- [ ] **Hero Section:**
  - [ ] Main image displays
  - [ ] Car title displays
  - [ ] Price displays
  - [ ] Key specs display
  - [ ] Status badge displays

- [ ] **Specifications Table:**
  - [ ] All specs display in 2-column layout
  - [ ] Year, mileage, VIN, color, etc.
  - [ ] Engine, transmission, drive, fuel
  - [ ] No empty fields (or properly hidden)

- [ ] **Gallery:**
  - [ ] All car images display in grid
  - [ ] Images lazy load
  - [ ] Click image opens lightbox (if enabled)
  - [ ] Lightbox navigation works

- [ ] **Equipment List:**
  - [ ] Features display as list
  - [ ] Each item has checkmark icon
  - [ ] List is readable

- [ ] **Lead Form:**
  - [ ] Form displays
  - [ ] All fields present: name, phone, email, budget, comment
  - [ ] Submit button visible

- [ ] **Related Cars:**
  - [ ] Section displays (if related cars exist)
  - [ ] Shows 3-4 cars from same brand
  - [ ] Car cards functional

### Breadcrumbs
- [ ] Breadcrumbs display at top
- [ ] Format: Home > Catalog > Brand > Car Name
- [ ] Links work correctly

### Schema.org Markup
- [ ] View page source
- [ ] Find `<script type="application/ld+json">`
- [ ] Product schema present
- [ ] Validate at: https://search.google.com/test/rich-results
- [ ] No errors in validation

### Mobile Responsive
- [ ] Resize to 360px width
- [ ] Hero image scales correctly
- [ ] Specs table stacks on mobile
- [ ] Gallery becomes single column
- [ ] Form displays correctly
- [ ] No horizontal scroll

---

## 📮 LEAD FORM SUBMISSION

### Frontend Form
- [ ] Fill in all fields:
  - Name: Test User
  - Phone: +7 999 123-45-67
  - Email: test@example.com
  - Budget: 2000000
  - Comment: Test comment

### Submit Test
- [ ] Click "Submit" button
- [ ] Form shows loading state
- [ ] Success message displays
- [ ] Form resets after success

### Backend Verification
- [ ] Go to **Заявки → All Leads**
- [ ] New lead appears in list
- [ ] Lead details correct:
  - [ ] Name matches
  - [ ] Phone matches
  - [ ] Email matches
  - [ ] Budget matches
  - [ ] Comment matches
  - [ ] Source page URL saved
  - [ ] Status is "New"

### Email Notification
- [ ] Check notification email inbox
- [ ] Email received
- [ ] Subject line correct
- [ ] Email contains lead details
- [ ] Link to admin panel works

### Spam Protection
- [ ] Honeypot field exists in form HTML (hidden)
- [ ] Rapid submission blocked (if rate limiting enabled)

---

## ⚙️ SETTINGS PAGE

### Access
- [ ] Go to **Auto Import → Settings**
- [ ] Page loads without errors
- [ ] All tabs present

### Company Tab
- [ ] Company name field
- [ ] Phone field
- [ ] Email field
- [ ] Address textarea
- [ ] Save button works
- [ ] Success notice displays
- [ ] Data persists after reload

### Email Tab
- [ ] Notification email field
- [ ] Subject template field
- [ ] Enable notifications checkbox
- [ ] Test email button (if exists)
- [ ] Save works

### Catalog Tab
- [ ] Cars per page number field
- [ ] Default sort dropdown
- [ ] Show filters checkbox
- [ ] Save works

### Forms Tab
- [ ] Button text field
- [ ] Placeholder texts
- [ ] Success message text
- [ ] Error message text
- [ ] Save works

---

## 🎨 DESIGN SYSTEM COMPLIANCE

### Colors
- [ ] Primary blue: #0066FF (links, buttons)
- [ ] Secondary green: #10B981 (success states)
- [ ] Accent orange: #FF6B35 (highlights)
- [ ] Text dark: #1F2937 (body text)
- [ ] Text light: #6B7280 (secondary text)
- [ ] Border: #E5E7EB (dividers)

### Typography
- [ ] Font family: Inter (loaded from Google Fonts)
- [ ] Headings: Bold weight (700)
- [ ] Body: Regular weight (400)
- [ ] Line height: 1.6 for body text

### Spacing
- [ ] All spacing uses 4px scale
- [ ] No arbitrary values (5px, 13px, etc.)
- [ ] Consistent padding in components

### Components
- [ ] Buttons follow BEM: `.btn`, `.btn--primary`
- [ ] Cards follow BEM: `.car-card`, `.car-card__image`
- [ ] No inline styles in HTML
- [ ] Reusable classes used

### Icons
- [ ] No emoji anywhere
- [ ] All icons are SVG
- [ ] Icons have proper `aria-label` or `aria-hidden="true"`
- [ ] Icons scale correctly

---

## 📱 RESPONSIVE DESIGN

### Mobile (360px - 767px)
- [ ] Visit site on mobile device or resize browser
- [ ] No horizontal scroll
- [ ] Text readable (not too small)
- [ ] Buttons touch-friendly (44x44px minimum)
- [ ] Forms usable
- [ ] Images scale correctly
- [ ] Navigation menu works (hamburger)

### Tablet (768px - 1199px)
- [ ] 2-column grid layouts
- [ ] Sidebar visible but narrower
- [ ] All functionality works

### Desktop (1200px+)
- [ ] 3-4 column grid layouts
- [ ] Full sidebar
- [ ] Optimal reading width
- [ ] No excessive whitespace

---

## 🔍 SEO & ACCESSIBILITY

### Meta Tags
- [ ] Each page has unique `<title>`
- [ ] Meta descriptions present
- [ ] Open Graph tags (for social sharing)
- [ ] Canonical URLs correct

### Schema.org
- [ ] Product schema on single car pages
- [ ] Organization schema in footer
- [ ] BreadcrumbList schema on catalog/single pages
- [ ] Validate at: https://search.google.com/test/rich-results

### Images
- [ ] All images have `alt` attributes
- [ ] Alt text descriptive
- [ ] Images lazy load: `loading="lazy"`

### Keyboard Navigation
- [ ] Can tab through all interactive elements
- [ ] Focus visible (outline on focused elements)
- [ ] Forms accessible via keyboard
- [ ] Modals/dropdowns work with keyboard

### Screen Reader
- [ ] Headings in logical order (h1 → h2 → h3)
- [ ] Links have descriptive text (not "click here")
- [ ] Forms have proper labels
- [ ] ARIA labels where needed

---

## ⚡ PERFORMANCE

### Page Load Speed
- [ ] Test with: https://pagespeed.web.dev/
- [ ] Score: 80+ (green) on mobile
- [ ] Score: 90+ (green) on desktop

### Asset Loading
- [ ] No 404 errors in Network tab (F12)
- [ ] CSS loads before content
- [ ] JS loads async/defer
- [ ] Fonts load properly

### Images
- [ ] Images lazy load
- [ ] Proper image sizes (not loading 4000px images for 400px display)
- [ ] WebP format (if supported)

### Queries
- [ ] No N+1 query issues
- [ ] Check with Query Monitor plugin
- [ ] Page load: <50 database queries

---

## 🔒 SECURITY

### Forms
- [ ] Nonce verification on all forms
- [ ] Input sanitization
- [ ] Output escaping
- [ ] No SQL injection possible

### Admin
- [ ] Capability checks before actions
- [ ] Can't access admin functions without permissions

### REST API
- [ ] Endpoints validate input
- [ ] Rate limiting (if implemented)
- [ ] Proper error messages (no stack traces)

---

## 🐛 ERROR HANDLING

### PHP Errors
- [ ] Check `/wp-content/debug.log`
- [ ] No fatal errors
- [ ] No warnings
- [ ] No notices

### JavaScript Errors
- [ ] Open browser console (F12)
- [ ] Navigate all pages
- [ ] No console errors
- [ ] No 404s for scripts

### Database
- [ ] No duplicate entries created
- [ ] Data saves correctly
- [ ] No orphaned meta data

---

## 📊 FINAL CHECKLIST

### Critical ✅
- [ ] Plugin activates without errors
- [ ] Theme activates without errors
- [ ] Can create car with all fields
- [ ] Cars display in catalog
- [ ] Filters work
- [ ] Lead form submits successfully
- [ ] Gutenberg blocks available

### Important ✅
- [ ] No PHP errors in debug.log
- [ ] No JavaScript console errors
- [ ] Mobile responsive
- [ ] Design matches system
- [ ] No emoji visible
- [ ] SEO markup valid

### Nice to Have ✅
- [ ] Performance score 80+
- [ ] Email notifications work
- [ ] Gallery lightbox works
- [ ] Related cars display
- [ ] Admin columns/filters functional

---

## 🎉 SMOKE TEST COMPLETE!

**If all critical and important items pass:** ✅ Ready for production!

**If any critical items fail:** ❌ Do not deploy. Debug and retest.

**If only nice-to-have items fail:** ⚠️ Can deploy, but fix soon.

---

## 📝 REPORTING ISSUES

If you find bugs during testing:

1. Note the exact steps to reproduce
2. Screenshot the error (console + visual)
3. Check `debug.log` for PHP errors
4. Create issue: https://github.com/Volkofff666/auto-import-wordpress/issues
5. Include:
   - WordPress version
   - PHP version
   - Browser and version
   - Error messages
   - Steps to reproduce

---

**Happy Testing!** 🚀