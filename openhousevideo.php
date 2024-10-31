<?php
/**
 * Plugin Name: OpenHouseVideo
 * Version: 1.0.0
 * Author: OpenHouseVideo, LLC
 * Author URI: https://www.openhousevideo.com
 * Description: This is a plugin for OpenHouseVideo (https://www.openhousevideo.com)
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

/**
 * Define plugin constants.
 */
define('OHV_PLUGIN_FILE', __FILE__);
define('OHV_PLUGIN_VERSION', '1.0.0');
define('OHV_LINK', 'https://www.openhousevideo.com');
define('OHV_DOWNLOAD_LINK', esc_url(OHV_LINK . '/download/latest'));

/**
 * Load dependencies.
 */
require_once 'inc/core/load.php';
require_once 'inc/core/assets.php';
require_once 'inc/core/shortcodes.php';
require_once 'inc/core/tools.php';
require_once 'inc/core/data.php';
require_once 'inc/core/generator.php';
require_once 'inc/core/ajax.php';

/**
 * The code that runs during plugin activation.
 *
 * @since  1.0.0
 */
function activate_openhousevideo()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-openhousevideo-activator.php';

    OpenHouseVideo_Activator::activate();
}

register_activation_hook(__FILE__, 'activate_openhousevideo');

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_openhousevideo()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-openhousevideo.php';

    $plugin = new OpenHouseVideo(__FILE__, OHV_PLUGIN_VERSION);

    do_action('ohv/ready');
}

run_openhousevideo();

/**
 * Retrieves instance of the main plugin class.
 *
 * @since  1.0.0
 */
function openhousevideo()
{
    return OpenHouseVideo::get_instance();
}
