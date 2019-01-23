<?php

namespace Delipress\WordPress\Services\Connector;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;
use DeliSkypress\Models\ContainerServiceInterface;

use Delipress\WordPress\Services\Connector\AbstractConnectorServices;

use Delipress\WordPress\Models\InterfaceModel\ConnectorInterface;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;

use Delipress\WordPress\Helpers\ConnectorHelper;

use Delipress\WordPress\Async\ConnectorBackgroundProcess;


/**
 * WooCommerceServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class WooCommerceServices extends AbstractConnectorServices implements ContainerServiceInterface {

    protected $keyConnector = ConnectorHelper::WOOCOMMERCE;

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
            $services["WooCommerceServices"],
            $services
        );
    }

    /**
     *
     * @param array $prepareImportLists
     * @return void
     */
    public function prepareBackgroundProcess($prepareImportLists){
        
        foreach($prepareImportLists as $key => $list){
            $this->connectorBackgroundProcess->push_to_queue($list);
        }
        
		$this->connectorBackgroundProcess->save()->dispatch();
    }

    /**
     *
     * @param int $listId
     * @return array
     */
    public function prepareLists($listId){
        $range              = apply_filters(DELIPRESS_SLUG . "_range_woocommerce_connector", $this->range);
        
        $totalUsers         = count_users();
        if(!isset($totalUsers["avail_roles"]["customer"])){
            return $totalUsers;
        }
        
        $countCall          = ceil($totalUsers["avail_roles"]["customer"] /  $range);
        $prepareLists = array();

        for ($i=1; $i <= $countCall; $i++) { 
            $prepareLists[] = array(
                "offset"            => ($i*$range) - $range,
                "limit"             => $range,
                "list_id"           => $listId,
                "page"              => $i,
                "total"             => $countCall
            );
        }

        return $prepareLists;

    }

    /**
     *
     * @param ListInterface $list
     * @param int $offset
     * @param int $limit
     * @return array
     */
    protected function getSubscribers(ListInterface $list, $offset, $limit){

        $subscribers = get_users(
            array(
                "role"   => "customer",
                "offset" => $offset,
                "number" => $limit
            )
        );

        return array(
            "success" => true,
            "results" => $subscribers
        );
    }


    /**
     *
     * @return array
     */
    public function constructParamsFromHook(){
        if(!isset($_POST) || empty($_POST)){
            return array();
        }

        return array(
            "email"      => $this->getEmailNameFromHook($_POST),
            "metas"      => array(
                "first_name" => $this->getFirstNameFromHook($_POST),
                "last_name"  => $this->getLastNameFromHook($_POST),
            )
        );
    }

    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getEmailNameFromHook($subscriber){
        return (isset($subscriber["email"])) ? $subscriber["email"] : "";
    }


    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getFirstNameFromHook($subscriber){
        return (isset($subscriber["first_name"])) ? $subscriber["first_name"] : "";
    }

    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getLastNameFromHook($subscriber){
        return (isset($subscriber["last_name"])) ? $subscriber["last_name"] : "";
    }


    /**
     *
     * @param ListInterface $list
     * @param int $offset
     * @param int $limit
     * @return void
     */
    public function importListContacts(ListInterface $list, $offset, $limit){

        $provider      = $this->optionServices->getProvider();
        $subscribers   = $this->getSubscribers($list, $offset, $limit);

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









