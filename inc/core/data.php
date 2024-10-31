<?php
/**
 * Class for managing plugin data
 */
class OHV_Data
{

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Listing's statuses
     */
    public static function statuses()
    {
        return apply_filters('ohv/data/listing/statuses', array(
            2 => array(
                'title' => 'Active',
                'active' => true
            ),
            3 => array(
                'title' => 'Inactive',
                'active' => false
            ),
            1 => array(
                'title' => 'Pending',
                'active' => false
            ),
            4 => array(
                'title' => 'Sold',
                'active' => false
            )
        ));
    }
}

class OpenHouseVideo_Data extends OHV_Data
{
    public function __construct()
    {
        parent::__construct();
    }
}
