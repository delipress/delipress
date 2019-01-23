<?php

namespace Delipress\WordPress\Connectors;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


use DeliSkypress\Models\ContainerInterface;

use Delipress\WordPress\Models\AbstractModel\AbstractConnectorHook;
use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Models\SubscriberModel;

use Delipress\WordPress\Helpers\ConnectorHelper;


/**
 * WooCommerceUser
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WooCommerceUser extends AbstractConnectorHook {

    protected $connector = ConnectorHelper::WOOCOMMERCE;

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        parent::setContainerServices($containerServices);
        $this->woocommerceServices    = $containerServices->getService("WooCommerceServices");
        
    }


    /**
     * @see HooksInterface
     *
     * @return void
     */
    public function hooks(){
        
        add_action( 'user_register', array($this, 'userRegisterConnector'));
        add_action('edit_user_profile_update', array($this, 'userEditConnector'));
        add_action('profile_update', array($this, 'userEditConnector'));
        add_action( 'delete_user', array($this, 'userDeleteConnector') ); 
    }

    protected function createUserFromPostData(){

        $connector = $this->optionServices->getConnectorByKey($this->connector);
        
        $params    = $this->woocommerceServices->constructParamsFromHook();

        $this->synchronizeSubscriberServices
             ->synchronizeSubscriberOnList($connector["list_id"], $params, true);
        
    }

    public function userRegisterConnector(){
        $connector = $this->optionServices->getConnectorByKey($this->connector);

        if(!$connector["active"]){
            return;
        }

        if(!isset($_POST["email"]) || !isset($_POST["role"])){
            return;
        } 
        
        if($_POST["role"] !== "customer") {
            return;
        }

        $this->createUserFromPostData();

    }

    /**
     *
     * @param int $userId
     * @return void
     */
    public function userEditConnector($userId){
        $connector = $this->optionServices->getConnectorByKey($this->connector);
        if(!$connector["active"]){
            return;
        }

        $user = get_user_by("ID", $userId);
        
        if(!$user){
            $this->createUserFromPostData();
            return;
        }

        if(!isset($_POST["email"]) || !isset($_POST["role"])){
            return;
        } 
        
        if($_POST["role"] !== "customer") {
            return;
        }

        $emailCurrent = $this->woocommerceServices->getEmailSubscriberFromSubscribersResult($user);

        // Change email
        if($emailCurrent != $_POST["email"]){
            $response = $this->subscriberServices->searchSubscriber($connector["list_id"], $emailCurrent);

            if($response["success"]){
                $list = $this->listServices->getList($connector["list_id"]);
                $this->deleteSubscriberServices->deleteSubscriberOnList($list, $response["results"], true);
            }
            
            $this->createUserFromPostData();
            return;

        }

        $response = $this->subscriberServices->searchSubscriber($connector["list_id"], $emailCurrent);

        if(!$response["success"]){
            $this->createUserFromPostData();
            return;
        }
        
        $params = $this->woocommerceServices->constructParamsFromHook();
        $this->synchronizeSubscriberServices
             ->editSynchronizeSubscriberOnList($connector["list_id"], $response["results"]->getId(), $params, true);

    }

    /**
     * @see delete_user
     *
     * @param int $userId
     * @return void
     */
    public function userDeleteConnector($userId){
        $connector = $this->optionServices->getConnectorByKey($this->connector);
        if(!$connector["active"]){
            return;
        }

        $user = get_user_by("ID", $userId);

        if(!$user){
            return;
        }

        $authorize = in_array("customer",$user->roles);
            
        if(!$authorize){
            return;
        }
        
        $emailCurrent = $this->woocommerceServices->getEmailSubscriberFromSubscribersResult($user);

        $response     = $this->subscriberServices->searchSubscriber($connector["list_id"], $emailCurrent);

        if(!$response["success"]){
            return;
        }
        
        $list = $this->listServices->getList($connector["list_id"]);

        $this->deleteSubscriberServices->deleteSubscriberOnList($list, $response["results"], true);

    }


}



