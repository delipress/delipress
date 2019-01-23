<?php

namespace Delipress\WordPress\Services\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;

use Delipress\WordPress\Models\CampaignModel;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Campaign\CampaignTrait;


/**
 * DeleteCampaignServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteCampaignServices implements ServiceInterface, MediatorServicesInterface {

    use PrepareParams;
    use CampaignTrait;

    protected $missingParameters = array();

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        
        if(!array_key_exists("ProviderServices", $services)){
            throw new Exception("Miss ProviderServices");
        }
        
        if(!array_key_exists("OptionServices", $services)){
            throw new Exception("Miss OptionServices");
        }

        $this->providerServices = $services["ProviderServices"];
        $this->optionServices   = $services["OptionServices"];

    }

    /**
     * 
     * @param CampaignModel $campaign
     * @return array
     */
    protected function deleteOneCampaign(CampaignModel $campaign){

        $providerId = $campaign->getCampaignProviderId();

        $provider = $this->optionServices->getProvider();

        $response = array(
            "success" => true
        );

        $isSend           = $campaign->getIsSend();
        $deleteOnProvider = apply_filters(DELIPRESS_SLUG . "_delete_campaign_on_provider", true);

        if(
            array_key_exists("is_connect", $provider) &&
            $provider["is_connect"] && 
            $providerId &&
            !$isSend &&
            $deleteOnProvider
        ){
            $providerApi = $this->providerServices->getProviderApi($provider["key"]);
            $response    =  $providerApi->setSafeError(true)
                                        ->deleteCampaign($campaign);

            do_action(DELIPRESS_SLUG . "_after_delete_campaign_provider", $response);

        }

        if(EMPTY_TRASH_DAYS){
            $result = wp_trash_post( $campaign->getId() );
        }
        else{
            $result = wp_delete_post( $campaign->getId() );
        }


        if(!$result){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false
            );
        }


        return $response;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_delete_campaigns"
     * @action DELIPRESS_SLUG . "_after_delete_campaigns"
     * 
     * @return array
     */
    public function deleteCampaigns($type){

        if($type === "send"){
            $this->fieldsPosts = array(
                "campaignsSend"       => "checkCampaignsExist",
            );
            $this->fieldsRequired = array(
                "campaignsSend"
            );
        }
        else{
            $this->fieldsPosts = array(
                "campaignsDraft"       => "checkCampaignsExist",
            );
            $this->fieldsRequired = array(
                "campaignsDraft"
            );
        }

        $params = $this->getPostParams("fields");

        if(empty($params) ) {
            return array(
                "success" => true
            );
        }

        if($type === "send"){
            $campaigns = $params["campaignsSend"];
        }
        else{
            $campaigns = $params["campaignsDraft"];
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

        if(empty($campaigns)){
            return $fullResponse;
        }

        foreach($campaigns as $key => $campaign){
            $response = $this->deleteOneCampaign($campaign);

            if(!$response["success"]){
                $fullResponse["success"] = false;
            }
        }

        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Campaigns successfully deleted", "delipress")
        );

        do_action(DELIPRESS_SLUG . "_after_delete_campaigns", $params, $fullResponse);

        return $fullResponse;
    }

    /**
     * 
     * @action DELIPRESS_SLUG . "_before_delete_campaign"
     * @action DELIPRESS_SLUG . "_after_delete_campaign"
     * 
     * @return array
     */ 
    public function deleteCampaign(){
        
        $this->fieldsGets = array(
            "campaign_id"       => "checkCampaignExist",
        );

        $this->fieldsRequired = array(
            "campaign_id"
        );

        $params = $this->getGetParams("fields");
        
        do_action(DELIPRESS_SLUG . "_before_delete_campaign", $params);
    
        if(!empty($this->missingParameters)){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::TRY_CHEAT,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );

            return array(
                "success" => false
            );
        }
        
        $response = $this->deleteOneCampaign($params["campaign_id"]);

        do_action(DELIPRESS_SLUG . "_after_delete_campaign", $response);

        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Campaign successfully deleted", "delipress")
        );
        
        return array(
            "success" => true,
            "results" => $response
        );

    }

}









