<?php

namespace Delipress\WordPress\Services\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Models\CampaignModel;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\DataEncoder;



/**
 * CampaignServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class CampaignServices implements ServiceInterface{

    protected $countPosts = 0;

    /**
     *
     * @return int
     */
    public function getCountLastGetCampaigns(){
        return $this->countPosts;
    }

    /**
     *
     * @param array $args
     * @return CampaignModel[]
     */
    public function getCampaigns($args = array()){

        $paramsWPQuery = array_merge(
            array(
                "post_type"   => PostTypeHelper::CPT_CAMPAIGN,
            ),
            $args
        );

        $paramsWPQuery = apply_filters(DELIPRESS_SLUG . "_get_campaigns", $paramsWPQuery);

        $campaigns = new \WP_Query($paramsWPQuery);


        $campaignsModel = array();
        while($campaigns->have_posts()){
            $value = $campaigns->next_post();
            $campaign = new CampaignModel();
            $campaign->setCampaign($value);
            $campaignsModel[] = $campaign;
        }

        $this->countPosts = $campaigns->found_posts;

        return $campaignsModel;

    }

    /**
     *
     * @param string $provider
     * @param array $args
     * @return array
     */
    public function getCampaignsWithProvider($provider, $args = array()){
        return $this->getCampaigns(
           array_merge(
                array(
                    "meta_query"  => array(
                        array(
                            'key'     => PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER,
                            'value'   => $provider,
                            'compare' => "=",
                        )
                    )
                ),
                $args
           )
        );
    }


    /**
     *
     * @param  CampaignModel $campaign
     * @return string
     */
    public function getPreviewUrl(CampaignModel $campaign){
        return add_query_arg(
            array(
                "page"            => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS),
                "campaign_id"     => $campaign->getId(),
                "action"          => ActionHelper::PREVIEW_CAMPAIGN
            ),
            admin_url("admin.php")
        );
    }

    /**
     *
     * @return string
     */
    public function getPageUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS),
            ),
            admin_url("admin.php")
        );

    }

    /**
     *
     * @param integer $campaignId
     * @return string
     */
    public function getUrlSendTestCampaign($campaignId){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::CREATE_CAMPAIGN_SEND_TEST,
                    "campaign_id" => $campaignId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::CREATE_CAMPAIGN_SEND_TEST
        );
    }

    /**
     *
     * @param int $nextStep
     * @param int $campaignId
     * @return string
     */
    public function getCreateUrlByNextStep($nextStep, $campaignId = "", $autoFollowStepBuilder = false){
        if(!empty($campaignId) && $nextStep == 1 && $autoFollowStepBuilder){
            $campaign = new CampaignModel();
            $campaign->setCampaignById($campaignId);
            $hasValidStepOne = $campaign->hasValidStepOne();

            if($hasValidStepOne){
                $nextStep = 3;
            }
        }

        return add_query_arg(
            array(
                "page"        => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS),
                "action"      => "create",
                "step"        => $nextStep,
                "campaign_id" => $campaignId
            ),
            admin_url("admin.php")
        );

    }


    /**
     *
     * @param int $step
     * @param int $campaignId
     * @return string
     */
    public function getCreateUrlFormAdminPost($step = 1, $campaignId = null, $params = array()){
        
        $action = ActionHelper::CREATE_CAMPAIGN_STEP_ONE;
        switch($step){
            case 2:
                $action = ActionHelper::CREATE_CAMPAIGN_STEP_TWO;
                break;
            case 3:
                $action = ActionHelper::CREATE_CAMPAIGN_STEP_THREE;
                break;
            case 4:
                $action = ActionHelper::CREATE_CAMPAIGN_STEP_FOUR;
                break;
        }
        
        return wp_nonce_url(
            add_query_arg(
                array_merge(
                    array(
                        "action"      => $action,
                        "step"        => $step,
                        "campaign_id" => $campaignId
                    ),
                    $params   
                ),
                admin_url("admin-post.php")
            ),
            $action
        );
    }

    /**
     * @param int $campaignId
     * @return string
     */
    public function getDeleteCampaignUrl($campaignId){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"          => ActionHelper::DELETE_CAMPAIGN,
                    "campaign_id"     => $campaignId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_CAMPAIGN
        );
    }

    /**
     * @param string $type
     * @return string
     */
    public function getDeleteCampaignsUrl($type = "send"){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"  => ActionHelper::DELETE_CAMPAIGNS,
                    "type"    => $type
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_CAMPAIGNS
        );
    }

    /**
     *
     * @param int $campaignId
     * @return string
     */
    public function getSendCampaignUrl($campaignId){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action" => ActionHelper::SEND_CAMPAIGN,
                    "campaign_id" => $campaignId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::SEND_CAMPAIGN
        );
    }

    /**
     *
     * @param int $campaignId
     * @return void
     */
    public function getCampaignStatistic($campaignId){
        return add_query_arg(
            array(
                "page"        => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS),
                "action"      => "statistic",
                "campaign_id" => $campaignId
            ),
            admin_url("admin.php")
        );
    }

    /**
     *
     * @param CampaignModel $campaign
     * @return string
     */
    public function getUrlViewCampaignOnline(CampaignModel $campaign){

        return sprintf(
            "%s/delipress/campaign/%s/%s",
            home_url(),
            $campaign->getId(),
            $campaign->getTokenOnline()
        );
    }
}
