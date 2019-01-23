<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Specification\SpecificationSqlFactory;
use Delipress\WordPress\Async\ExportDynamicBackgroundProcess;

use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\ColorHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;



class CreateDynamicListServices implements ServiceInterface, MediatorServicesInterface {

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->createListServices                  = $services["CreateListServices"];
        $this->providerServices                    = $services["ProviderServices"];
        $this->listSubscriberTableServices         = $services["ListSubscriberTableServices"];
        $this->specificationServices               = $services["Specification"];
        $this->optionServices                      = $services["OptionServices"];
        $this->specificationServices->setFactory(new SpecificationSqlFactory());
        
        $this->exportDynamicBackgroundProcess  = new ExportDynamicBackgroundProcess(
            $services
        );
    }

    /**
     *
     * @param array $data
     * @return array
     */
    protected function cleanData($data){
        if(empty($data)){
            return array();
        }

        return array_map("array_values", $data );
    }

    /**
     *
     * @param array $data (From $_POST)
     * @return array
     */
    public function countDynamicList($data){
        if(isset($data[TaxonomyHelper::LIST_NAME])){
            unset($data[TaxonomyHelper::LIST_NAME]);
        }

        $data   = $this->cleanData($data);
        if(empty($data)){
            return array(
                "total" => 0
            );
        }

        $result = $this->specificationServices->constructSpecification($data);

        return array(
            "total" => count($result["results"])
        );
    }

    /**
     *
     * @param array $data (from $_POST)
     * @return array
     */
    public function createDynamicList($data){
        if(isset($data[TaxonomyHelper::LIST_NAME])){
            unset($data[TaxonomyHelper::LIST_NAME]);
        }

        $data   = $this->cleanData($data);

        if(empty($data)){
            return array(
                "success" => false
            );
        }

        $resultSubscribers = $this->specificationServices->constructSpecification($data);

        if(count($resultSubscribers["results"]) === 0){
            return array(
                "success" => false
            );
        }

        $name = "Date " . time();
        if(isset($_POST[TaxonomyHelper::LIST_NAME])){
            $name = sanitize_text_field($_POST[TaxonomyHelper::LIST_NAME]);
        }

        $params = array(
            TaxonomyHelper::LIST_NAME => $name
        );

        $resultCreateList = $this->createListServices->createListStandalone($params);

        if(!$resultCreateList["success"]){
            return $resultCreateList;
        }

        $list = $resultCreateList["results"]["list"];

        $provider       = $this->optionServices->getProvider();
        $ids            = delipress_array_flatten($resultSubscribers["results"]);
        $total          = count($ids);
        $range          = 50;
        $nameTransient  = DELIPRESS_SLUG . "export_dynamic_save_ids";

        set_transient($nameTransient, $ids);

        $countCall         = ceil($total /  $range);
        $prepareBackground = array();

        for ($i=1; $i <= $countCall; $i++) { 
            $this->exportDynamicBackgroundProcess->push_to_queue(array(
                "offset"            => ($i*$range) - $range,
                "limit"             => $range,
                "list_id"           => $list->getId(),
                "page"              => $i,
                "total"             => $countCall,
                "transient"         => $nameTransient
            ));
        }
        
        $this->exportDynamicBackgroundProcess->save()->dispatch();  
    
        return array(
            "success" => true   
        );

    }


}
