<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ActionHelper;

/**
 * EndpointTerms
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EndpointTerms extends AbstractHook implements HooksAdminInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){

    }

    
    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(current_user_can('manage_options' ) ){
            add_action( 'wp_ajax_delipress_get_terms', array($this, 'getTerms') );
        }
    }


    /**
     * @filter DELIPRESS_SLUG . "_endpoint_get_terms"
     * 
     * @return JSON Response
     */
    public function getTerms(){

        if(
            !isset( $_POST["_wpnonce_ajax"] ) ||
            !isset( $_POST["taxonomy"] )
        ){ 
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                ) 
            );
            exit;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                ) 
            );
            exit;
        }

        $taxonomy = sanitize_text_field($_POST["taxonomy"]);
        $search   = ( isset($_POST["name__like"] ) ) ? sanitize_text_field($_POST["name__like"]) : "";
        $offset   = ( isset($_POST["offset"] ) ) ? (int) $_POST["offset"] : 0;
        
        $args  = array(
            "taxonomy"     => $taxonomy,
            "name__like"   => $search,
            "offset"       => $offset,
            "number"       => 20
        );

        $args       = apply_filters(DELIPRESS_SLUG . "_endpoint_get_terms", $args);
		$terms      = get_terms($args);
        
        $countTerms = wp_count_terms($taxonomy);

		wp_send_json_success(
            array(
                "code"        => "get_terms",
                "results"     => $terms,
                "total_count" => (int) $countTerms
            )
        );

    }


}
