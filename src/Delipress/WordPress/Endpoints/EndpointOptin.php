<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * EndpointOptin
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EndpointOptin extends AbstractHook implements HooksAdminInterface{


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(current_user_can('manage_options' ) ){
            add_action( 'wp_ajax_delipress_save_optin', array($this, 'saveOptin') );
            add_action( 'wp_ajax_delipress_get_optin', array($this, 'getOptin') );
        }
    }

    /**
     * @action DELIPRESS_SLUG . '_before_endpoint_save_optin'
     * @action DELIPRESS_SLUG . '_after_endpoint_save_optin'
     */
    public function saveOptin(){
        if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["config"]) || !isset($_POST["optin_id"]) ) ||
            ( $_POST["config"] === null || $_POST["optin_id"] === null)
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        do_action(DELIPRESS_SLUG . '_before_endpoint_save_optin');

        $optinId = (int) $_POST["optin_id"];
        $config  = $_POST["config"];

        $optinPost = get_post($optinId);

        if($optinPost->post_type !== PostTypeHelper::CPT_OPTINFORMS){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        update_post_meta($optinId, PostTypeHelper::META_OPTIN_CONFIG, $config);
        // update_post_meta($optinId, PostTypeHelper::META_OPTIN_TYPE, $type);

        do_action(DELIPRESS_SLUG . '_after_endpoint_save_optin', $optinId);

        wp_send_json_success(
            array(
                "code" => "save_optin_success",
                "results" => array(
                    "id" => $optinId
                )
            )
        );
    }

    /**
     * @return JSON
     */
    public function getOptin(){
        if(
            ! isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            ! isset($_POST["optin_id"]) ||
            $_POST["optin_id"] === null
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $post = get_post( (int) $_POST["optin_id"]);

        if(!$post){
            wp_send_json_error(
                array(
                    "error"             => "get_optin_error",
                    "error_description" => __("Error: unable to get Opt-In form", "delipress")
                )
            );
        }

        if($post->post_type !== PostTypeHelper::CPT_OPTINFORMS){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }


        $config = get_post_meta($post->ID, PostTypeHelper::META_OPTIN_CONFIG, true);
        $type   = get_post_meta($post->ID, PostTypeHelper::META_OPTIN_TYPE,   true);

        wp_send_json_success(
            array(
                "code" => "get_optin_success",
                "results" => array(
                    "post"   => $post,
                    "config" => json_decode($config),
                    "type"   => $type
                )
            )
        );


    }

}
