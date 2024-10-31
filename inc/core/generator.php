<?php
/**
 * OHV Generator
 */
class OHV_Generator
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('media_buttons', array( __CLASS__, 'button' ), 1000);

        add_action('wp_ajax_update_token', array( __CLASS__, 'update_token' ));
    }

    /**
     * Generator button
     */
    public static function button($args = array())
    {
        // Prepare button target
        $target = is_string($args) ? $args : 'content';
        // Prepare args
        $args = wp_parse_args($args, array(
                'target'    => $target,
                'text'      => __('Insert listing', 'openhousevideo'),
                'class'     => 'button',
                'icon'      => plugins_url('assets/images/icon.png', OHV_PLUGIN_FILE),
                'echo'      => true
            ));
        // Prepare icon
        if ($args['icon']) {
            $args['icon'] = '<img src="' . $args['icon'] . '" /> ';
        }
        // Print button
        $button = '<a href="javascript:void(0);" class="ohv-generator-button ' . $args['class'] . '" title="' . $args['text'] . '" data-target="' . $args['target'] . '" data-mfp-src="#ohv-popup" data-toggle="ohv-popup">' . $args['icon'] . $args['text'] . '</a>';
        // Show generator popup
        add_action('wp_footer', array( __CLASS__, 'popup' ));
        add_action('admin_footer', array( __CLASS__, 'popup' ));
        // Request assets
        wp_enqueue_media();
        ohv_query_asset('css', array( 'magnific-popup', 'font-awesome', 'ohv' ));
        ohv_query_asset('js', array( 'jquery', 'magnific-popup', 'ohv' ));
        // Hook
        do_action('ohv/button', $args);
        // Print/return result
        if ($args['echo']) {
            echo $button;
        }
        return $button;
    }

    /**
     * Generator popup form
     */
    public static function popup()
    {
        $token = ohv_get_token();

        ob_start();

        ohv_the_template('templates/popup', [ 'token' => $token, 'statuses' => OHV_Data::statuses() ]);

        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
    }

    /**
     * AJAX Update token
     */
    public static function update_token()
    {
        $token = null;
        if (!empty($_REQUEST['token']) && check_admin_referer('update_token')) {
            $token = $_REQUEST['token'];
        }

        ohv_update_token($token);

        return true;
    }
}

new OHV_Generator;

class OpenHouseVideo_Generator extends OHV_Generator
{
    public function __construct()
    {
        parent::__construct();
    }
}
