# VisionPoint Gutenberg Blocks Plugin

Requires at least: 5.0
Tested up to: 5.2
Requires PHP: 5.3
License: GPL3+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Custom Gutenberg Blocks for custom websites created by VisionPoint Marketing.

VPM Blocks will include the following blocks:

* Tabbed Content
* Call to Action
* Content Toggle (Accordion)
* Feature Box
* Stats Box
* Testimonial
* Hero Box

## Installation Instructions

Please remember you MUST have WordPress 5.0+ or Gutenberg installed to be able to use this plugin.

1. Install Gutenberg if you are not on WordPress version 5.0+.
2. Clone this repo into wp-content/plugins folder
3. Activate the plugins through the ‘Plugins’ menu in WordPress.

## Development Instructions

1. In `vpm-blocks` folder, run `npm install`
2. Then run `npm start`. The changed will be watched and blocks rebuilt.
3. To compile for production (in the dist folder), run `npm run build`.
