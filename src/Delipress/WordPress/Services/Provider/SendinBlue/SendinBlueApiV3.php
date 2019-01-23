<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\AbstractModel\AbstractProviderApi;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\AccountApi;
use SendinBlue\Client\Api\AttributesApi;
use SendinBlue\Client\Api\ContactsApi;
use SendinBlue\Client\Model\CreateList;
use SendinBlue\Client\Model\CreateUpdateFolder;
use SendinBlue\Client\Model\CreateContact;
use SendinBlue\Client\Model\CreateAttribute;

/**
 * SendinblueApi
 *
 * @author Delipress
 */
class SendinblueApiV3 extends AbstractProviderApi {

    protected function returnResponseError($response){

        if(!$this->safeError){
            global $wp_version;
            __delipress__god_save_error(
                array(
                    "title"   => "SendinBlue Error",
                    "message" => $response->getMessage(),
                    "wp_infos" => array(
                        "wp_version"       => $wp_version,
                    ),
                    "home_url"     => home_url(),
                    "php_version"  => PHP_VERSION,
                    "server_infos" => __delipress__god_get_info_server(),
                    // "extras"       => $body,
                    "file"         => "sendinblue_api",
                    "line"         => "error_sendinblue_api",
                    "code"         => $response->getCode()
                )
            );
        }

        return array(
            "success" => false,
            "results" => $response->getMessage()
        );
    }

