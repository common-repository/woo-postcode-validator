<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.eli5.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Postcode_Validator
 * @subpackage Woocommerce_Postcode_Validator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Postcode_Validator
 * @subpackage Woocommerce_Postcode_Validator/includes
 * @author     Kishan Chamman <kishan@eli5.io>
 */
class Woocommerce_Postcode_Validator
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Postcode_Validator_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woocommerce-postcode-validator';

        $this->load_dependencies();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Postcode_Validator_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Postcode_Validator_i18n. Defines internationalization functionality.
     * - Woocommerce_Postcode_Validator_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Postcode_Validator_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-postcode-validator-loader.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woocommerce-postcode-validator-public.php';

        $this->loader = new Woocommerce_Postcode_Validator_Loader();
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Woocommerce_Postcode_Validator_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'woocommerce_postcode_validator_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'woocommerce_postcode_validator_enqueue_scripts');

        // Add Ajax function
        $this->loader->add_action('wp_ajax_nopriv_get_woocommerce_postcode_validation', $plugin_public, 'get_woocommerce_postcode_validation');
        $this->loader->add_action('wp_ajax_get_woocommerce_postcode_validation', $plugin_public, 'get_woocommerce_postcode_validation');

        // Add ajax base url to head
        $this->loader->add_action('wp_head', $plugin_public, 'woocommerce_postcode_validator_add_ajax_url');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woocommerce_Postcode_Validator_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
