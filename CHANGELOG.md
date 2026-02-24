# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.5.4]

#### Enhancement

- Added internationalization (i18n) support for hours of operation display
  - Day names now render in the WordPress site language using core locale (`$wp_locale`)
  - Added `translate_description()` helper to translate status values (e.g. "Closed", "Open 24 hours")
  - Added `load_plugin_textdomain()` so plugin-specific strings load from the `language/` directory
  - Updated `.pot` file with all translatable strings

#### Bug

- Fixed hours of operation not displaying days with no open/close times (e.g. Saturday/Sunday marked as Closed)
- Fixed undefined `$description` variable in `Deprecated.php` hours methods causing Closed days to be silently skipped
- Fixed inconsistent description rendering — all three renderers (Shortcode, Deprecated, Gutenberg) now consistently append description in parentheses when both hours and a description are present

## [1.5.3]

#### Bug

- Fix "Array to string conversion" warning in Gutenberg block data handling

## [1.5.2]

#### Fixes

- Changed admin notices
- Fixed full address Shortcode
- Fixed Gutenberg Elements
   - Social Links with icon
   - Logo Images

## [1.5.1]

#### Fixes

- Deployment issue

## [1.5.0]

### Revamp the new version

#### Fixes

- Multiple revision issues

#### Enhancement

- Introduced psr-4 model
- Reduced multiple shortcodes
- Implemented font awesome
- Added click-to-copy option

## [1.4.0]

### Updated

- Update to overcome WordFence plugin

## [1.3.0]

### Added

- short code and reusable block for business contact email.

## [1.2.0]

### Added

- a link to the row meta on the plugin screen which takes users to the plugin usage page.

## [1.1.0]

### Added

- support for updating the plugin through WordPress plugin management page.

## [1.0.1]

### Release

- Fix console errors about social link reusable blocks 
- Display hours of operation in a readable format

## [1.0.0]

### Release

- Creates Shortcodes and Reusable Blocks for the business profile data.
- initial release supports the following business data:
  - CompanyName
  - FullAddress
  - Address
  - City
  - State
  - ZipCode
  - Country
  - WorkNumber
  - TollFreeNumber
  - HoursOfOperation
  - CompanyDescription
  - CompanyShortDescription
  - PrimaryImage
  - LogoImage
  - Services
  - Foursquare
  - Twitter
  - Instagram
  - LinkedIn
  - Pinterest
  - Facebook
  - Rss
  - YouTube
