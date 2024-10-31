<?php

/**
 * Class for managing plugin assets
 */
class OHV_Assets
{

    /**
     * Set of queried assets
     *
     * @var array
     */
    public static $assets = array( 'css' => array(), 'js' => array() );


    /**
     * Constructor
     */
    public function __construct()
    {
        // Register
        add_action('wp_head',       array( __CLASS__, 'register' ));
        add_action('admin_head',    array( __CLASS__, 'register' ));
        // Enqueue
        add_action('wp_footer',     array( __CLASS__, 'enqueue' ));
        add_action('admin_footer',  array( __CLASS__, 'enqueue' ));
    }

    /**
     * Register assets
     */
    public static function register()
    {
        // Font Awesome
        wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', 'all');
        // Magnific Popup
        wp_register_style('magnific-popup', plugins_url('assets/styles/magnific-popup.css', OHV_PLUGIN_FILE), false, '0.9.9', 'all');
        wp_register_script('magnific-popup', plugins_url('assets/scripts/magnific-popup.js', OHV_PLUGIN_FILE), array( 'jquery' ), '0.9.9', true);
        wp_localize_script('magnific-popup', 'ohv_magnific_popup', array(
            'close'   => __('Close (Esc)',  'openhousevideo'),
            'loading' => __('Loading...',   'openhousevideo'),
            'prev'    => __('Previous (Left arrow key)', 'openhousevideo'),
            'next'    => __('Next (Right arrow key)', 'openhousevideo'),
            'counter' => sprintf(__('%s of %s', 'openhousevideo'), '%curr%', '%total%'),
            'error'   => sprintf(__('Failed to load this link. %sOpen link%s.', 'openhousevideo'), '<a href="%url%" target="_blank"><u>', '</u></a>')
        ));
        // OHV
        wp_register_style('ohv', plugins_url('assets/styles/ohv.css', OHV_PLUGIN_FILE), array( 'magnific-popup' ), OHV_PLUGIN_VERSION, 'all');
        wp_register_script('ohv', plugins_url('assets/scripts/ohv.js', OHV_PLUGIN_FILE), array( 'magnific-popup'), OHV_PLUGIN_VERSION, true);

        $inline_js = array(
            'version' 			      => OHV_PLUGIN_VERSION,
            'update_token' 			  => wp_create_nonce('update_token'),
            'get_listings' 			  => wp_create_nonce('get_listings'),
            'get_user' 			      => wp_create_nonce('get_user'),
            'get_info' 			      => wp_create_nonce('get_info'),
            'loginApiUrl' 			  => ohv_get_url('api-login'),
            'loadingUrl' 				  => esc_url(plugin_dir_url(__FILE__) . '../../assets/images/loading.gif'),
            // 'pluginUrl' 				  => OHV_DOWNLOAD_LINK,
            'listingStatuses' 	  => OHV_Data::statuses(),
            'strings' => array(
                'Processing...'   => __('Processing...', 'openhousevideo'),
                'Loading...'      => __('Loading...', 'openhousevideo'),
                'Empty'           => __('Empty', 'openhousevideo'),
                'You can create a listing ' => __('You can create a listing ', 'openhousevideo'),
                'The plugin you are using is outdated. You can download the new one ' => __('The plugin you are using is outdated. You can download the new one ', 'openhousevideo'),
                'here'            => __('here', 'openhousevideo')
            )
        );

        // Add Token
        if ($token = ohv_get_token()) {
            $inline_js['token'] = $token;
        }

        wp_localize_script('ohv', 'WPOpenHouseVideo', $inline_js);
        // Hook to deregister assets or add custom
        do_action('ohv/assets/register');
    }

    /**
     * Enqueue assets
     */
    public static function enqueue()
    {
        // Get assets query and plugin object
        $assets = self::assets();
        // Enqueue stylesheets
        foreach ($assets['css'] as $style) {
            wp_enqueue_style($style);
        }
        // Enqueue scripts
        foreach ($assets['js'] as $script) {
            wp_enqueue_script($script);
        }
        // Hook to dequeue assets or add custom
        do_action('ohv/assets/enqueue', $assets);
    }

    /**
     * Add asset to the query
     */
    public static function add($type, $handle)
    {
        // Array with handles
        if (is_array($handle)) {
            foreach ($handle as $h) {
                self::$assets[$type][$h] = $h;
            }
        }
        // Single handle
        else {
            self::$assets[$type][$handle] = $handle;
        }
    }

    /**
     * Get queried assets
     */
    public static function assets()
    {
        // Get assets query
        $assets = self::$assets;
        // Apply filters to assets set
        $assets['css'] = array_unique(( array ) apply_filters('ohv/assets/styles', ( array ) array_unique($assets['css'])));
        $assets['js']  = array_unique(( array ) apply_filters('ohv/assets/scripts', ( array ) array_unique($assets['js'])));
        // Return set
        return $assets;
    }
}

new OHV_Assets;

/**
 * Helper function to add asset to the query
 *
 * @param string  $type   Asset type (css|js)
 * @param mixed   $handle Asset handle or array with handles
 */
function ohv_query_asset($type, $handle)
{
    OHV_Assets::add($type, $handle);
}
