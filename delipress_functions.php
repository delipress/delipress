<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Models\SubscriberModel;

/**
 * @return bool
 */
function checkDelipressPluginExist(){
    global $delipressPlugin;

    if(!isset($delipressPlugin) ) {
        return false;
    }

    return true;
}

/**
 *
 * @param string $name
 *
 * @param array $params
 * @example $metas = array(
 *      TaxonomyHelper::META_LIST_COLOR => "#ffffff"
 * )
 *
 * @return array
 */
function delipress_create_list($name, $metas = array()){

    if(!checkDelipressPluginExist()){
        return null;
    }

    if(empty($name)){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("Name is empty", "delipress")
            )
        );
    }

    $params = array(
        TaxonomyHelper::LIST_NAME => $name
    );

    global $delipressPlugin;

    return $delipressPlugin->getService("CreateListServices")->createListStandalone($params, $metas);

}

/**
 *
 * @param string $email
 * @param int|string $listId
 *
 * @param array $metas
 * @example $metas = array(
 *      "first_name" => "John",
 *      "last_name"  => "Doe"
 * )
 *
 * @return array
 */
function delipress_create_subscriber_on_list($email, $listId, $metas = array()){

    if(!checkDelipressPluginExist()){
        return null;
    }

    if(empty($email)){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("Email is empty", "delipress")
            )
        );
    }

    if(empty($listId)){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("List id is empty", "delipress")
            )
        );
    }

    global $delipressPlugin;

    $createSubscriberServices = $delipressPlugin->getService("CreateSubscriberServices");

    $params = array(
        "email"   => $email,
        "metas"   => $metas,
        "list_id" => $listId
    );


    return $createSubscriberServices->createSubscriberOnListStandalone($listId, $params);

}



/**
 *
 * @param int|string $listId
 * @param int|string $subscriberId
 *
 * @param array $params
 * @example $params = array(
 *      "email" => "support@delipress.io"
 * )
 *
 * @param array $metas
 * @example $metas = array(
 *      "first_name" => "John",
 *      "last_name"  => "Doe"
 * )
 *
 * @return array
 */
function delipress_edit_subscriber_on_list($listId, $subscriberId, $params, $metas = array() ){

    if(!checkDelipressPluginExist()){
        return null;
    }

    if(empty($listId)){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("List id is empty", "delipress")
            )
        );
    }

    if(empty($subscriberId)){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("Subscriber id is empty", "delipress")
            )
        );
    }

    global $delipressPlugin;

    $synchronizeSubscriberServices = $delipressPlugin->getService("SynchronizeSubscriberServices");
    $listServices                  = $delipressPlugin->getService("ListServices");
    $subscriberServices            = $delipressPlugin->getService("SubscriberServices");

    $list       = $listServices->getList($listId);
    if(!$list){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("List doesn't exist", "delipress")
            )
        );
    }
    $subscriber = $subscriberServices->getSubscriberByList($list->getId(), $subscriberId);

    if(!$subscriber){
        return array(
            "success" => false,
            "results" => array(
                "message" => __("Subscriber doesn't exist", "delipress")
            )
        );
    }

    $params = array_merge(
        $params,
        array(
            "list"       => $list,
            "subscriber" => $subscriber
        )
    );

    $params["metas"] = $metas;

    return $synchronizeSubscriberServices->editSubscriberSynchronizeOnList($list, $subscriber, $params);

}


/**
 *
 * @param array $args
 * @param boolean $safeError
 * 
 * @return array
 */
function delipress_get_lists($args = array(), $safeError = false){

    if(!checkDelipressPluginExist()){
        return null;
    }

    global $delipressPlugin;

    return $delipressPlugin->getService("ListServices")->getLists($args, $safeError);
    
}
