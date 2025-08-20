# Northwestern Global Marketing WordPress Theme

**Built & Managed by [Northwestern IT Academic & Research Technologies](http://www.it.northwestern.edu/about/departments/at/)**

A comprehensive WordPress theme built for Northwestern University's Global Marketing branding guidelines, featuring extensive customization options, custom post types, and specialized widgets.

## Features

### Core Functionality
* **Responsive Design** - Mobile-first approach with breakpoint-specific styling
* **Schema.org Integration** - Rich structured data markup for SEO
* **Accessibility Ready** - WCAG compliant with screen reader support
* **Custom Post Types** - News, Directory Items, Projects, and Events
* **Advanced Customizer** - Extensive theme customization options
* **Child Theme Support** - Includes sample child themes (Light & Student Affairs)

### Header & Navigation
* **Flexible Header Lockups** - 5 different header format options
* **Custom Logo Support** - Upload custom images or use text-based lockups
* **Breadcrumb Navigation** - Schema.org compliant breadcrumb trails
* **Responsive Navigation** - Mobile-optimized menu system

### Content Management
* **Custom Post Types:**
  - News Articles with custom permalinks
  - Directory Items (staff/faculty profiles)
  - Projects with categories and services
  - Events integration
* **Multiple Display Formats** - Feature boxes, photo features, text previews
* **Archive Customization** - Configurable archive layouts and hero images

### Widgets & Integrations
* **PlanItPurple Widget** - Northwestern event calendar integration
* **News Widget** - Display recent news articles
* **Statistics Widget** - Showcase key metrics
* **Text Widgets** - Full-width content areas
* **Posts Widget** - Curated post displays

### Footer Customization
* **Contact Information** - Address, phone, email, website fields
* **Social Media Links** - Support for major social platforms
* **RSS Feed Control** - Option to hide/show RSS feeds
* **Custom Footer Links** - Menu-driven footer navigation

### Plugin Support
* **Divi Builder** - 20+ custom Divi modules
* **Formidable Forms** - Enhanced form styling
* **AMP** - Accelerated Mobile Pages support
* **Meta Box** - Advanced custom fields integration

## Installation

1. Upload theme files to `/wp-content/themes/northwestern-global-marketing-wordpress-theme/`
2. Activate the theme in WordPress admin
3. Navigate to **Appearance > Customize** to configure theme options

## Customization

### Theme Customizer Sections
* **Header Lockup** - Configure site branding and logos
* **Homepage** - Hero banner and widget management
* **Footer** - Contact info, social media, and site links
* **Post Display Options** - Archive layouts and formatting
* **Custom Post Type Options** - News, Directory, and Project settings

### Child Themes
Two sample child themes are included:
* `nu_gm_light` - Lighter color scheme variant
* `nu_gm_student` - Student Affairs branding

## File Structure

```
northwestern-global-marketing-wordpress-theme/
├── library/
│   ├── core/                    # Core functionality
│   │   ├── custom-content-types/ # Custom post types
│   │   ├── custom-fields/        # Meta box fields
│   │   ├── plugin-support/       # Plugin integrations
│   │   ├── widgets/              # Custom widgets
│   │   └── customizer.php        # Theme customizer
│   ├── css/                     # Compiled stylesheets
│   ├── scss/                    # Sass source files
│   ├── js/                      # JavaScript files
│   ├── images/                  # Theme images
│   └── child-themes/            # Sample child themes
├── nu-gm-formats/               # Content format templates
├── post-formats/                # Post format templates
├── custom-templates/            # Template examples
└── [standard WordPress files]
```

## Custom Post Types

### News Articles (`nu_gm_news`)
- Custom permalink structure: `/news/YYYY/MM/DD/post-name/`
- Archive page: `/news/`
- Supports: title, editor, thumbnail, excerpt, revisions

### Directory Items (`nu_gm_directory_item`)
- Staff and faculty profiles
- Custom fields for contact information
- Grouping by last name initial
- Multiple display formats (big, medium, small)

### Projects (`nu_gm_project`)
- Portfolio/project showcase
- Custom taxonomies: categories and services
- Feature box and photo feature layouts

## Development

### Sass Compilation
The theme uses Sass for CSS preprocessing:
- Source files in `/library/scss/`
- Compiled output in `/library/css/`
- Breakpoint-specific stylesheets

### JavaScript
- Modern JavaScript with fallbacks
- Fancybox for lightboxes
- Swiper for carousels
- Custom GM scripts for interactions

## Browser Support
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Internet Explorer 11+ (with graceful degradation)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements
- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+

## License
MIT License - See LICENSE.txt for details

## Support
For theme support, please file a ticket through the [Northwestern IT Help Desk](http://www.it.northwestern.edu/supportcenter/)

## Changelog
See CHANGELOG.md for version history and updates.