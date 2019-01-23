<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminFormValues;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Listing\ListTrait;


/**
 * CreateListServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateListServices implements ServiceInterface, MediatorServicesInterface {

    use PrepareParams;
    use ListTrait;

    protected $paramsNotValid = 0;

    protected $missingParameters = array();

    protected $fieldsPosts = array(
        TaxonomyHelper::LIST_NAME         => "stripslashes"
    );

    protected $fieldsPostMetas = array(
        TaxonomyHelper::META_LIST_COLOR   => "sanitize_text_field"
    );

    protected $fieldsRequired = array(
        TaxonomyHelper::LIST_NAME
    );

    protected $fieldsMetasRequired = array();

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
        $this->listServices            = $services["ListServices"];

    }

    /**
     * @param array $params
     * @return void
     */
    protected function verifyParameters($params){

        if(empty($params[TaxonomyHelper::LIST_NAME])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                TaxonomyHelper::LIST_NAME,
                CodeErrorHelper::getMessage(CodeErrorHelper::NOT_EMPTY)
            );
        }
    }

     /**
     * @action DELIPRESS_SLUG . "_before_create_list"
     * @action DELIPRESS_SLUG . "_after_create_list"
     *
     * @param array $params
     * @param array $args
     * 
     * @return array
     */
    public function createListStandalone($params, $args = array()){

        do_action(DELIPRESS_SLUG . "_before_create_list", $params, $args);


        $list = new ListModel();
        $list->setName($params[TaxonomyHelper::LIST_NAME]);

        $response = $this->synchronizeListServices
                         ->createListSynchronize($list, $args);

        if(!$response["success"]){
            return $response;
        }

        $list->setId($response["results"]["list_id"]);

        do_action(DELIPRESS_SLUG . "_after_create_list", $list, $params, $args);

        return array(
            "success" => true,
            "results" => array(
                "list" => $list
            )
        );
    
    }

    /**
     * @return array
     */
    public function createList(){
        $params = $this->getPostParams("fields");

        $this->verifyParameters($params);

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }
        if(
            $this->paramsNotValid > 0
        ){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );
            return array(
                "success" => false
            );
        }

        $response = $this->createListStandalone($params);

        if($response["success"]){
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("List successfully created", "delipress")
            );
        }
        
        AdminFormValues::cleanFormValues();

        return $response;
    }

    /**
     *
     * @return array
     */
    public function editList(){

        $this->fieldsPosts = array(
            "list_id"                         => "",
            TaxonomyHelper::LIST_NAME         => "sanitize_text_field",
        );

        $this->fieldsRequired = array(
            "list_id",
            TaxonomyHelper::LIST_NAME
        );

        $params = $this->getPostParams("fields");

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        do_action(DELIPRESS_SLUG . "_before_delete_list", $params);

        $list = $this->listServices->getList($params["list_id"]);

        $response = $this->synchronizeListServices
                         ->editListSynchronize($list, $params);
        
        if(!$response["success"]){
            return $response;
        }
        else{
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("List successfully edited", "delipress")
            );
        }

        do_action(DELIPRESS_SLUG . "_after_delete_list", $list, $params);

        AdminFormValues::cleanFormValues();

        return $response;
    }
}
