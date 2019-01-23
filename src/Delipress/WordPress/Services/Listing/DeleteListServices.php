<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\OptinModel;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Listing\ListTrait;
use Delipress\WordPress\Traits\Subscriber\SubscriberTrait;


/**
 * DeleteListServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteListServices implements ServiceInterface, MediatorServicesInterface {

    use ListTrait;
    
    use PrepareParams;

    /**
     * @var array
     */
    protected $missingParameters = array();

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->providerServices            = $services["ProviderServices"];
        $this->optionServices              = $services["OptionServices"];
        $this->optinServices               = $services["OptinServices"];
        $this->listServices                = $services["ListServices"];

    }

    /**
     * @filter DELIPRESS_SLUG . "_synchronize_delete_list"
     * @action DELIPRESS_SLUG . "_after_delete_list_provider"
     * 
     * @param ListInterface $list
     * @return array
     */
    public function deleteOneList(ListInterface $list){

        $optins = $list->getOptins();

        foreach($optins as $key => $optin){
            $totalListForOptin = $optin->getCountLists();
            if($totalListForOptin == 1){
                $this->optinServices->deactivateOptin($optin);
            }
        }

        $provider = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){
            return array(
                "success" => false
            );
        }

        return $this->providerServices
                    ->getProviderApi($provider["key"])
                    ->deleteList($list->getId());

    }

    
    /**
     * @action DELIPRESS_SLUG . "_before_delete_lists"
     * @action DELIPRESS_SLUG . "_after_delete_lists"
     * 
     * @return array
     */
    public function deleteLists(){
        $this->fieldsPosts = array(
            "lists"       => "",
        );

        $this->fieldsRequired = array(
            "lists"
        );

        $params = $this->getPostParams("fields");

        if(empty($params) ) {

            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false
            );
        }
        
        do_action(DELIPRESS_SLUG . "_before_delete_lists", $params);
        
        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        $fullResponse = array(
            "success" => true
        );
        foreach($params["lists"] as $key => $idList){
            $list = $this->listServices->getList($idList);

            if(!$list){
                continue;
            }
            
            $response = $this->deleteOneList($list);

            if(!$response["success"]){
                $fullResponse["success"] = false;
            }
        }

        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Lists sucessfully deleted", "delipress")
        );

        do_action(DELIPRESS_SLUG . "_after_delete_lists", $params, $fullResponse);

        return $fullResponse;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_delete_list"
     * @action DELIPRESS_SLUG . "_after_delete_list"
     * 
     * @return void
     */ 
    public function deleteList(){
        
        $this->fieldsGets = array(
            "list_id"       => "",
        );

        $this->fieldsRequired = array(
            "list_id"
        );

        $params = $this->getGetParams("fields");

        do_action(DELIPRESS_SLUG . "_before_delete_list", $params);

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        $list = $this->listServices->getList($params["list_id"]);

        $response = $this->deleteOneList($list);

        do_action(DELIPRESS_SLUG . "_after_delete_list");
        
        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("List successfully deleted", "delipress")
        );

        return array(
            "success" => true,
            "results" => $response
        );

    }

}









