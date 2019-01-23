<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;

use Delipress\WordPress\Services\Connector\UserDataTrait;


/**
 * AbstractProviderExport
 */
abstract class AbstractProviderExport implements MediatorServicesInterface {

    use UserDataTrait;

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->providerServies = $services["ProviderServices"];
    }

    /**
     *
     * @param ProviderApi $providerApi
     * @return AbstractProviderExport
     */
    public function setApi($providerApi){
        $this->providerApi = $providerApi;
        return $this;
    }

    /**
     *
     * @param ListInterface $list
     * @param array $subscribers
     * @param array $args
     * @return void
     */
    public function exportSubscribers(ListInterface $list, $subscribers, $args = array()){
        
        if(!isset($args["safeError"])){
            $args["safeError"] = true;
        }

        $this->providerApi
             ->setSafeError($args["safeError"])
             ->createSubscribersOnList($list->getId(), $subscribers);

    }

    
}


