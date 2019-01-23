<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ConnectorHelper;

/**
 * @author Delipress
 */
abstract class StatusHelper{

    const UNSUBSCRIBE      = "unsubscribe";
    const SUBSCRIBE        = "subscribe";
    const PENDING          = "pending";

    /**
     *
     * @param string $key
     * @return string
     */
    public static function getLabelByKey($key){
        $labels = array(
            self::UNSUBSCRIBE => __("Unsubscribed", "delipress"),
            self::SUBSCRIBE => __("Subscriber", "delipress"),
            self::PENDING => __("Pending", "delipress"),
        );

        if(!array_key_exists($key, $labels)){
            return null;
        }

        return $labels[$key];
    }

}









