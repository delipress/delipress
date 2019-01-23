<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\AbstractModel\AbstractProviderApi;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;

use Delipress\WordPress\Services\Provider\Mailjet\MailjetApiV1;

use Mailjet\Resources;
use Delipress\WordPress\Services\Provider\Mailjet\Override\MailjetClient;


/**
 * MailjetApi
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class MailjetApi extends AbstractProviderApi {

    protected function returnResponse($response){
        $status = $response->getStatus();
        if($response->success() || $status == 304){

            return array(
                "success"     => true,
                "results"     => $response->getData(),
                "status"      => $response->getStatus(),
                "total_items" => $response->getCount()
            );
        }
        else{

            $body = $response->getBody();

            $errMessage = "";
            if(empty($body) ) {
                $errMessage = $response->getReasonPhrase();
            }
            else{
                $errMessage = $body["ErrorMessage"];
            }

            if($this->safeError){
                return array(
                    "success" => false,
                    "results" => $response->getData(),
                    "status"  => $response->getStatus()
                );
            }

            global $wp_version;
            __delipress__god_save_error(
                array(
                    "title"   => "Mailjet Error",
                    "message" => $errMessage,
                    "wp_infos" => array(
                        "wp_version"       => $wp_version,
                    ),
                    "home_url"     => home_url(),
                    "php_version"  => PHP_VERSION,
                    "server_infos" => __delipress__god_get_info_server(),
                    "extras"       => $body,
                    "file"         => "mailjet_api",
                    "line"         => "error_mailjet_api",
                    "code"         => $body["StatusCode"]
                )
            );

            if($errMessage === "Wrong API version"){
                $errMessage = __("<strong>Warning:</strong> your Mailjet API credentials are not working with Mailjet API V3. <a target='_blank' href='https://app.mailjet.com/support/pourquoi-ai-je-obtenu-une-erreur-api-en-essayant-dactiver-un-plugin-mailjet,498.htm'>You must contact the Mailjet Support to migrate your account</a>", "delipress");
            }

            AdminNoticesProviderHelper::registerError(
                ProviderHelper::MAILJET,
                $errMessage,
                array(
                    "provider" => ProviderHelper::MAILJET
                )
            );

            return array(
                "success" => false,
                "results" => $response->getData(),
                "status"  => $response->getStatus()
            );
        }
    }


    /**
     *
     * @param int $idList
     * @param int $idProvider
     * @return array
     */
    public function getSubscriber($idList = null, $idProvider){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$Contact,
            array(
                "ID" => $idProvider
            )
        );

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

        $response = $this->client->get(Resources::$User);

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

        if(!array_key_exists("subject", $params) ){
            throw new \Exception(__("No subject", "delipress"));
        }

        if(!array_key_exists("html", $params) ){
            throw new \Exception(__("No content", "delipress"));
        }

        if(!array_key_exists("emails", $params) ){
            throw new \Exception(__("No emails", "delipress"));
        }

        $body = array(
            'FromEmail' => $options["options"]["from_to"],
            'FromName'  => (array_key_exists("from_name", $params) ) ? $params["from_name"] : "",
            'Subject'   => $params["subject"],
            'Html-part' => $params["html"],
            'Recipients' => array()
        );

        foreach($params["emails"] as $key => $email){
            $body["Recipients"][] = array(
                'Email' => $email
            );
        }

        if(isset($options["options"]["reply_to"]) ){
            $body["Headers"]["Reply-To"] = $options["options"]["reply_to"];
        }


        $response = $this->client->post(Resources::$Email,
            array(
                "body" => $body
            )
        );

        return $this->returnResponse($response);
    }


    /**
     *
     * @return array
     */
    public function testConnexion(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$User);

        return $this->returnResponse($response);
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return MailjetApi
     */
    public function createClient($clientId, $clientSecret){
        $this->client = new MailjetClient($clientId, $clientSecret);

        $this->client_id     = $clientId;
        $this->client_secret = $clientSecret;

        return $this;
    }

    /**
     * @return MailjetApi
     */
    public function createClientFromOptionServices(){
        $provider = $this->optionServices->getProvider(true);

        $this->client_id     = $provider["client_id"];
        $this->client_secret = $provider["client_secret"];

        $this->createClient(
            $provider["client_id"],
            $provider["client_secret"]
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

        $response = $this->client->get(Resources::$Contactslist, array(
            "filters" => array(
                "Offset" => (isset($params["offset"])) ? $params["offset"] : 0,
                "Limit"  => (isset($params["limit"])) ? $params["limit"] : 10,
            )
        ));


        return $this->returnResponse($response);
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

        if(!array_key_exists("email", $params) ){
            throw new \Exception(__("No subscriber email", "delipress"));
        }

        $body = array(
            "Email" => $params["email"],
            "Action" => "remove"
        );

        $response = $this->client->post(Resources::$ContactslistManagecontact,
            array(
                'id'   => $idList,
                'body' => $body
            )
        );

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

        $body = array(
            "Action" => "remove"
        );

        foreach($params["contact"] as $key => $contact){

            if(!array_key_exists("email", $params) ){
                throw new \Exception(__("No subscriber email", "delipress"));
            }

            $body["Contacts"][] = array(
                "Email"  => $contact["email"],
                "Action" => "remove"
            );
        }

        $response = $this->client->post(Resources::$ContactslistManagemanycontacts,
            array(
                'id'   => $idList,
                'body' => $body
            )
        );

        return $this->returnResponse($response);

    }

    /**
     *
     * @param array $params
     * @return array
     */
    protected function prepareParamsSubscriberOnList($params){
        $body = array(
            "Action" => "addnoforce"
        );

        if(isset($params["email"])){
            $body["Email"] = $params["email"];
        }

        $name = "";
        if(isset($params["first_name"])){
            $name .= $params["first_name"];
        }
        else if(isset($params["metas"]) && isset($params["metas"]["first_name"])){
            $name .= $params["metas"]["first_name"];
        }
        
        if(isset($params["last_name"])){
            $name .= $params["last_name"];
        }
        else if(isset($params["metas"]) && isset($params["metas"]["last_name"])){
            $name .= $params["metas"]["last_name"];
        }

        $body["Name"] = $name;

        if(isset($params["metas"])){
            foreach($params["metas"] as $key => $value){
                if(empty($key)){
                    continue;
                }

                $body["Properties"][$key] = $value;
            }
        }

        return $body;

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

        if(!array_key_exists("email", $params) ){
            throw new \Exception(__("No subscriber email", "delipress"));
        }

        $body = $this->prepareParamsSubscriberOnList($params);
        $body = apply_filters(DELIPRESS_SLUG . "_mailjet_api_subscriber_on_list", $body, $idList, $params);

        $response = $this->client->post(Resources::$ContactslistManagecontact,
            array(
                'id'   => $idList,
                'body' => $body
            )
        );

        $response = $this->returnResponse($response);

        return $response;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function prepareContactData($params){
        $body = array();

        if(isset($params["metas"])) {
            foreach($params["metas"] as $key => $value){
                $body[] = array(
                    "Name"  => $key,
                    "value" => $value
                );
            }
        }

        return $body;
    }

    /**
     *
     * @param array $meta
     * @return array
     */
    public function createMetaData($meta, $params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->post(Resources::$Contactmetadata,
            array(
                "body" => array(
                    "Datatype"  => $meta["datatype"],
                    "Name"      => $meta["name"],
                    "NameSpace" => $meta["type"]
                )
            )
        );

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

        $response = $this->client->get(Resources::$Contactmetadata, array(
            "filters" => array(
                "limit" => -1
            )
        ));

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

        $response = $this->client->get(Resources::$Contactmetadata, array(
            "ID" => $id
        ));

        return $this->returnResponse($response);
    }


    /**
     *
     * @param string $name
     * @param array $params
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


        foreach($response["results"] as $key => $value){
            if($value["Name"] == sanitize_title($name) ) {

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
     * @param integer $idList
     * @param integer $idSubscriber
     * @param array $params
     * @return array
     */
    public function editSubscriberOnList($idList, $idSubscriber, $params) {

        if($this->client === null){
            $this->createClientFromOptionServices();
        }
        
        $body = $this->prepareContactData($params);
        $body = apply_filters(DELIPRESS_SLUG . "_mailjet_api_edit_subscriber_on_list", $body, $params, $idList, $idSubscriber);

        $response = $this->client->put(Resources::$Contactdata,
            array(
                "ID" => $idSubscriber,
                "body" => array(
                    "Data"      => $body
                )
            )
        );

        return $this->returnResponse($response);
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

        $response = $this->client->get(Resources::$Listrecipient,
            array(
                'filters' => array(
                    "style"          => "full",
                    "ContactsList"   => $params["list_id"],
                    "ContactEmail"   => $params["email"]
                )
            )
        );


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

        $body = array(
            "Action" => "addnoforce"
        );

        foreach($contacts as $key => $contact){
            $subscriber = $this->prepareParamsSubscriberOnList($contact);
            
            $body["Contacts"][]   = $subscriber;
        }

        $response = $this->client->post(Resources::$ContactslistManagemanycontacts,
            array(
                'ID'   => $idList,
                'body' => $body
            )
        );
        
        $response =  $this->returnResponse($response);

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

        $body = array(
            'Name' => $list->getName()
        );

        $response = $this->client->post(Resources::$Contactslist, array('body' => $body) );

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

        $body = array(
            'Name' => $params[TaxonomyHelper::LIST_NAME]
        );

        $response = $this->client->put(Resources::$Contactslist, 
            array(
                'id' => $list->getId(),
                'body' => $body
            ) 
        );

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

        $response = $this->client->delete(Resources::$Contactslist, array('ID' => $id) );

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

        $body = array(
            'Locale'         => get_locale(),
            'Sender'         => $options["options"]["from_name"],
            'SenderName'     => $options["options"]["from_name"],
            'SenderEmail'    => $options["options"]["from_to"],
            'ReplyEmail'     => $options["options"]["reply_to"],
            'Subject'        => $metas[PostTypeHelper::META_CAMPAIGN_SUBJECT],
            'ContactsListID' => $params[PostTypeHelper::CAMPAIGN_TAXO_LISTS]->getId(),
            'Title'          => $params[PostTypeHelper::CAMPAIGN_NAME]
        );

        $campaignProvider = $metas[PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID];
        
        if(!empty($campaignProvider)){
            $provider = explode("_", $campaignProvider);

            if($provider[0] === ProviderHelper::MAILJET && isset($provider[1])){
                $response = $this->client->put(Resources::$Campaigndraft, array("ID" => $provider[1], "body" => $body ) );
            }
            else{
                $response = $this->client->post(Resources::$Campaigndraft, array('body' => $body ) );
            }
        }
        else{
            $response = $this->client->post(Resources::$Campaigndraft, array('body' => $body ) );
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

        $send = $campaign->getSend();

        if($send === "now"){
            $body = array(
                'Status' => -2
            );

            $response = $this->client->put(Resources::$Campaigndraft,
                array(
                    'ID'   => $campaign->getCampaignProviderId(),
                    'body' => $body
                )
            );
        }
        else{
            $response = $this->client->delete(Resources::$CampaigndraftSchedule,
                array(
                    'ID'   => $campaign->getCampaignProviderId(),
                )
            );
        }

        return $this->returnResponse($response);
    }

    public function getSenders(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$Sender);

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


        $body = array(
            'Html-part' => $campaign->getHtml()
        );


        $response = $this->client->post(Resources::$CampaigndraftDetailcontent,
            array(
                'id'   => $campaign->getCampaignProviderId(),
                'body' => $body
            )
        );

        if(!$response->success()){
            return $this->returnResponse($response);    
        }

        $typeSend = $campaign->getSend();
        switch($typeSend){
            case "now":
                $params =  array(
                    'id' => $campaign->getCampaignProviderId()
                );
                $response = $this->client->post(Resources::$CampaigndraftSend, $params);
                break;
            case "later":
                $response = $this->getUser();
                if($response["success"]){
                    $timezoneWP = get_option('timezone_string');

                    if(empty($timezoneWP)){
                        $timezoneWP = new \DateTimeZone("UTC");
                    }
                    else{
                        $timezoneWP = new \DateTimeZone($timezoneWP);
                    }

                    $dateWP     = new \DateTime($campaign->getDateSend(), $timezoneWP);
                    $dateWP->setTimezone(new \DateTimeZone("UTC"));
                    $date = gmdate(\DateTime::ISO8601, $dateWP->getTimestamp());

                    $params =  array(
                        'id'   => $campaign->getCampaignProviderId(),
                        'body' => array(
                            "date" => $date
                        )
                    );
                    $response = $this->client->post(Resources::$CampaigndraftSchedule, $params);
                }
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

        $response = $this->client->get(Resources::$Contactslist,
            array(
                'id'   => $listIdProvider,
            )
        );

        return $this->returnResponse($response);
    }

    /**
     *
     * @param int $idList
     * @return array
     */
    public function getListStatistic($idList){

         if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$Liststatistics,
            array(
                'filters'   => array(
                    'ContactsListID' => $idList
                )
            )
        );

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

        $filters = array();
        if(isset($params["Name"])){
            $filters["Name"] = $params["Name"];
        }

        $response = $this->client->get(Resources::$Contactdata,
            array(
                'id'      => $idSubscriber,
                'filters' => $filters
            )
        );

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

        $response = $this->client->get(Resources::$Listrecipient,
            array(
                'filters' => array(
                    "style"          => "full",
                    "ContactsList"   => $listIdProvider,
                    "Offset"         => $offset,
                    "Limit"          => $limit
                )
            )
        );

        return $this->returnResponse($response);
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

        $response = $this->client->get(Resources::$Campaignstatistics,
            array(
                "ID"   => $campaignId
            )
        );

        return $this->returnResponse($response);
    }

    /**
     *
     * @param int $campaignId
     * @return array
     */
    public function getNewsletterInformation($campaignId){

        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $str = "mj.nl=" . $campaignId;

        $ressource = array(
            "campaign",
            $str
        );

        $response = $this->client->get(
            $ressource
        );

        return $this->returnResponse($response);

    }

    /**
     *
     * @param int $campaignId
     * @return array
     */
    public function getCampaignOpenStatistics($campaignId){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$Openinformation,
            array(
                "filters" => array(
                    "CampaignID" => $campaignId
                )
            )
        );

        return $this->returnResponse($response);
    }

    /**
     *
     * @param int $campaignId
     * @return array
     */
    public function getCampaignClickStatistics($campaignId){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(Resources::$Clickstatistics,
            array(
                "filters" => array(
                    "CampaignID" => $campaignId
                )
            )
        );

        return $this->returnResponse($response);
    }

}
