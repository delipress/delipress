<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ActionHelper;

/**
 * EndpointCampaign
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EndpointCampaign extends AbstractHook implements HooksAdminInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->emailHtmlServices      = $containerServices->getService("EmailHtmlServices");
        $this->createCampaignServices = $containerServices->getService("CreateCampaignServices");
    }


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){
        add_action( 'wp_ajax_delipress_save_campaign_template', array($this, 'saveCampaignTemplate') );
        add_action( 'wp_ajax_delipress_get_campaign_template', array($this, 'getCampaignTemplate') );
        add_action( 'wp_ajax_delipress_get_campaign', array($this, 'getCampaign') );
        add_action( 'wp_ajax_delipress_save_campaign_template_html', array($this, 'saveCampaignTemplateHtml') );
    }

    /**
     * @action DELIPRESS_SLUG . '_before_save_campaign_template'
     * @action DELIPRESS_SLUG . '_after_save_campaign_template'
     */
    public function saveCampaignTemplate(){
        if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["campaignId"]) || $_POST["campaignId"] === null )  ||
            ( !isset($_POST["config"]) || $_POST["config"] === null ) ||
            ( !isset($_POST["_wpnonce_ajax"]) )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        do_action(DELIPRESS_SLUG . '_before_save_campaign_template');

        $postId = null;
        if(isset($_POST["campaignId"]) && $_POST["campaignId"] != null){
            $postId = (int) $_POST["campaignId"];
        }

        if($postId === null){

            wp_send_json_error(
                array(
                    "code"              => "save_campaign_template_no_id",
                    "error_description" => __("Error: unable to get post", "delipress")
                )
            );
        }


        $config    = $_POST["config"];

        update_post_meta($postId, PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG, $config);

        do_action(DELIPRESS_SLUG . '_after_save_campaign_template', $postId);

        wp_send_json_success(
            array(
                "code" => "save_campaign_template_success",
                "results" => array(
                    "id" => $postId
                )
            )
        );

    }

    /**
     * @action DELIPRESS_SLUG . '_before_save_campaign_template_html'
     * @action DELIPRESS_SLUG . '_after_save_campaign_template_html'
     */
    public function saveCampaignTemplateHtml(){
        if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["campaignId"]) || $_POST["campaignId"] === null )  ||
            ( !isset($_POST["html"]) || $_POST["html"] === null ) ||
            ( !isset($_POST["_wpnonce_ajax"]) )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        do_action(DELIPRESS_SLUG . '_before_save_campaign_template_html');

        $postId = null;
        if(isset($_POST["campaignId"]) && $_POST["campaignId"] != null){
            $postId = (int) $_POST["campaignId"];
        }

        if($postId === null){

            wp_send_json_error(
                array(
                    "code"              => "save_campaign_template_no_id",
                    "error_description" => __("Error: unable to get post", "delipress")
                )
            );
        }

        $html = $_POST["html"];
        $html = $this->emailHtmlServices->prepareEmailHtml($html, $postId);

        update_post_meta($postId, PostTypeHelper::META_CAMPAIGN_TEMPLATE_HTML,  $html);

        do_action(DELIPRESS_SLUG . '_after_save_campaign_template_html', $postId);

        wp_send_json_success(
            array(
                "code"     => "save_campaign_template_html_success",
                "results"  => array(
                    "id" => $postId,
                )
            )
        );

    }

    /**
     * Get campaign
     * @return JSON
     */
    public function getCampaign(){
        if(
            !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            !isset($_POST["campaignId"]) || $_POST["campaignId"] === null ||
            !isset($_POST["_wpnonce_ajax"])
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $post = get_post( (int) $_POST["campaignId"]);

        if(!$post){
             wp_send_json_error(
                array(
                    "code"              => "get_campaign_template_error",
                    "error_description" => __("Error: unable to get campaign template", "delipress")
                )
            );
        }
        
        $config   = json_decode( get_post_meta($post->ID, PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG, true) );

        if(empty($config)){
            $config = $this->createCampaignServices->getTemplateFromScratch();
        }

        wp_send_json_success(
            array(
                "code" => "get_campaign_success",
                "results" => array(
                    "post"     => $post,
                    "config"   => $config
                )
            )
        );


    }

    /**
     * 
     * @return JSON
     */
    public function getCampaignTemplate(){
        if(
            !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            !isset($_POST["campaignId"]) || $_POST["campaignId"] === null ||
            !isset($_POST["_wpnonce_ajax"])
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $post = get_post( (int) $_POST["campaignId"]);

        if(!$post){
             wp_send_json_error(
                array(
                    "code"              => "get_campaign_template_error",
                    "error_description" => __("Error: unable to get campaign template", "delipress")
                )
            );
        }

        $config    = get_post_meta($post->ID, PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG, true);

        if(empty($config)){
            $config = $this->createCampaignServices->getTemplateFromScratch();
        }

        wp_send_json_success(
            array(
                "code" => "get_campaign_template_success",
                "results" => array(
                    "post"     => $post,
                    "config"   => json_decode($config)
                )
            )
        );


    }


}
