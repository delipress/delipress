<?php

namespace Delipress\WordPress\Services\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

use Delipress\WordPress\Models\OptinModel;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Optin\OptinTrait;


/**
 * DeleteOptinServices
 *
 * @author DeliPress
 */
class DeleteOptinServices implements ServiceInterface, MediatorServicesInterface {

    use OptinTrait;
    use PrepareParams;

    protected $missingParameters = array();

    protected $fieldsRequired   = array();

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices   = $services["OptionServices"];
    }

    /**
     * 
     * @param OptinModel $optin
     * @return array
     */
    protected function deleteOneOptin(OptinModel $optin){

        if(EMPTY_TRASH_DAYS){
            $result = wp_trash_post( $optin->getId() );
        }
        else{
            $result = wp_delete_post( $optin->getId() );
        }


        if(!$result){
            return array(
                "success" => false,
                "results" => $optin
            );
        }

        $response = array(
            "success" => true,
            "results" => $optin
        );

        return $response;
    }

    

    /**
     * @action DELIPRESS_SLUG . "_before_delete_optin"
     * @action DELIPRESS_SLUG . "_after_delete_optin"
     * 
     * @return array
     */ 
    public function deleteOptin(){
        
        $this->fieldsGets = array(
            "optin_id"       => "checkOptinExist",
        );
        
        $this->fieldsGetsRequired = array(
            "optin_id",
        );

        $params = $this->getGetParams("fields");

        do_action(DELIPRESS_SLUG . "_before_delete_optin", $params);

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }
        
        $response = $this->deleteOneOptin($params["optin_id"]);

        do_action(DELIPRESS_SLUG . "_after_delete_optin", $response);
        
        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Opt-In form successfully deleted", "delipress")
        );

        return array(
            "success" => true,
            "results" => $response
        );

    }

    /**
     *
     * @param string $type
     * @return array
     */
    public function deleteOptins($type){

        $this->fieldsPosts = array(
            "optinsActive"       => "checkOptinsExist",
        );
        
        $this->fieldsRequired = array(
            "optinsActive"
        );
        
        if($type === "inactive"){
            $this->fieldsPosts = array(
                "optinsInactive"       => "checkOptinsExist",
            );
            
            $this->fieldsRequired = array(
                "optinsInactive"
            );
        }
        
        $params = $this->getPostParams();

        if(empty($params) ) {
            return array(
                "success" => true
            );
        }

        if($type === "inactive"){
            $optins = $params["optinsInactive"];
        }
        else{
            $optins = $params["optinsActive"];
        }

        do_action(DELIPRESS_SLUG . "_before_delete_campaigns", $params);
        
        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        $fullResponse = array(
            "success" => true
        );

        if(empty($optins)){
            return $fullResponse;
        }

        foreach($optins as $key => $optin){
            $response = $this->deleteOneOptin($optin);

            if(!$response["success"]){
                $fullResponse["success"] = false;
            }
        }

        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Opt-In forms successfully deleted", "delipress")
        );

        do_action(DELIPRESS_SLUG . "_after_delete_campaigns", $params, $fullResponse);

        return $fullResponse;
    }

}









