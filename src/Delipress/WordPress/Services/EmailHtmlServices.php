<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\CampaignMetaHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Models\CampaignModel;

/**
 * EmailHtmlServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EmailHtmlServices implements ServiceInterface, MediatorServicesInterface{
    
    protected $campaign = null;

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->campaignServices         = $services["CampaignServices"];
        $this->subscriberServices       = $services["SubscriberServices"];
        $this->optionServices           = $services["OptionServices"];
    }

    /**
     * 
     * @param string $key
     * @param string $html
     * @return string
     */
    public function siteUrl($key, $html){

        $replace = str_replace(
            $key,
             sprintf(
                "<a href='%s'>%s</a>", 
                home_url(),
                home_url()
            ),
            $html
        );

        return apply_filters(DELIPRESS_SLUG . "_email_html_site_url", $replace, $key, $html);
    }
    

    /**
     * 
     * @param string $key
     * @param string $html
     * @return string
     */
    public function viewCampaignOnline($key, $html){
        if(!$this->campaign){
            return $html;
        }

        $provider = $this->optionServices->getProviderKey();

        switch($provider){
            default:
                return str_replace(
                    $key,
                    $this->campaignServices->getUrlViewCampaignOnline($this->campaign),
                    $html
                );
                break;
            case ProviderHelper::SENDINBLUE:
                return str_replace(
                    $key,
                    "[MIRROR]",
                    $html
                );
                break;
        }
        
        
    }


    /**
     * @return string
     */
    public function getCodeUnsubscribeProvider(){

        $provider = $this->optionServices->getProviderKey();

        $fallback = null;
        switch($provider){
            case ProviderHelper::MAILCHIMP:
                $fallback = "*|UNSUB|*";
                break;
            case ProviderHelper::MAILJET:
                $fallback = "[[UNSUB_LINK]]";
                break;
            case ProviderHelper::SENDGRID:
                $fallback = "[unsubscribe]";
                break;
            case ProviderHelper::SENDINBLUE:
                $fallback = "[UNSUBSCRIBE]";
                break;
        }
        return $fallback;
    }
    
    /**
     * 
     * @param string $key
     * @param string $html
     * @return string
     */
    public function linkUnsubscribe($key, $html){
        if(!$this->campaign){
            return $html;
        }
        
        return str_replace(
            $key,
            $this->getCodeUnsubscribeProvider(),
            $html
        );
    }


    /**
     * 
     * @param int $campaignId
     * @param string $html
     * @return string
     */
    public function prepareEmailHtml($html, $campaignId = null){
        if($campaignId){
            $this->campaign = new CampaignModel();
            $this->campaign->setCampaignById($campaignId);
        }

        $metas = CampaignMetaHelper::getMetas();

        foreach($metas as $key => $callback){
            if(function_exists($callback)){
                $html = call_user_func($callback, $key, $html);
            }
            else if(method_exists($this, $callback)){
                $html = call_user_func_array(array($this, $callback), array($key, $html));
            }
            else{
                $html = apply_filters(DELIPRESS_SLUG . "_callback_prepare_campaign_html_" . $key, $html, $key, $campaign);
            }
        }
        
        return $html;
    }
           

    
}


