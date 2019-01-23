<?php

namespace Delipress\WordPress\Admin\Import;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

/**
 * ImportSubscriber
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ImportSubscriber extends AbstractHook implements HooksAdminInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->subscriberServices          = $containerServices->getService("SubscriberServices");
        $this->importSubscriberServices    = $containerServices->getService("ImportSubscriberServices");
        $this->listServices                = $containerServices->getService("ListServices");
    }


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::IMPORT_SUBSCRIBER_STEP_ONE, array($this, 'importSubscriber') );
            add_action( 'admin_post_' . ActionHelper::IMPORT_SUBSCRIBER_STEP_TWO, array($this, 'importSubscriberStepTwo') );
        }
    }

    /**
     * @return void
     */
    public function importSubscriber(){
        if(
            !isset($_GET["_wpnonce"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::IMPORT_SUBSCRIBER_STEP_ONE ) ||
            ! isset( $_FILES['import'] ) ||
            ! isset($_POST["delipress_import_confirm"])
        ){
            wp_nonce_ays( '' );
        }


        if($_FILES["import"]["size"] === 0){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                __("File is empty", "delipress")
            );
            $url = $this->subscriberServices->getPageSubscribersImport(null);
            wp_redirect($url);
            exit;
        }

        if(
            (!isset($_POST["list_id"]) || empty($_POST["list_id"]) ) &&
            (!isset($_POST["create_list"]) || empty($_POST["create_list"]) )
        ){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                __("Create a list or choose existing one", "delipress")
            );
            $url = $this->subscriberServices->getPageSubscribersImport(null);
            wp_redirect($url);
            exit;
        }

        $delimiter = ",";
        if(isset($_POST["delimiter"])){
            $delimiter = $_POST["delimiter"];
        }
        $delimiter = apply_filters(DELIPRESS_SLUG . "_delimiter_import_file", $delimiter);

        $result = $this->importSubscriberServices
                        ->setDelimiter($delimiter)
                        ->setFile($_FILES)
                        ->prepareImport();

        $url = $this->subscriberServices->getPageSubscribersImport(null, 2);
        if(!$result["success"]){
            $url = $this->subscriberServices->getPageSubscribersImport();
        }


        wp_redirect($url);
        exit;

    }

    /**
     * @see admin_post_' . ActionHelper::IMPORT_SUBSCRIBER_STEP_TWO
     */
    public function importSubscriberStepTwo(){

        if(
            !isset($_GET["_wpnonce"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::IMPORT_SUBSCRIBER_STEP_TWO )
        ){
            wp_nonce_ays( '' );
        }

        $nextStep = (int) $_POST["next_step"];

        if($nextStep === 1){
            $url = $this->subscriberServices->getPageSubscribersImport(null, 1);
            wp_redirect($url);
            exit;
        }

        $result = $this->importSubscriberServices
                       ->execute();

        if($result["success"]){
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                $result["results"]["message"]
            );
        }
        else{
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                $result["results"]["message"]
            );
        }

        $url = $this->listServices->getPageListUrl();

        wp_redirect($url);
        exit;
    }

}
