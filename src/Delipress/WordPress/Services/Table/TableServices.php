<?php

namespace Delipress\WordPress\Services\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;


/**
 * TableServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class TableServices implements ServiceInterface, MediatorServicesInterface {

    protected static $tables = array();

    /**
     * @param array $services
     * @return void
     */
    public function setServices($services){
        self::$tables["OptinStatsTableServices"]     = $services["OptinStatsTableServices"];
        self::$tables["ListSubscriberTableServices"] = $services["ListSubscriberTableServices"];
        self::$tables["SubscriberTableServices"]     = $services["SubscriberTableServices"];

    }


    public static function getTable($model){
        if(!array_key_exists($model, self::$tables)){
            return null;
        }
        
        return self::$tables[$model];
    }
   
}









