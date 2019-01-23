<?php

namespace Delipress\WordPress\Services\Connector;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;
use DeliSkypress\Models\ContainerServiceInterface;

use Delipress\WordPress\Services\Connector\AbstractConnectorServices;

use Delipress\WordPress\Models\InterfaceModel\ConnectorInterface;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\ConnectorHelper;

use Delipress\WordPress\Async\ConnectorBackgroundProcess;


class DynamicListServices extends AbstractConnectorServices implements ContainerServiceInterface {

    protected $keyConnector = ConnectorHelper::WORDPRESS_USER;

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        parent::setServices($services);
        
        $this->optionServices              = $services["OptionServices"];
        $this->connectorBackgroundProcess  = new ConnectorBackgroundProcess(
            $this->keyConnector,
            $services["WordPressUserServices"],
            $services
        );
    }



    /**
     *
     * @param ListInterface $list
     * @param array $args
     * @return array
     */
    protected function getSubscribersDynamic(ListInterface $list, $args){

        $ids = get_transient($args["transient"], false);
        if(!$ids){
            return array(
                "success" => false,
                "results" => array()
            );
        }

        $idsInclude = array_slice($ids, $args["offset"], $args["limit"]);

        $subscribers = get_users(
            array(
                "include" => $idsInclude
            )
        );


        return array(
            "success" => true,
            "results" => $subscribers
        );
    }

    /**
     *
     * @param ListInterface $list
     * @param array $args
     * @return void
     */
    public function exportDynamicListContacts(ListInterface $list, $args){

        $provider      = $this->optionServices->getProvider();
        $subscribers   = $this->getSubscribersDynamic($list, $args);

        if(!$subscribers["success"]){
            return;
        }

        $argsSubscribers = array();
        foreach($subscribers["results"] as $key => $subscriber){
            $argsSubscribers[] = $this->prepareArgsSubscriber($subscriber);
        }

        $this->providerServices
             ->getProviderExport($provider["key"])
             ->exportSubscribers($list, $argsSubscribers);

    }

}









