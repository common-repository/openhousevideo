<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization and hooks.
 *
 * @since        1.0.0
 * @package      OpenHouseVideo
 * @subpackage   OpenHouseVideo/includes
 */
class OpenHouseVideo
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
     * Upgrader class instance.
     *
     * @since  1.0.0
     * @var    OpenHouseVideo_Upgrade  Upgrader class instance.
     */
    public $upgrade;

    /**
     * Menu classes instances.
     *
     * @since  1.0.0
     */
    public $top_level_menu;

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
        $this->plugin_path    = plugin_dir_path($plugin_file);

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_common_hooks();

        self::$instance = $this;
    }

    /**
     * Load the required dependencies for the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for plugin upgrades.
         */
        require_once $this->plugin_path . 'includes/class-openhousevideo-upgrade.php';

        /**
         * Classes responsible for defining admin menus.
         */
        require_once $this->plugin_path . 'admin/class-openhousevideo-admin.php';
        require_once $this->plugin_path . 'admin/class-openhousevideo-admin-top-level.php';

        /**
         * Classes responsible for defining front side.
         */
        require_once $this->plugin_path . 'includes/class-openhousevideo-front.php';
        new OpenHouseVideo_Front($this->plugin_file, $this->plugin_version);
    }

    /**
     * Register all of the hooks related to the admin area functionality of the
     * plugin.
     *
     * @since     1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        /**
         * Upgrades.
         */
        $this->upgrade = new OpenHouseVideo_Upgrade( $this->plugin_file, $this->plugin_version );

        add_action( 'admin_init', array( $this->upgrade, 'maybe_upgrade' ) );

        /**
         * Top-level menu: OHVideo
         * admin.php?page=openhousevideo
         */
        $this->top_level_menu = new OpenHouseVideo_Admin_Top_Level($this->plugin_file, $this->plugin_version);

        add_action('admin_menu', array( $this->top_level_menu, 'admin_menu' ), 5);
        add_action('admin_enqueue_scripts', array( $this->top_level_menu, 'enqueue_scripts' ));
    }

    /**
     * Register all of the hooks related to both admin area and public part
     * functionality of the plugin.
     *
     * @since     1.0.0
     * @access   private
     */
    private function define_common_hooks()
    {
        /**
    		 * Enable shortcodes in text widgets and category descriptions.
    		 */
    		add_filter( 'widget_text', 'do_shortcode' );
    		add_filter( 'category_description', 'do_shortcode' );
    }
}
