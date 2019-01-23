<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Models\AbstractModel\AbstractImport;
use Delipress\WordPress\Strategy\ImportSubscriberCsvStrategy;

use Delipress\WordPress\Helpers\TypeFileHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;

/**
 * ImportSubscriberServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ImportSubscriberServices extends AbstractImport {

    
    /**
     *
     * @param File $file
     * @return ImportSubscriberServices
     */
    public function setFile($file){
        $this->file = $file;
        return $this;
    }

    /**
     *
     * @param string $type
     * @return Strategy
     */
    protected function getImportSubscriberStrategy($type){

        switch($type){
            case TypeFileHelper::CSV:
            case TypeFileHelper::VND_MS_EXCEL:
            default:
                $strategy = new ImportSubscriberCsvStrategy();
                $strategy->setDelimiter($this->getDelimiter());
                break;
            }
            
        $strategy = apply_filters(DELIPRESS_SLUG . "_import_subscriber_strategy", $strategy, $this->getDelimiter());

        $provider = $this->optionServices->getProvider();

        $strategy->setProvider($provider["key"])
                 ->setBackgroundProcess($this->backgroundProcess)
                 ->setServices(
                        array(
                            "MetaServices" => $this->metaServices,
                            "OptionServices" => $this->optionServices
                        )
                );


        return $strategy;
    }

    /**
     * @return array
     */
    public function prepareImport(){

        $result = $this->getData();

        if(!$result["success"]){
            return $result;
        }

        $strategy = $this->getImportSubscriberStrategy($result["type"]);

        if(empty($strategy)){
            return array(
                "success" => false
            );
        }

        $prepareImport = $strategy->setFile($this->getFile())
                                  ->prepareImport();

        set_transient(DELIPRESS_SLUG . "_import_subscriber_data_services", $prepareImport, HOUR_IN_SECONDS);
        set_transient(DELIPRESS_SLUG . "_import_subscriber_data_form", array(
            "file"          => $this->getFile(),
            "delimiter"     => $this->getDelimiter(),
            "listId"        => $result["listId"],
            "listCreate"    => $result["listCreate"],
            "type"          => $result["type"]
        ), HOUR_IN_SECONDS);

        return array(
            "success" => true
        );
    }

    /**
     * @return void
     */
    public function execute(){
        $dataForm = get_transient(DELIPRESS_SLUG . "_import_subscriber_data_form", false);
        
        if(!$dataForm){
            return array(
                "success" => false,
                "results" => array(
                    "message" => esc_html__("An error has occurred, please re-import the file", "delipress")
                )
            );
        }

        if(!isset($dataForm["file"]) || !isset($dataForm["type"])){
            return array(
                "success" => false,
                "results" => array(
                    "message" => esc_html__("An error has occurred, please re-import the file", "delipress")
                )
            );
        }

        if(isset($dataForm["delimiter"]) ){
            $this->setDelimiter($dataForm["delimiter"]);
        }

        $strategy = $this->getImportSubscriberStrategy($dataForm["type"]);

        if(empty($strategy)){
            return array(
                "success" => false,
                "results" => array(
                    "message" => esc_html__("An error has occurred, please re-import the file", "delipress")
                )
            );
        }

        $listId = null;
        if(isset($dataForm["createOrUpdate"]) && $dataForm["createOrUpdate"] === "create"){
            $response = $this->createListServices->createListStandalone(array(
                TaxonomyHelper::LIST_NAME => $dataForm["listCreate"]
            ));

            if($response["success"]){
                $listId = $response["results"]["list"]->getId();
            }
        }
        else{
            $listId = $dataForm["listId"];
        }

        $data = array(
            "meta_import"   => (isset($_POST["meta_import"]))   ? $_POST["meta_import"]   : array(),
            "create_import" => (isset($_POST["create_import"])) ? $_POST["create_import"] : array(),
            "list_id"       => $listId
        );

        $verifyEmail = array_filter($data["meta_import"], function($ar) {
            return $ar == SubscriberMetaHelper::EMAIL;
        });

        if(empty($verifyEmail)){
            return array(
                "success" => false,
                "results" => array(
                    "message" => esc_html__("You must at least choose an email field", "delipress")
                )
            );
        }

        
        $strategy->setFile($dataForm["file"])
                 ->setData($data)
                 ->execute();

        return array(
            "success" => true,
            "results" => array(
                "message" => esc_html__("Import datas, work in progress", "delipress")
            )
        );

    }

}
