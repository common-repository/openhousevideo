<?php
/**
 * OHV Ajax
 */
class OHV_Ajax
{
    private static $sslverify = false;
    private static $timeout   = 10;

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('wp_ajax_get_listings',  array( __CLASS__, 'get_listings_response' ));
        add_action('wp_ajax_get_user',      array( __CLASS__, 'get_user_response' ));
        add_action('wp_ajax_get_info',      array( __CLASS__, 'get_info_response' ));
    }

    /**
     * Retrieve the collection of listings.
     *
     * @since    1.0.0
     * @access   private
     * @return   array Listings collection.
     */
    public static function get_listings_response()
    {
        $token = ohv_get_token();
        $listings_response = null;
        if (!is_null($token)) {
            $listings_response = self::load_listings($token);
        }

        echo json_encode($listings_response);

        wp_die();
    }

    /**
     * Load the collection of listings from remote API.
     *
     * @since   1.0.0
     * @access  private
     * @param   string $token
     * @return  array Listings collection.
     */
    private function load_listings($token)
    {
        $response = wp_remote_get(
          ohv_get_url('api-listing') . "?token={$token}", [
            'timeout' => self::$timeout,
            'sslverify' => self::$sslverify
          ]
        );
        $response = json_decode(wp_remote_retrieve_body($response), true);

        return $response;
    }

    /**
     * Retrieve the collection of User.
     *
     * @since    1.0.0
     * @access   private
     * @return   array User collection.
     */
    public static function get_user_response()
    {
        $token = ohv_get_token();
        $user_response = null;
        if (!is_null($token)) {
            $user_response = self::load_user($token);
        }

        echo json_encode($user_response);

        wp_die();
    }

    /**
     * Load the collection of User from remote API.
     *
     * @since   1.0.0
     * @access  private
     * @param   string $token
     * @return  array User collection.
     */
    private function load_user($token)
    {
        $response = wp_remote_get(
          ohv_get_url('api-user') . "?token={$token}", [
            'timeout' => self::$timeout,
            'sslverify' => self::$sslverify
          ]
        );
        $response = json_decode(wp_remote_retrieve_body($response), true);

        return $response;
    }

    /**
     * Retrieve the Plugin Info.
     *
     * @since    1.0.0
     * @access   private
     * @return   array Info.
     */
    public static function get_info_response()
    {
        $info_response = self::load_info();

        echo json_encode($info_response);

        wp_die();
    }

    /**
     * Load the Plugin Info from remote API.
     *
     * @since   1.0.0
     * @access  private
     * @return  array Info.
     */
    private function load_info()
    {
        $response = wp_remote_get(
          ohv_get_url('api-info') . "?token={$token}", [
            'timeout' => self::$timeout,
            'sslverify' => self::$sslverify
          ]
        );
        $response = json_decode(wp_remote_retrieve_body($response), true);

        return $response;
    }
}

new OHV_Ajax;

class OpenHouseVideo_Ajax extends OHV_Ajax
{
    public function __construct()
    {
        parent::__construct();
    }
}
