<?php

namespace Delipress\WordPress\Traits\Campaign;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Models\CampaignModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait CampaignTrait {

    /**
     * 
     * @param string $value
     * @return CampaignModel|null
     */
    public function checkCampaignExist($value){

        $campaign = get_post( $value );

        if($campaign && $campaign->post_type === PostTypeHelper::CPT_CAMPAIGN){
            $model = new CampaignModel();
            $model->setCampaign($campaign);
            return $model;
        }

        $this->missingParameters["check_campaign_exist"] = 1;
        
        return null;
        
    }
    /**
     * 
     * @param string $value
     * @return array
     */
    public function checkCampaignsExist($values){

        foreach($values as $key => $value){
            $values[$key] = $this->checkCampaignExist($value);
        }

        return $values;
    }

    /**
     * 
     * @param string $value
     * @return string
     */
    public function checkMetaCampaignSend($value){
        
        if($value !== "now" && $value !== "later"){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        return $value;
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public function checkDateSend($value){
        $locale = get_locale();

        if(empty($value)){
            return $value;
        }

        switch($locale){
            case "fr_FR":
                $value = \DateTime::createFromFormat('d-m-Y H:i', $value);
                if(!$value){
                    $this->missingParameters["check_date_send"] = 1;
                    return $value;
                }

                return $value->format("Y-m-d H:i:s");
                break;

            default:
                $value = \DateTime::createFromFormat('m-d-Y H:i', $value);
                if(!$value){
                    $this->missingParameters["check_date_send"] = 1;
                    return $value;
                }
                return $value->format("Y-m-d H:i:s");
                break;
        }
        
    }

 

}

