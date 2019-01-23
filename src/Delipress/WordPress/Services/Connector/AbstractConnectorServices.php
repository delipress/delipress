<?php

namespace Delipress\WordPress\Services\Connector;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\InterfaceModel\ConnectorInterface;
use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ConnectorHelper;

use Delipress\WordPress\Services\Connector\UserDataTrait;

/**
 * AbstractConnectorServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractConnectorServices implements ConnectorInterface, MediatorServicesInterface {

    use UserDataTrait;

    protected $range = 100;
    
    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices          = $services["OptionServices"];
        $this->providerServices        = $services["ProviderServices"];
        $this->synchronizeListServices = $services["SynchronizeListServices"];
        $this->deleteListServices      = $services["DeleteListServices"];
        $this->createListServices      = $services["CreateListServices"];
        $this->connector               = ConnectorHelper::getConnectorByKey($this->keyConnector);
    }


     /**
     * @see ConnectorInterface
     *
     * @return void
     */
    public function prepareConnector(){
        
        $response = $this->createList();

        $prepareLists = $this->prepareLists($response["results"]["list"]->getId());

        $this->prepareBackgroundProcess($prepareLists);

        return $response;

    }

    /**
     * @see ConnectorInterface
     *
     * @return void
     */
    public function removeConnector($listId){

        $removeList = apply_filters(DELIPRESS_SLUG . "_remove_list_connect_" . $this->keyConnector, true );

        if($removeList){
            add_filter(DELIPRESS_SLUG . "_synchronize_delete_list", function(){
                return true;
            });

            $this->removeList($listId);
        }
    }   

    /**
     *
     * @param array $subscriber
     * @return array
     */
    public function prepareArgsSubscriber($subscriber){
        return array(
            "email"      => $this->getEmailSubscriberFromSubscribersResult($subscriber),
            "metas"      => array(
                "first_name" => $this->getFirstNameSubscriberFromSubscribersResult($subscriber),
                "last_name"  => $this->getLastNameSubscriberFromSubscribersResult($subscriber)
            )
        );
    }

    /**
     * @return null
     */
    protected function getProvider(){
        return null;
    }
    
    /**
     * @return string
     */
    public function getSource(){
        return $this->keyConnector;
    }
    

    /**
     *
     * @param array $list
     * @return null
     */
    protected function getIdFromListResult($list){
        return null;
    }

    /**
     *
     * @param array $list
     * @return null
     */
    protected function getNameFromListResult($list){
        return null;
    }

    /**
     *
     * @param array $list
     * @return null
     */
    protected function getTotalSubscriberFromListResult($list){
        return null;
    }

    /**
     *
     * @param array $subscriber
     * @return null
     */
    public function getIdSubscriberFromSubscribersResult($subscriber){
        return null;
    }


    /**
     * Create list connector
     *
     * @return array
     */
    protected function createList(){

        $name     = $newName = apply_filters(DELIPRESS_SLUG . "_name_" . $this->connector["key"] . "_connector", $this->connector["label"]);
        
        $i        = 1;
        $response = $this->createListServices->createListStandalone(
            array(
                TaxonomyHelper::LIST_NAME => $name
            ),
            array(
                "safeError" => true
            )
        );

        while(!$response["success"] || $i > 5){
            $newName = $name . " ($i)";
            $response = $this->createListServices->createListStandalone(
                array(
                    TaxonomyHelper::LIST_NAME => $newName
                ),
                array(
                    "safeError" => true
                )
            );

            $i++;
        }

        if($i > 5){
            return array(
                "success" => false,
            );
        }
        
        return $response;
    }

    /**
     *
     * @param int $idList
     * @return array
     */
    protected function removeList($idList){
        $list = new ListModel();
        $list->setId($idList);

        return $this->deleteListServices->deleteOneList($list);
    }

}









