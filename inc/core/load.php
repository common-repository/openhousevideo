<?php
class OpenHouseVide_Load
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', array( __CLASS__, 'register' ));
    }

    /**
     * Register shortcodes
     */
    public static function register()
    {
        // Register shortcode
        add_shortcode('ohv_listing', array( 'OHV_Shortcodes', 'listing' ));
    }
}

new OpenHouseVide_Load;
