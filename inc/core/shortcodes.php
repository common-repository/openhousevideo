<?php
class OHV_Shortcodes
{
    public function __construct()
    {
    }

    public static function listing($atts = null, $content = null)
    {
        $atts = shortcode_atts(array(
          'src' => null
        ), $atts, 'listing');

        do_action('ohv/listing', $atts);

        $src = ohv_parse_type_connection($atts['src']);

        return '<span class="ohv-iframe"><iframe width="598px" height="500px" src="' . $src .'" frameborder="0" allowfullscreen></iframe></span>';
    }
}

new OHV_Shortcodes;

class OpenHouseVideo_Shortcodes extends OHV_Shortcodes
{
    public function __construct()
    {
        parent::__construct();
    }
}