    protected function returnResponse($response){
        return array(
            "success"     => true,
            "results"     => $response,
        );
    }

  
    /**
     *
     * @param int $idList
     * @param string $email
     * @return array
     */
    public function getSubscriber($idList = null, $email){
        $this->createClientFromOptionServices("lists");

        $email = str_replace(" ", "+", $email);
        try{
            $response = $this->client->getContactInfo($email);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     * Get user info
     *
     * @return array
     */
    public function getUser(){
         if($this->client === null){
            $this->createClientFromOptionServices();
        }

        try{
            $response = $this->client->getAccount();
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function sendCampaignTestEmail($params){
        // return $this->sendEmail($params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function sendEmail($params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }


    /**
     *
     * @return array
     */
    public function testConnexion(){
        
        $this->createClientFromOptionServices();

        try{
            $response = $this->client->getAccount();
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return SendinblueApi
     */
    public function createClient($apiKey, $type = null){
        Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        
        switch($type){
            default:
            case "account":
                $this->client  = new AccountApi();
                break;
            case "lists":
                $this->client  = new ContactsApi();
                break;
            case "attributes":
                $this->client = new AttributesApi();
                break;
        }

        return $this;
    }

    /**
     * @return SendinblueApi
     */
    public function createClientFromOptionServices($type = null){
        $provider = $this->optionServices->getProvider(true);

        $this->api_key       = $provider["api_key_sendinblue"];

        $this->createClient(
            $provider["api_key_sendinblue"],
            $type
        );

        return $this;

    }

    /**
     *
     * @return array
     */
    public function getLists($params = array()){
        
        $this->createClientFromOptionServices("lists");

        try{
            $offset  = (isset($params["offset"])) ? $params["offset"] : 0;
            $limit   = (isset($params["limit"]))  ? $params["limit"]  : 10;
            
            $response = $this->client->getLists($limit, $offset);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }
        
        $response = $this->returnResponse($response);

        $response["results"] = $response["results"]->getLists();
        
        return $response;
    }



    /**
     *
     * @param int $idList
     * @param array $params
     * @return array
     */
    public function deleteSubscriberOnList($idList, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }

    /**
     *
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function deleteSubscribersOnList($idList, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);

    }

    protected function prepareContactData($idList, $params){

        $createContact = new CreateContact();
        $createContact->setListIds(
            array(
                $idList
            )
        );
        $createContact->setEmail($params["email"]);
        
        $attributes = array();
        if(isset($params["metas"]) && isset($params["metas"]["first_name"])){
        
            $attributes["FNAME"] = $params["metas"]["first_name"];
            unset($params["metas"]["first_name"]);
        }
        if(isset($params["metas"]) && isset($params["metas"]["last_name"])){
            $attributes["LNAME"] = $params["metas"]["last_name"];
            unset($params["metas"]["last_name"]);
        }

        if(!empty($params["metas"])){
            $attributes = array_merge($attributes, $params["metas"]);
        }

        $createContact->setAttributes($attributes);

        return $createContact;
    }

    /**
     *
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function createSubscriberOnList($idList, $params){

        $this->createClientFromOptionServices("lists");

        $createContact = $this->prepareContactData($idList, $params);

        try{
            $response = $this->client->createContact($createContact);
        }
        catch(\Exception $e){
            // var_dump($e); die;
            return $this->returnResponseError($e);
        }
        
        // var_dump($response); die;

        return $this->returnResponse($response);

    }

    public function getDataType($type){
        switch($type){
            case "str":
                return "text";
        }

        return $type;
    }


    /**
     *
     * @param array $meta
     * @return array
     */
    public function createMetaData($meta){
        
        $this->createClientFromOptionServices("lists");

        $createAttribute = new CreateAttribute();
        $createAttribute->setName($meta["name"]);
        $createAttribute->setValue($meta["name"]);
        $createAttribute->setType($this->getDataType($meta["datatype"]));
        $createAttribute->setCategory("normal");

        try{
            $response = $this->client->createAttribute($createAttribute);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);

    }

    /**
     *
     * @param integer $idList
     * @param integer $idSubscriber
     * @param array $params
     * @return array
     */
    public function editSubscriberOnList($idList, $idSubscriber, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }

    /**
     *
     * @param integer $idList
     * @param array $emails
     * @return array
     */
    public function createSubscribersOnList($idList, $contacts){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);

    }

    /**
     * @return void
     */
    public function createFolder($name){
        
        $this->createClientFromOptionServices("lists");

        $createFolder = new CreateUpdateFolder();
        $createFolder->setName($name);

        try{
            $response = $this->client->createFolder($createFolder);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     * @return array
     */
    public function getFolderDeliPress(){
        $folders = $this->getFolders();
        
        $results = array(
            "success" => false,
            "results" => array()
        );

        foreach($folders["results"] as $folder){
            if($folder["name"] !== DELIPRESS_ITEM_NAME){
                continue;
            }
            
            $results["success"] = true;
            $results["results"] = $folder;
        }

        return $results;
    }


    /**
     *
     * @return array
     */
    public function getFolders(){
        $this->createClientFromOptionServices("lists");

        try{
            $response = $this->client->getFolders(50, 0);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        $response = $this->returnResponse($response);
        $response["results"] = $response["results"]["folders"];

        return $response;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function createList(ListInterface $list){
        $this->createClientFromOptionServices("lists");

        $createList = new CreateList();
        $createList->setName($list->getName());

        $folder = $this->getFolderDeliPress();
        if(!$folder["success"]){
            $folder = $this->createFolder(DELIPRESS_ITEM_NAME);
        }

        $createList->setFolderId($folder["results"]["id"]);

        try{
            $response = $this->client->createList($createList);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function deleteList($id){
        $this->createClientFromOptionServices("lists");

        try{
            $response = $this->client->deleteList($id);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        return $this->returnResponse($response);
    }

    /**
     *
     * @param array $params
     * @param array $metas
     * @return array
     */
    public function createDraftCampaign($params, $metas){

        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API


        return $this->returnResponse($response);
    }

    /**
     *
     * @param CampaignModel $campaign
     * @return array
     */
    public function deleteCampaign(CampaignModel $campaign){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }

    /**
     *
     * @param CampaignModel $campaign
     * @return array
     */
    public function sendCampaign(CampaignModel $campaign){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }


    /**
     * Get list
     *
     * @param int $listIdProvider
     * @return array
     */
    public function getList($listIdProvider){
        $this->createClientFromOptionServices("lists");

        try{
            $response = $this->client->getList($listIdProvider);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }
        
        return $this->returnResponse($response);
    }

    /**
     *
     * @param int $idSubscriber
     * @param array $params
     * @return array
     */
    public function getContactMetaData($idSubscriber, $params = array()){

        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }


    /**
     * Get contacts from list
     *
     * @param int $listIdProvider
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getListContacts($listIdProvider, $offset = 0, $limit = 500){
        
        $this->createClientFromOptionServices("lists");

        try{
            $response = $this->client->getContactsFromList($listIdProvider, null, $limit, $offset);
        }
        catch(\Exception $e){
            return $this->returnResponseError($e);
        }

        $response = $this->returnResponse($response);
        $response["results"] = $response["results"]["contacts"];

        return $response;

    }

    /**
     *
     * @param int $campaignId
     * @return array
     */
    public function getCampaignStatistics($campaignId){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        // CALL API

        return $this->returnResponse($response);
    }

}
