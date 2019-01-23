<?php

namespace Delipress\WordPress\Services\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Campaign\CampaignTrait;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\AdminFormValues;


/**
 * SendCampaignServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SendCampaignServices implements ServiceInterface, MediatorServicesInterface{

    use PrepareParams;
    use CampaignTrait;

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->providerServices = $services["ProviderServices"];
        $this->optionServices = $services["OptionServices"];

    }

      /**
     *
     * @action DELIPRESS_SLUG . "_before_synchronize_campaign_draft"
     * @action DELIPRESS_SLUG . "_after_synchronize_campaign_draft"
     *
     * @param string $provider
     * @param array $params
     * @param array $metas
     * @return void
     */
    public function synchronizeCampaignDraft($provider, $params, $metas){
        
        do_action(DELIPRESS_SLUG . "_before_synchronize_campaign_draft", $provider, $params, $metas);

        $response = $this->providerServices
                            ->getProviderApi($provider)
                            ->createDraftCampaign($params, $metas);

        $metaProvider = "";

        if($response["success"]){
            switch($provider){
                case ProviderHelper::MAILJET:
                    if(isset($response["status"]) && $response["status"] == 304){
                        $metaProvider = get_post_meta($params["id"] , PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID, true);
                    }
                    else{
                        $metaProvider = sprintf("%s_%s", $provider, $response["results"][0]["ID"] );
                        update_post_meta( $params["id"] , PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID, $metaProvider);
                    }
                    break;
                case ProviderHelper::MAILCHIMP:
                case ProviderHelper::SENDINBLUE:
                case ProviderHelper::SENDGRID:
                    $metaProvider = sprintf("%s_%s", $provider, $response["results"]["id"] );
                    update_post_meta( $params["id"] , PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID, $metaProvider);
                    break;
            }
        }

        do_action(DELIPRESS_SLUG . "_after_synchronize_campaign_draft", $response);

        return array(
            "success" => $response["success"],
            "results" => array(
                "api" => $response["results"],
                "metaProvider" => $metaProvider
            )
        );
    }

    /** 
     * @param CampaignModel $campaign
     * @return array
     */
    public function verifySynchronizeCampaign(CampaignModel $campaign){

        try{
            $provider  = $this->optionServices->getProvider();
            $list      = $campaign->getLists();

            $params = array(
                "id"                                => $campaign->getId(),
                PostTypeHelper::CAMPAIGN_TAXO_LISTS => $list,
                PostTypeHelper::CAMPAIGN_NAME       => $campaign->getTitle()
            );
            $metas = array(
                PostTypeHelper::META_CAMPAIGN_SUBJECT              => $campaign->getSubject(),
                PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID => $campaign->getMetaCampaignProvider()
            );

            $response = $this->synchronizeCampaignDraft($provider["key"], $params, $metas);

            if(!$response["success"]){
                return $response;
            }

            $campaign->setMetaCampaignProvider($response["results"]["metaProvider"]);
        }
        catch(\Exception $e){}

        return array(
            "success"  => $response["success"],
            "results" => $campaign
        );

        
    }

    /**
     * @param CampaignModel $campaign
     * @return array
     */
    public function authorizeSendThisDate($campaign){
        $authorize = true;
        $send      = $campaign->getSend();
        switch($send){
            case "later":
                $dateSend = $campaign->getDateSend();
                $date     = new \DateTime($dateSend);
                $date = $date->getTimestamp();

                $now      = new \DateTime("now");
                $now = $now->getTimestamp();
                if($date < $now){
                    $authorize = false;
                }
                break;
        }
        
        if(!$authorize){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::SEND_CAMPAIGN_ERROR)
            );
            
            return array(
                "success" => false
            );
        }

        return array(
            "success" => true
        );
    }


    /**
     * 
     * @action DELIPRESS_SLUG . "_before_send_campaign"
     * @action DELIPRESS_SLUG . "_after_send_campaign"
     * 
     * @param CampaignModel $campaign
     * @return array
     */
    public function sendCampaign(CampaignModel $campaign){
        
        do_action(DELIPRESS_SLUG . "_before_send_campaign", $campaign);
        
        $provider = $this->optionServices->getProvider();

        $response = $this->authorizeSendThisDate($campaign);
        if(!$response["success"]){
            return $response;
        }
        
        $response = $this->verifySynchronizeCampaign($campaign);

        if(!$response["success"]){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::SEND_CAMPAIGN_ERROR)
            );
            return $response;
        }
        
        $campaign = $response["results"];

        $providerApi = $this->providerServices
                            ->getProviderApi($provider["key"]);
        
        $response = $providerApi->sendCampaign($campaign);

        if($response["success"]){
            $args = array(
                'ID'            => $campaign->getId(),
                'post_status'   => "publish"
            );

            $postId =  wp_update_post($args);

            update_post_meta($campaign->getId(), PostTypeHelper::META_CAMPAIGN_IS_SEND, 1);
            
            $msg = CodeErrorHelper::getMessage(CodeErrorHelper::SEND_CAMPAIGN_SUCCESS);
            if($campaign->getSend() === "later"){
                $msg = CodeErrorHelper::getMessage(CodeErrorHelper::SEND_LATER_CAMPAIGN_SUCCESS);
            }

            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                $msg
            );
        }
        else{
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::SEND_CAMPAIGN_ERROR)
            );
        }

        do_action(DELIPRESS_SLUG . "_after_send_campaign", $campaign, $response);

        return $response;
    }

     /**
     * @action DELIPRESS_SLUG . "_before_send_test_campaign"
     * @action DELIPRESS_SLUG . "_after_send_test_campaign"
     *
     * @return array
     */
    public function sendTestCampaign(){

        $provider = $this->optionServices->getProvider();

        do_action(DELIPRESS_SLUG . "_before_send_test_campaign", $provider);

        $providerApi = $this->providerServices->getProviderApi($provider["key"]);

        $this->fieldsPosts = array(
            "send_to"     => "sanitize_email",
            "campaign_id" => "checkCampaignExist"
        );

        $params = $this->getPostParams("fields");

        if(!empty($this->missingParameters)){
            return array(
                "success" => false
            );
        }

        $campaign = $params["campaign_id"];    
        
        $response = $providerApi->sendCampaignTestEmail(
            array(
                "id"      => $campaign->getCampaignProviderId(),
                "subject" => $campaign->getSubject(),
                "html"    => $campaign->getHtml(),
                "emails" => array(
                    $params["send_to"]
                )
            )
        );

        AdminFormValues::cleanFormValues();

        do_action(DELIPRESS_SLUG . "_after_send_test_campaign", $campaign);

        return $response;
    }
        

}


