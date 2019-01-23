<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;

class EndpointSubscriber extends AbstractHook implements HooksAdminInterface{


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){
        add_action( 'wp_ajax_delipress_count_subscriber_dynamic_list', array($this, 'countSubscriberDynamicList') );
    }


    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->createDynamicListServices      = $containerServices->getService("CreateDynamicListServices");
    }


    /**
     * @action DELIPRESS_SLUG . '_before_endpoint_count_subscribers_dynamic_list'
     * @action DELIPRESS_SLUG . '_after_endpoint_count_subscriber_dynamic_list'
     */
    public function countSubscriberDynamicList(){

        if(!isset($_POST["specification"]) || empty($_POST["specification"])){
            wp_send_json_success(
                array(
                    "code"    => "count_subscriber_dynamic_list",
                    "results" => array(
                        "total" => 0,
                        "duration_msg" => 0
                    )
                )
            );
        }
        
        do_action(DELIPRESS_SLUG . '_before_endpoint_count_subscribers_dynamic_list');

        $result = $this->createDynamicListServices->countDynamicList($_POST["specification"]);

        do_action(DELIPRESS_SLUG . '_after_endpoint_count_subscriber_dynamic_list');
        $timeMS = 10000;
        $nbData = 150;
        $msg    = __('Please note: this list may take around %d %s to be created', 'delipress');
        $second = __("second(s)", "delipress");
        $minute = __("minute(s)", "delipress");

        $duration = ($result["total"]  * $timeMS) / $nbData;
        
        $result["duration_msg"] = null;

        if($duration >= 5000 && $duration <= 60000){
            $result["duration_msg"] = sprintf($msg, $duration / 1000, $second);
        }
        else if($duration > 60000){
            $time = round($duration / 60000);
            $result["duration_msg"] = sprintf($msg, $time, $minute);
        }

        wp_send_json_success(
            array(
                "code"    => "count_subscriber_dynamic_list",
                "results" => $result
            )
        );
    }

  

}
