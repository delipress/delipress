<?php

namespace Delipress\WordPress\Services\Import;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Helpers\TypeFileHelper;
use Delipress\WordPress\Strategy\ImportSubscriberCsvStrategy;

/**
 * ImportFileSubscriberServices
 *
 * @author Delipress
 * @version 1.1.0
 * @since 1.1.0
 */
class ImportFileSubscriberServices implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->synchronizeSubscriberServices = $services["SynchronizeSubscriberServices"];
        $this->listServices                  = $services["ListServices"];
        $this->metaServices                  = $services["MetaServices"];
        $this->optionServices                = $services["OptionServices"];
    }


    /**
     *
     * @param array $item
     * @return void
     */
    public function importSubscribers($item){

        if(!isset($item["type"]) || !isset($item["file"]) || !isset($item["list_id"])){
            return;
        }

        switch($item["type"]){
            case TypeFileHelper::CSV:
            case TypeFileHelper::VND_MS_EXCEL:
                if(!isset($item["delimiter"])){
                    return;
                }

                $strategy = new ImportSubscriberCsvStrategy();
                $strategy->setDelimiter($item["delimiter"]);
                break;
            default:
                $strategy = apply_filters(DELIPRESS_SLUG . "_import_file_subscriber_process", "");
                break;
        }
    
        if(empty($strategy)){
            return;
        }

        $strategy->setServices(
            array(
                "MetaServices" => $this->metaServices,
                "OptionServices" => $this->optionServices
            )
        )->setFile($item["file"]);

        $subscribers = $strategy->getSubscribersFromImport($item);

        $list = $this->listServices->getList($item["list_id"]);

        if(!$list){
            return;
        }

        $allContacts = array();
        foreach($subscribers as $key => $subscriber){
            if(!is_email($subscriber["email"]) ){
                continue;
            }

            $allContacts[] = $subscriber;
        }

        $this->synchronizeSubscriberServices->synchronizeSubscribersOnList($list->getId(), $allContacts, true, false);

    } 


}









