<?php

/**
 * Fired for the front side.
 *
 * This class defines all code necessary to run for the front side.
 *
 * @since        1.0.0
 * @package      OpenHouseVideo
 * @subpackage   OpenHouseVideo/includes
 */
class OpenHouseVideo_Front
{

    /**
     * The path to the main plugin file.
     *
     * @since    1.0.0
     * @access   private
     * @var      string      $plugin_file   The path to the main plugin file.
     */
    private $plugin_file;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string      $plugin_version   The current version of the plugin.
     */
    private $plugin_version;

    /**
     * The path to the plugin folder.
     *
     * @since    1.0.0
     * @access   private
     * @var      string      $plugin_path   The path to the plugin folder.
     */
    private $plugin_path;

    /**
     * Class instance.
     *
     * @since  1.0.0
     * @access private
     * @var    null      The single class instance.
     */
    private static $instance;

    /**
     * Get class instance.
     *
     * @since  1.0.0
     * @return OpenHouseVideo
     */
    public static function get_instance()
    {
        return self::$instance;
    }

    /**
     * Define the core functionality of the plugin.
     *
     * @since   1.0.0
     * @param string  $plugin_file    The path to the main plugin file.
     * @param string  $plugin_version The current version of the plugin.
     */
    public function __construct($plugin_file, $plugin_version)
    {
        $this->plugin_file    = $plugin_file;
        $this->plugin_version = $plugin_version;
        $this->plugin_url     = plugin_dir_url( $plugin_file );
        $this->plugin_path    = plugin_dir_path($plugin_file);

        $this->define_front_hooks();

        self::$instance = $this;
    }

    /**
     * Register all of the hooks related to the front area functionality of the
     * plugin.
     *
     * @since     1.0.0
     * @access   private
     */
    private function define_front_hooks()
    {
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
    }

    /**
     * Enqueue JavaScript(s) and Stylesheet(s) for the front side.
     *
     * @since   1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style( 'ohv-front', $this->plugin_url . 'assets/styles/ohv-loader.css', array(), $this->plugin_version );
        wp_enqueue_script( 'ohv-front', $this->plugin_url . 'assets/scripts/ohv-loader.js', array('jquery'), $this->plugin_version );

        $inline_js = array(
            'version' 			      => OHV_PLUGIN_VERSION,
            'loadingUrl' 				  => esc_url(plugin_dir_url(__FILE__) . '../assets/images/loading.gif'),
            'strings' => array(
                'Loading...'      => __('Loading...', 'openhousevideo')
            )
        );

        wp_localize_script('ohv-front', 'WPOpenHouseVideo', $inline_js);
        // Hook to deregister assets or add custom
        do_action('ohv/assets/front');
    }
}
