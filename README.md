n# Custom Tabs Plugin

**Plugin Name:** Custom Tabs Plugin  
**Description:** A custom tabs plugin with an options page, ACF fields, and shortcode support.  
**Version:** 1.0.0  
**Author:** Yevgeni AnyWeb  
**Text Domain:** custom-tabs-plugin  
**Domain Path:** /languages  
**Requires PHP:** 7.4  
**Requires at least:** 5.8  

## Description

The Custom Tabs Plugin for WP allows you to create and manage custom tabs on your website. It includes an options page for easy configuration, supports Advanced Custom Fields (ACF), and provides a shortcode for displaying the tabs on any page or post.

## Features

- Create and manage custom tabs
- Supports ACF fields for tab content
- Shortcode for displaying tabs on any page or post
- Customizable grid layout for tab content
- Admin interface for managing tabs and brands

## Installation

1. Upload the plugin files to the `/wp-content/plugins/custom-tabs-plugin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to the plugin's settings page to configure your tabs and brands.

## Usage

### Shortcode

Use the `[custom_tabs_plugin_shortcode]` shortcode to display the tabs on any page or post.

### Options Page

Navigate to the plugin's options page to create and manage your tabs and brands. Here you can add new tabs, configure their content, and upload brand logos.

### SCSS and JS Compilation

This plugin uses Gulp for SCSS and JS compilation. The Gulp tasks are defined in `gulpfile.js`. To compile the SCSS and JS files, run the following commands:

```bash
# Install the necessary dependencies
npm install

# Compile SCSS and JS files
gulp

# Watch for changes in SCSS and JS files
gulp watch
