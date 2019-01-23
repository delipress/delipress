<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\AbstractModel\AbstractProviderApi;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;

use Sendinblue\Mailin;

/**
 * SendinblueApi
 *
 * @author Delipress
 */
class SendinblueApi extends AbstractProviderApi {

    protected function returnResponseError($response){

        if(!$this->safeError){
            global $wp_version;
            __delipress__god_save_error(
                array(
                    "title"   => "SendinBlue Error",
                    "message" => $response["message"],
                    "wp_infos" => array(
                        "wp_version"       => $wp_version,
                    ),
                    "home_url"     => home_url(),
                    "php_version"  => PHP_VERSION,
                    "server_infos" => __delipress__god_get_info_server(),
                    "extras"       => $response["data"],
                    "file"         => "sendinblue_api",
                    "line"         => "error_sendinblue_api",
                    "code"         => "failure"
                )
            );
        }

        return array(
            "success" => false,
            "results" => $response["message"]
        );
    }

    protected function returnResponse($response){
        if($response["code"] == "failure"){
            return $this->returnResponseError($response);
        }

        return array(
            "success"     => true,
            "results"     => $response["data"],
        );
    }

  
    /**
     *
     * @param int $idList
     * @param string $email
     * @return array
     */
    public function getSubscriber($idList = null, $email){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array(
            "email" => str_replace(" ", "+", $email)
        );

        $response = $this->client->get_user($data);
        
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

        $response = $this->client->get_account();

        return $this->returnResponse($response);
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

        $options = $this->optionServices->getOptions(true);

        $data = array(
            'from'      => $options["options"]["from_to"],
            'subject'   => $params["subject"],
            'html'      => $params["html"],
            'to'        => array()
        );

        if(isset($options["options"]["reply_to"]) ){
            $data["replyto"] = $options["options"]["reply_to"];
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($params["emails"], $data["subject"], $params["html"], $headers);

        return array(
            "success" => true
        );

    }


    /**
     *
     * @return array
     */
    public function testConnexion(){
    
        if(!$this->client){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get_account();

        return $this->returnResponse($response);
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return SendinblueApi
     */
    public function createClient($apiKey){
        $this->client = new Mailin('https://api.sendinblue.com/v2.0', $apiKey, 20000);	
        return $this;
    }

    /**
     * @return SendinblueApi
     */
    public function createClientFromOptionServices(){
        $provider = $this->optionServices->getProvider(true);

        $this->api_key       = $provider["api_key_sendinblue"];

        $this->createClient(
            $provider["api_key_sendinblue"]
        );

        return $this;

    }

    /**
     *
     * @param int $offset
     * @param int $limit
     * @return int
     */
    protected function getPageFromOffset($offset, $limit){
        if($offset < 0){
            $page = 1;
        }
        else{
            $page = ( $offset / $limit ) + 1;
        }

        return $page;
    }

    /**
     *
     * @return array
     */
    public function getLists($params = array()){
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

            
        $limit   = (isset($params["limit"]))  ? $params["limit"]  : 10;
        if($limit == -1){
            $limit = 50;
        }

        if(!isset($params["offset"])){
            $page = 1;
        }
        else{
            $page = $this->getPageFromOffset($params["offset"], $params["limit"]);
        }
        
        $data = array(
            "page"       => $page,
            "page_limit" => $limit
        );

        $response = $this->client->get_lists($data);

        $response = $this->returnResponse($response);
        if(!$response["success"]){
            return $response;
        }

        $response["total_items"] = $response["results"]["total_list_records"];
        $response["results"]     = $response["results"]["lists"];
        
        return $response;

    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function searchSubscribers($params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        return $this->getSubscriber($params["list_id"], $params["email"]);
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

        $data = array( 
            "id" => $idList,
            "users" => array(
                $params["id"]
            )
        );

        $response = $this->client->delete_users_list($data);

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

        $data = array( 
            "id" => $idList,
            "users" => array()
        );

        foreach($params["contact"] as $key => $contact){
            $data["users"][] = $contact["email"];
        }

        $response = $this->client->delete_users_list($data);

        return $this->returnResponse($response);

    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function prepareParamsSubscriberOnList($params){

        if(isset($params["attributes"])){
            foreach($params["attributes"] as $key => $value){
                if(empty($key)){
                    continue;
                }
                
                switch($key){
                    case "first_name":
                        $params["attributes"]["PRENOM"] = $value;
                        unset($params["attributes"][$key]);
                        break;
                    case "last_name":
                        $params["attributes"]["NOM"] = $value;
                        unset($params["attributes"][$key]);
                        break;

                }
            }
        }

        return $params;
    }


    /**
     *
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function createSubscriberOnList($idList, $params){
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( 
            "email"      => $params["email"],
            "attributes" => $params["metas"],
            "listid"     => array($idList),
        );
        $data     = $this->prepareParamsSubscriberOnList($data);

        $response = $this->client->create_update_user($data);

        return $this->returnResponse($response);

    }

    public function getDataType($type){
        switch($type){
            case "str":
            default:
                return "TEXT";
        }

        return $type;
    }

    /**
     *
     * @param string $name
     * @return array
     */
    public function getMetaDatas($params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get_attributes();
        
        return $this->returnResponse($response);
    
    }

     /**
     *
     * @param string $name
     * @return array
     */
    public function getMetaDataByName($name, $params = array()){

        $response = $this->getMetaDatas();
        if(!$response["success"]){
            return $response;
        }

        $result  = array(
            "success" => false
        );

        foreach($response["results"]["normal_attributes"] as $key => $value){
            if($value["name"] == $name) {
                $result = array(
                    "success" => true,
                    "results" => array(
                        $value
                    )
                );
                break;
            }
        }
        
        return $result;
    }


    /**
     *
     * @param array $meta
     * @return array
     */
    public function createMetaData($meta){
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( 
            "type" => "normal",
            "data" => array()
        );
        
        $data["data"][$meta["name"]] = $this->getDataType($meta["datatype"]);

        $response = $this->client->create_attribute($data);

        return $this->returnResponse($response);

    }


    /**
     *
     * @param array $params
     * @return array
     */
    public function editList(ListInterface $list, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( 
            "id"          => $list->getId(),
            "list_name"   => $params[TaxonomyHelper::LIST_NAME],
            "list_parent" => $list->getListParent()
        );

        $this->client->update_list($data);

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

        $data = array( 
            "email"      => $params["email"],
            "attributes" => $params["metas"],
            "listid"     => array($idList),
        );

        $response = $this->client->create_update_user($data);

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

        foreach($contacts as $contact){
            $response = $this->createSubscriberOnList($idList, $contact);
        }

        return $response;

    }

    /**
     * @return void
     */
    public function createFolder($name){
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( 
            "name" => DELIPRESS_ITEM_NAME
        );
 
        $response = $this->client->create_folder($data);

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
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( 
            "page" => 1,
            "page_limit" => 50
        );

        $response = $this->client->get_folders($data);
        
        $response = $this->returnResponse($response);
        
        $response["total_items"] = $response["results"]["total_folder_records"];
        $response["results"]     = $response["results"]["folders"];
        
        return $response;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function createList(ListInterface $list){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $folder = $this->getFolderDeliPress();
        if(!$folder["success"]){
            $folder = $this->createFolder(DELIPRESS_ITEM_NAME);
        }


        $data = array(
            "list_name"   => $list->getName(),
            "list_parent" => $folder["results"]["id"]
        );

        $response = $this->client->create_list($data);

        return $this->returnResponse($response);
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function deleteList($id){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array(
            "id" => $id
        );

        $response = $this->client->delete_list($data);

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

        $options = $this->optionServices->getOptions();

        $campaignModel = new CampaignModel();
        $campaignModel->setCampaignById($params["id"]);


        $data = array( 
            "from_name"  => $options["options"]["from_name"],
            "from_email" => $options["options"]["from_to"],
            "name"       => $params[PostTypeHelper::CAMPAIGN_NAME],
            "listid"     => array(
                $params[PostTypeHelper::CAMPAIGN_TAXO_LISTS]->getId()
            ),
            "subject"       => $metas[PostTypeHelper::META_CAMPAIGN_SUBJECT],
            "reply_to"      => $options["options"]["reply_to"],
            "html_content"  => $campaignModel->getHtml(),
            "mirror_active" => 0,
            "send_now"      => 0
        );
    
        $campaignProvider = $metas[PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID];

        if(!empty($campaignProvider)){
            $provider = explode("_", $campaignProvider);

            if($provider[0] === ProviderHelper::SENDINBLUE && isset($provider[1])){
                $data["id"] = $provider[1];
                $response = $this->client->update_campaign($data);
                if($response["code"] === "success"){
                    $response["data"] = array(
                        "id" => $data["id"]
                    );
                }
            }
            else{
                $response = $this->client->create_campaign($data);
            }
        }
        else{
            $response = $this->client->create_campaign($data);
        }

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

        if($campaign->getSend() === "later"){
            $response = $this->client->update_campaign_status(
                array(
                    "id"     => $campaign->getCampaignProviderId(),
                    "status" => "suspended"
                )
            );
        }

        $response = $this->client->delete_campaign(
            array(
                "id" => $campaign->getCampaignProviderId()
            )
        );

        return $this->returnResponse($response);
    }

    public function getSenders(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }
    
        $response = $this->client->get_senders(array( "option" => "" ));

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

        $data = array(
            "id" => $campaign->getCampaignProviderId()
        );

        $typeSend = $campaign->getSend();
        switch($typeSend){
            case "now":
                $data["send_now"] = 1;
                break;
            case "later":
                $data["send_now"] = 0;
                $timezoneWP = get_option('timezone_string');

                if(empty($timezoneWP)){
                    $timezoneWP = new \DateTimeZone("UTC");
                }
                else{
                    $timezoneWP = new \DateTimeZone($timezoneWP);
                }

                $dateWP     = new \DateTime($campaign->getDateSend(), $timezoneWP);
                $dateWP->setTimezone(new \DateTimeZone("UTC"));
                $date = $dateWP->format("Y-m-d H:i:s");
                
                $data["scheduled_date"] = $date;
                break;
        }

        $response = $this->client->update_campaign($data);

        return $this->returnResponse($response);
    }


    /**
     * Get list
     *
     * @param int $listIdProvider
     * @return array
     */
    public function getList($listIdProvider){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $data = array( "id" => $listIdProvider );
        $response = $this->client->get_list($data);

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
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }
        
        $page = $this->getPageFromOffset($offset, $limit);

        $data = array( 
            "listids"    => array($listIdProvider),
            "page"       => $page,
            "page_limit" => $limit
        );
        
        $response = $this->client->display_list_users($data);

        $response = $this->returnResponse($response);
        if(!$response["success"]){
            return $response;
        }
        $response["total_items"] = $response["results"]["total_list_records"];
        $response["results"]     = $response["results"]["data"];

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

        $response = $this->client->get_campaign_v2(array(
            "id" => $campaignId
        ));

        return $this->returnResponse($response);
    }

}
