<?php

namespace Delipress\WordPress\Front;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\DataEncoder;

use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Models\SubscriberModel;

/**
 * PageConfirmSubscribe
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class PageConfirmSubscribe extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->confirmSubscribeServices           = $containerServices->getService("ConfirmSubscribeServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action("init", array($this,"requestConfirmSubscribe"));
        //add_action("wp_footer", array($this, "headConfirmation"));
    }


    /**
     * @action DELIPRESS_SLUG . "_before_page_confirm_subscribe"
     * @action DELIPRESS_SLUG . "_after_page_confirm_subscribe"
     *
     * @return void
     */
    public function requestConfirmSubscribe(){

        if(
            !isset( $_GET["action"]) ||
            !isset($_GET["data"] ) ||
            !isset($_GET["_wpnonce"])
        ){
            return;
        }

        $data = DataEncoder::decodeData($_GET["data"]);

        if(
            empty($data) ||
            !array_key_exists("list_ids", $data) ||
            !array_key_exists("email", $data) ||
            !$data["list_ids"] ||
            !$data["email"] ||
            ! wp_verify_nonce($_GET["_wpnonce"], ActionHelper::CONFIRM_SUBSCRIBE)
        ){
            return;
        }

        $this->confirmSubscribeServices->confirmSubscriber($data["email"], $data["list_ids"], $data);

        add_action("wp_footer", array($this, "headConfirmation"));

    }

    public function headConfirmation(){
        ?>
        <div id="DELI-confirm">
          <?php _e("Your subscription has been confirmed! Thank you! ", "delipress"); ?>
        </div>
        <style>
            #DELI-confirm{position:fixed; z-index:100000; background:#92DAB8; color:white; top:0; left:0; right:0; text-align: center; padding:10px 0; transition:.6s all ease-out;font-size:1.1em;}
            #DELI-confirm.DELI-disappear{top:-100px;}
        </style>
        <script>
            var deliConfirm = document.getElementById("DELI-confirm");
            setTimeout(function(){
                deliConfirm.className = "DELI-disappear";
            }, 4000);
        </script>
        <?php
    }

}
