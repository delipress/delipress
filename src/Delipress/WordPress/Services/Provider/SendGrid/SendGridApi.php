<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\AbstractModel\AbstractProviderApi;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;

use SendGrid;
use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Mail;

/**
 * SendGridApi
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SendGridApi extends AbstractProviderApi {

    protected function returnResponseError($response){

        $body = json_decode($response->body(), true);
        $msg  = (isset($body["errors"][0]["message"])) ? $body["errors"][0]["message"] : "";

        if(!$this->safeError){
            AdminNoticesProviderHelper::registerError(
                ProviderHelper::SENDGRID,
                $msg,
                array(
                    "provider" => ProviderHelper::SENDGRID
                )
            );
        }
        else{

             global $wp_version;

            __delipress__god_save_error(
                array(
                    "title"   => "SendGrid Error",
                    "message" => $msg,
                    "wp_infos" => array(
                        "wp_version"       => $wp_version,
                    ),
                    "home_url"     => home_url(),
                    "php_version"  => PHP_VERSION,
                    "server_infos" => __delipress__god_get_info_server(),
                    "extras"       => $body,
                    "file"         => "sendgrid_api",
                    "line"         => "error_sendgrid_api",
                    "code"         => $response->statusCode()
                )
            );
        }

        return array(
            "success" => false,
            "results" => $body,
        );
    }

    /**
     *
     * @param array $response
     * @return array
     */
    protected function returnResponse($response, $key = ""){

        if($response->statusCode() >= 200 && $response->statusCode() < 400){
            $body = json_decode($response->body(), true);

            return array(
                "success" => true,
                "results" => (empty($key)) ? $body : $body[$key]
            );
        }

        return $this->returnResponseError($response);
    }


    public function getClient(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        return $this->client->client;
    }

    /**
     *
     * @param int $idProvider
     * @return array
     */
    public function getSubscriber($idList = null, $idProvider){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->contactdb()->recipients()->_($idProvider)->get();

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

        $response = $this->getClient()->user()->account()->get();

        return $this->returnResponse($response, "result");
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

        if(!array_key_exists("subject", $params) ){
            throw new \Exception(__("No subject", "delipress"));
        }

        if(!array_key_exists("html", $params) ){
            throw new \Exception(__("No content", "delipress"));
        }

        if(!array_key_exists("emails", $params) && !empty($params["emails"]) ){
            throw new \Exception(__("No emails", "delipress"));
        }


        $from    = new Email(null, $options["options"]["from_to"]);
        $to      = new Email(null, $params["emails"][0]);
        $subject = $params["subject"];
        $content = new Content("text/html", $params["html"]);

        $emailPost  = new Mail($from, $subject, $to, $content);

        unset($params["emails"][0]);

        if(!empty($params["emails"])){
            foreach($params["emails"] as $email){
                $email = new Email(null, $email);
                $emailPost->personalization[0]->addTo($email);
            }
        }


        $response = $this->getClient()->mail()->send()->post($emailPost);

        return $this->returnResponse($response, "result");
    }


    /**
     *
     * @return array
     */
    public function testConnexion(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->api_keys()->get();

        return $this->returnResponse($response, "result");
    }

    /**
     * @param string $apiKey
     * @return SendGridApi
     */
    public function createClient($apiKey){
        $this->client  = new SendGrid($apiKey);

        return $this;
    }

    /**
     * @return SendGridApi
     */
    public function createClientFromOptionServices(){
        $provider = $this->optionServices->getProvider(true);

        $this->api_key       = $provider["api_key_sendgrid"];

        $this->createClient(
            $provider["api_key_sendgrid"]
        );

        return $this;

    }

    /**
     *
     * @return array
     */
    public function getLists($params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->contactdb()->lists()->get();

        return $this->returnResponse($response, "lists");
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

        $response = $this->getClient()->contactdb()->lists()->_($idList)->recipients()->_($params["id"])->delete();

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

        foreach($params["contact"] as $key => $contact){
            $this->deleteSubscriberOnList($idList, $contact);
        }


        return $this->returnResponse($response);

    }

      /**
     *
     * @param array $params
     * @return array
     */
    protected function prepareParamsSubscriberOnList($params){
        $body = array(
            "email" => $params["email"]
        );


        if(isset($params["metas"]["first_name"])){
            $body["first_name"] = (string) $params["metas"]["first_name"];
            unset($params["metas"]["first_name"]);
        }

        if(isset($params["metas"]["last_name"])){
            $body["last_name"] = (string) $params["metas"]["last_name"];
            unset($params["metas"]["last_name"]);
        }


        if(isset($params["metas"])){
            foreach($params["metas"] as $key => $value){
                if(empty($key)){
                    continue;
                }
                $body[$key] = (string) $value;
            }
        }

        return (object) $body;

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
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function createSubscriberOnList($idList, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $body = array(
            $this->prepareParamsSubscriberOnList($params)
        );

        $response = $this->getClient()->contactdb()->recipients()->post($body);

        $response = $this->returnResponse($response);

        if(!$response["success"]){
            return $response;
        }

        $idSubscriber = current($response["results"]["persisted_recipients"]);


        $response = $this->getClient()->contactdb()->lists()->_($idList)->recipients()->_($idSubscriber)->post();

        return $this->returnResponse($response);

    }

    protected function getDataType($type){
        switch($type){
            case "str":
                return "text";
                break;
            default:
                return $type;
        }
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

        $request_body = (object) array(
            "name" => $meta["name"],
            "type" => $this->getDataType($meta["datatype"])
        );

        $response = $this->getClient()->contactdb()->custom_fields()->post($request_body);

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

        $body = array(
            $this->prepareParamsSubscriberOnList($params)
        );

        $response = $this->getClient()->contactdb()->recipients()->patch($body);

        return $this->returnResponse($response);
    }

    /**
     *
     * @param integer $idList
     * @param array $contacts
     * @return array
     */
    public function createSubscribersOnList($idList, $contacts){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $body = array();
        foreach($contacts as $contact){
            $body[] = $this->prepareParamsSubscriberOnList($contact);
        }

        $response = $this->getClient()->contactdb()->recipients()->post($body);

        $response = $this->returnResponse($response);

        if(!$response["success"]){
            return $response;
        }

        if(empty($response["results"]["persisted_recipients"])){
            return $response;
        }

        foreach($response["results"]["persisted_recipients"] as $idSubscriber){
            $response = $this->getClient()->contactdb()->lists()->_($idList)->recipients()->_($idSubscriber)->post();
        }

        return $this->returnResponse($response);

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

        $response = $this->getClient()->contactdb()->lists()->post($list);

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

        $response = $this->getClient()->contactdb()->lists()->_($id)->delete();

        return $this->returnResponse($response);
    }

    public function getSenders(){
        
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->senders()->get();

        return $this->returnResponse($response);
        
    }

    /**
     * @return array
     */
    public function getSenderId(){

        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->senders()->get();


        $response = $this->returnResponse($response);
        
        if(!$response["success"]){
            return $response;
        }

        if(empty($response["results"])){
            return array(
                "success" => false,
                "results" => $response["results"]
            );
        }

        $options  = $this->optionServices->getOptions();
        $fromTo   = $options["options"]["from_to"];

        $result   = array();
        $success  = false;
        foreach($response["results"] as $sender){
            if($sender["from"]["email"] != $fromTo){
                $result[] = $sender["from"]["email"];
                continue;
            }
            $success  = true;
            $result = $sender;
            break;
        }

        return array(
            "success" => $success,
            "results" => $result
        );

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

        $response = $this->getSenderId();
        
        if(!$response["success"]){
            return array(
                "success" => false,
                "results" => array(
                    "code" => "no_sender"
                )
            );
        }

        $options = $this->optionServices->getOptions();

        $body = (object) array(
            'sender_id'      => $response["results"]["id"],
            'list_ids'       => array(
                $params[PostTypeHelper::CAMPAIGN_TAXO_LISTS]->getId()
            ),
            'subject'        => $metas[PostTypeHelper::META_CAMPAIGN_SUBJECT],
            'title'          => $params[PostTypeHelper::CAMPAIGN_NAME]
        );

        $campaignProvider = $metas[PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID];

        if(!empty($campaignProvider)){
            $provider = explode("_", $campaignProvider);

            if($provider[0] === ProviderHelper::SENDGRID && isset($provider[1])){
                $typeSend = get_post_meta($params["id"], PostTypeHelper::META_CAMPAIGN_SEND, true);

                switch($typeSend){
                    case "later":
                        $this->getClient()->campaigns()->_($provider[1])->schedules()->delete();
                        break;
                }

                $response = $this->getClient()->campaigns()->_($provider[1])->patch($body);
            }
            else{
                $response = $this->getClient()->campaigns()->post($body);
            }
        }
        else{
            $response = $this->getClient()->campaigns()->post($body);
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

        $response = $this->getClient()->campaigns()->_($campaign->getCampaignProviderId())->delete();

        return $this->returnResponse($response);
    }

    /**
     * @return array
     */
    public function createUnsusbcribeGroup(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }


        $request_body = (object) array(
            "description" => __("Delete group", "delipress"),
            "name"        => "DeliPress"
        );

        $response = $this->getClient()->asm()->groups()->post($request_body);

        return $this->returnResponse($response);
    }

    /**
     * @return array
     */
    public function getUnsubscribeGroups(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->asm()->groups()->get();

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

        $response = $this->getUnsubscribeGroups();
        if(!$response["success"]){
            return $response;
        }


        $body = array();

        if(empty($response["results"])){
            $response = $this->createUnsusbcribeGroup();

            if(!$response["success"]){
                return $response;
            }

            $body["suppression_group_id"] = $response["results"]["id"];
        }
        else{
            foreach($response["results"] as $group){
                if($group["name"] !== "DeliPress"){
                    continue;
                }

                $body["suppression_group_id"] = $group["id"];
                break;
            }
        }

        if(!isset($body["suppression_group_id"])){
            $response = $this->createUnsusbcribeGroup();

            if(!$response["success"]){
                return $response;
            }

            $body["suppression_group_id"] = $response["results"]["id"];
        }

        $body["html_content"] = $campaign->getHtml();

        $response = $this->getClient()->campaigns()->_($campaign->getCampaignProviderId())->patch((object) $body);
        $response = $this->returnResponse($response);

        if(!$response["success"]){
            $this->returnResponse($response);
        }

        $typeSend = $campaign->getSend();

        switch($typeSend){
            case "now":
                $response = $this->getClient()
                                 ->campaigns()
                                 ->_($campaign->getCampaignProviderId())
                                 ->schedules()
                                 ->now()->post();
                break;
            case "later":
                $timezoneWP = get_option('timezone_string');

                if(empty($timezoneWP)){
                    $timezoneWP = new \DateTimeZone("UTC");
                }
                else{
                    $timezoneWP = new \DateTimeZone($timezoneWP);
                }

                $dateWP     = new \DateTime($campaign->getDateSend(), $timezoneWP);
                $dateWP->setTimezone(new \DateTimeZone("UTC"));
                $date = $dateWP->getTimestamp();

                $request_body = (object) array(
                    "send_at" => $date
                );

                $response = $this->getClient()
                                 ->campaigns()
                                 ->_($campaign->getCampaignProviderId())
                                 ->schedules()
                                 ->post($request_body);
                break;
        }

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

        $response = $this->getClient()->contactdb()->lists()->_($listIdProvider)->get();

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


        $request_body = array(
            "name" => $params[TaxonomyHelper::LIST_NAME]
        );


        $query_params = array(
            "list_id" => $list->getId()
        );

        $response = $this->getClient()->contactdb()->lists()->_($list->getId())->patch((object) $request_body, (object) $query_params);

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
     *
     * @param string $name
     * @return array
     */
    public function getMetaDatas($params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->contactdb()->custom_fields()->get();

        return $this->returnResponse($response);
    }


    /**
     *
     * @param string $id
     * @return array
     */
    public function getMetaData($id){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->getClient()->contactdb()->custom_fields()->_($id)->get();

        return $this->returnResponse($response);
    }



    /**
     *
     * @param string $name
     * @param array $params
     * @return array
     */
    public function getMetaDataByName($name, $params){
        $response = $this->getMetaDatas($params);

        if(!$response["success"]){
            return $response;
        }

        $result  = array(
            "success" => false
        );

        if(empty($response["results"]["custom_fields"])){
            return $result;
        }

        foreach($response["results"]["custom_fields"] as $key => $value){

            if($value["name"] == $name){

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

        $response = $this->getClient()->contactdb()->lists()->_($listIdProvider)->recipients()->get();

        return $this->returnResponse($response, "recipients");
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


        return array(
            "success" => true,
            "results" => array()
        );
    }

}
