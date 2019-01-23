<?php

namespace Delipress\WordPress\Front;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Models\CampaignModel;


/**
 * CampaignOnline
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class CampaignOnline extends AbstractHook implements HooksInterface{


    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->campaignServices = $containerServices->getService("CampaignServices");
    }


   /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action('init', array($this, "campaignOnlineUrl"), 1);
        add_action( 'wp_loaded',array($this, "flushRules") );

        add_filter("template_redirect", array($this,"viewCampaignOnline"));
    }  

    /**
     * @see init
     *
     * @return void
     */
    public function flushRules(){
        $rules = get_option( 'rewrite_rules' );

        if ( ! isset( $rules['delipress/campaign/([^/]+)/([^/]+)/?$'] ) ) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }

    /**
     * @see init
     * @return void
     */
    public function campaignOnlineUrl(){

        add_rewrite_tag('%campaign_id%','([^&]+)');
        add_rewrite_tag('%token%','([^&]+)');

        add_rewrite_rule('delipress/campaign/([^/]+)/([^/]+)/?$', 'index.php?campaign_id=$matches[1]&token=$matches[2]', 'top');

    }

    /**
     * @action DELIPRESS_SLUG . "_before_campaign_online"
     * @action DELIPRESS_SLUG . "_after_campaign_online"
     * 
     * @return void
     */
    public function viewCampaignOnline(){

        $id    = get_query_var("campaign_id");
        $token = get_query_var("token");
        
        if(empty($id) || empty($token)){
            return;
        }

        $this->campaign = new CampaignModel();
        $this->campaign->setCampaignById($id);
    
        if($token !== $this->campaign->getTokenOnline()){
            wp_redirect(home_url());
            exit;
        }

        do_action(DELIPRESS_SLUG . "_before_campaign_online");
        
        include_once DELIPRESS_PLUGIN_DIR_TEMPLATES_FRONT . "/campaign_online.php";

        do_action(DELIPRESS_SLUG . "_after_campaign_online");

        die;
    }

    

}
