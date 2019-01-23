<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * EndpointFrontOptin
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EndpointFrontOptin extends AbstractHook implements HooksInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optinStatsServices = $containerServices->getService("OptinStatsServices");
    }


    /**
     * @see HooksFrontInterface
     */
    public function hooks(){
        add_action( 'wp_ajax_stat_optin', array($this, 'statOptin') );
        add_action( 'wp_ajax_nopriv_stat_optin', array($this, 'statOptin') );
    }

    /**
     * @see wp_ajax_stat_optin | wp_ajax_nopriv_stat_optin
     *
     * @return void
     */
    public function statOptin(){

        if(!isset($_POST["optin_id"])){
            wp_send_json_error(
                array(
                    "code"    => "no_optin_id",
                    "results" => "Error"
                )
            );
        }

        $optinId = (int) $_POST["optin_id"];
        
        $result = $this->optinStatsServices->incrementView($optinId);

        if(!$result["success"]){
            wp_send_json_error(
                array(
                    "code"    => "error_increment",
                    "results" => "Error"
                )
            );
        }

        wp_send_json_success(
            array(
                "code" => "stat_optin_success",
                "results" => "View counted"
            )
        );
    }

}
