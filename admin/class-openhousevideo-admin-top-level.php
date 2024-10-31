<?php

/**
 * The OHV menu component.
 *
 * @since        1.0.0
 *
 * @package      OpenHouseVideo
 * @subpackage   OpenHouseVideo/admin
 */
final class OpenHouseVideo_Admin_Top_Level extends OpenHouseVideo_Admin
{

    /**
     * Initialize the class and set its properties.
     *
     * @since  1.0.0
     * @param string  $plugin_file    The path of the main plugin file
     * @param string  $plugin_version The current version of the plugin
     */
    public function __construct($plugin_file, $plugin_version)
    {
        parent::__construct($plugin_file, $plugin_version);
    }


    /**
     * Add menu page
     *
     * @since   1.0.0
     */
    public function admin_menu()
    {

        // SVG icon (base64-encoded)
        $icon = apply_filters('ohv/admin/icon', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIxLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9ItCh0LvQvtC5XzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDEgMzMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQxIDMzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+Cgkuc3Qwe2ZpbGw6I0ZGNjgwMTt9Cjwvc3R5bGU+CjxnIGlkPSJYTUxJRF8xXyI+Cgk8Zz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNNDEsMjR2NmgtMWwtNi0zaC0xdjJoLTJ2NGgtOFYxOEgxM3YxbDcsMnYxMmgtN0g0VjE0SDB2LTFsNy01LjRWMWg1djIuOEwxNywwaDFsMTcsMTN2MWgtNHY0aDJ2MmgxbDYtM2gxdjYKCQkJVjI0eiBNMjEsMTNWOC41QzIxLDYuNiwxOS40LDUsMTcuNSw1UzE0LDYuNiwxNCw4LjVWMTNIMjF6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==');

        /**
         * Top-level menu: OHVideo
         * admin.php?page=openhousevideo
         */
        $this->add_menu_page(
            __('OpenHouseVideo Plugin', 'openhousevideo'),
            __('OHVideo', 'openhousevideo'),
            $this->get_capability(),
            'openhousevideo',
            array( $this, 'the_menu_page' ),
            $icon,
            '80.11'
        );
    }

    /**
     * Enqueue JavaScript(s) and Stylesheet(s) for the component.
     *
     * @since   1.0.0
     */
    public function enqueue_scripts()
    {
        if (! $this->is_component_page()) {
            return;
        }

        // Request assets
        wp_enqueue_media();
        ohv_query_asset('css', array( 'magnific-popup', 'font-awesome', 'ohv' ));
        ohv_query_asset('js',  array( 'jquery', 'magnific-popup', 'ohv' ));
    }
}
