<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ActionHelper;

class EndpointTemplate extends AbstractHook implements HooksAdminInterface{


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){
        add_action( 'wp_ajax_delipress_get_templates', array($this, 'getTemplates') );
        add_action( 'wp_ajax_delipress_get_template', array($this, 'getTemplate') );
        add_action( 'wp_ajax_delipress_save_template', array($this, 'saveTemplate') );
        add_action( 'wp_ajax_delipress_update_template', array($this, 'updateTemplate') );
        add_action( 'wp_ajax_delipress_remove_template', array($this, 'removeTemplate') );
    }


    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){}


    public function getTemplates(){
 
        if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["_wpnonce_ajax"]) ) ||
            ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) 
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }   


        $templates = get_posts(array(
            "post_type" => PostTypeHelper::CPT_TEMPLATE
        ));

        wp_send_json_success(
            array(
                "code" => "get_templates",
                "results" => $templates
            )
        );
        
    }


    public function getTemplate(){

         if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["_wpnonce_ajax"]) ) ||
            ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) 
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }   

        $post   = get_post($_POST["template_id"]);
        $config = json_decode(get_post_meta($_POST["template_id"], PostTypeHelper::META_TEMPLATE_CONFIG, true ));

        wp_send_json_success(array(
            "code" => "get_template",
            "results" => array(
                "post"   => $post,
                "config" => $config
            )
        ));
        
    }

    public function saveTemplate(){

         if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["_wpnonce_ajax"]) ) ||
            ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        } 

        $args = array(
            "post_type"  => PostTypeHelper::CPT_TEMPLATE,
            "post_title" => $_POST["name"],
            "post_status" => "publish"
        );

        $templateId = wp_insert_post($args);

        update_post_meta($templateId, PostTypeHelper::META_TEMPLATE_CONFIG, $_POST["config"] );
        
        wp_send_json_success(
            array(
                "code"    => "save_template",
                "results" => $templateId
            )
        );
    }

    public function updateTemplate(){

         if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["_wpnonce_ajax"]) ) ||
            ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ||
            ( !isset($_POST["template_id"]) )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }   

        update_post_meta($_POST["template_id"], PostTypeHelper::META_TEMPLATE_CONFIG, $_POST["config"] );
        
        wp_send_json_success(
            array(
                "code"    => "update_template",
            )
        );
        
    }

    public function removeTemplate(){
          if(
            ( !isset($_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ) ||
            ( !isset($_POST["_wpnonce_ajax"]) ) ||
            ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ||
            ( !isset($_POST["template_id"]) )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }   

        wp_delete_post($_POST["template_id"], true);
        
        wp_send_json_success(
            array(
                "code"    => "delete_template",
            )
        );
    }

  

}
