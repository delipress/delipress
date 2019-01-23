<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;


/**
 * MetaSubscriberServices
 *
 * @author Delipress
 */
class MetaSubscriberServices implements ServiceInterface, MediatorServicesInterface {
    
    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        // $this->metaTableServices = $services["MetaTableServices"];
    }

    /**
     *
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    public function getMetas($page = 1, $limit = 1000, $params = array()){

        $metas = $this->metaTableServices->getMetas(
            array(
                "selects" => array(
                    "id",
                    "name"
                )
            ), $page, apply_filters(DELIPRESS_SLUG . "_max_metas_import", $limit)
        );
        
        $metas           = array_merge(
            $metas, 
            SubscriberMetaHelper::getMetaNotInTable(), 
            SubscriberMetaHelper::getMetaWooCommerce()
        );

        if(isset($params["with_user_meta"]) && $params["with_user_meta"]){
            $metasUser = SubscriberMetaHelper::getWordPressUser();

            $metas           = array_merge(
                $metas, 
                $metasUser
            );
        }
        
        return $metas;
    }

}
