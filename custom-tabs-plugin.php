<?php
/**
 * Plugin Name: Custom Tabs Plugin
 * Description: A custom tabs plugin with an options page, ACF fields, and shortcode support.
 * Version: 1.0.0
 * Author: Evgeny
 * Text Domain: custom-tabs-plugin
 * Domain Path: /languages
 * Requires PHP: 8.4
 * Requires at least: 5.8
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Custom_Tabs_Plugin')) {

    class Custom_Tabs_Plugin {

        public $version = '1.0.0';
        public $settings = array();

        public function __construct() {
            $this->initialize();
        }

        public function initialize() {
            $this->define_constants();
            $this->define_settings();
            $this->includes();
            $this->init_hooks();
        }

        private function define_constants() {
            $this->define('CUSTOM_TABS_PLUGIN', true);
            $this->define('CUSTOM_TABS_PLUGIN_PATH', plugin_dir_path(__FILE__));
            $this->define('CUSTOM_TABS_PLUGIN_BASENAME', plugin_basename(__FILE__));
            $this->define('CUSTOM_TABS_PLUGIN_VERSION', $this->version);
            $this->define('CUSTOM_TABS_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        private function define_settings() {
            $this->settings = array(
                'name'             => __('Custom Tabs Plugin', 'custom-tabs-plugin'),
                'slug'             => dirname(CUSTOM_TABS_PLUGIN_BASENAME),
                'version'          => CUSTOM_TABS_PLUGIN_VERSION,
                'basename'         => CUSTOM_TABS_PLUGIN_BASENAME,
                'path'             => CUSTOM_TABS_PLUGIN_PATH,
                'file'             => __FILE__,
                'url'              => CUSTOM_TABS_PLUGIN_URL,
                'show_admin'       => true,
                'show_updates'     => true,
                'capability'       => 'manage_options',
                'autoload'         => false,
                'l10n'             => true,
                'l10n_textdomain'  => '',
            );
        }

        private function includes() {
            include_once CUSTOM_TABS_PLUGIN_PATH . 'includes/shortcode.php';

            if (is_admin()) {
                include_once CUSTOM_TABS_PLUGIN_PATH . 'includes/admin/admin.php';
                include_once CUSTOM_TABS_PLUGIN_PATH . 'includes/admin/admin-notices.php';
                include_once CUSTOM_TABS_PLUGIN_PATH . 'includes/admin/options-page.php';
            }
        }

        private function init_hooks() {
            add_action('init', array($this, 'init'), 5);
            add_action('init', array($this, 'load_textdomain'));
            add_action('activated_plugin', array($this, 'deactivate_other_instances'));

            // Enqueue admin scripts and styles only if on the options page
            if (isset($_GET['page']) && $_GET['page'] === 'custom-tabs-plugin') {
                add_action('admin_enqueue_scripts', array($this, 'custom_tabs_plugin_admin_enqueue_scripts'));
            }
 

            add_filter('upload_mimes', array($this, 'custom_mime_types')); // Use class method as callback for upload_mimes filter

        }

        public function init() {
          
            if (!did_action('plugins_loaded')) {
                return;
            }

            if ($this->did_init()) {
                return;
            }


            $this->update_settings_url();
        }
        public function custom_mime_types($mimes) {
          $mimes['svg'] = 'image/svg+xml';
          return $mimes;
        }
        private function did_init() {
            static $did_init = false;
            if ($did_init) {
                return true;
            }
            $did_init = true;
            return false;
        }

        private function update_settings_url() {
            $this->settings['url'] = CUSTOM_TABS_PLUGIN_URL;
        }

        public function load_textdomain() {
            load_plugin_textdomain('custom-tabs-plugin', false, dirname(CUSTOM_TABS_PLUGIN_BASENAME) . '/languages');
        }

        public function deactivate_other_instances($plugin) {
            $plugin_to_deactivate = 'custom-tabs-plugin/custom-tabs-plugin.php';

            if ($plugin === $plugin_to_deactivate) {
                return;
            }

            deactivate_plugins($plugin_to_deactivate);
        }

        public function custom_tabs_plugin_admin_enqueue_scripts() {
            wp_enqueue_media();
            wp_enqueue_editor();

            // Enqueue your admin scripts here
            wp_enqueue_script('custom-tabs-plugin-admin-script', CUSTOM_TABS_PLUGIN_URL . 'assets/js/custom-tab-plugin-admin.bundle.min.js', array('jquery'), CUSTOM_TABS_PLUGIN_VERSION, true);
            wp_enqueue_style('custom-tabs-plugin-admin-style', CUSTOM_TABS_PLUGIN_URL . 'assets/css/admin.min.css', array(), CUSTOM_TABS_PLUGIN_VERSION);
            wp_localize_script('custom-tabs-plugin-admin-script', 'customTabsAjax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('custom-tabs-nonce')
            ));
        }

 

        private function define($name, $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }

    new Custom_Tabs_Plugin();
}
