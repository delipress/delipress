<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\AbstractModel\AbstractProviderApi;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;

use Delipress\WordPress\Services\Provider\Mailchimp\Override\MailChimp;
use \DrewM\MailChimp\Batch;


/**
 * MailchimpApi
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class MailchimpApi extends AbstractProviderApi {

    /**
     *
     * @param array $response
     * @return array
     */
    protected function returnResponse($response){
        $success = $this->client->success();
        if($success){
            return array(
                "success" => true,
                "results" => $response
            );
        }
        else{

            if($this->safeError){
                return array(
                    "success" => false,
                    "results" => $response,
                );
            }

            global $wp_version;
            __delipress__god_save_error(
                array(
                    "title"   => "Mailchimp Error",
                    "message" => (isset($response["detail"])) ? $response["detail"] : "",
                    "wp_infos" => array(
                        "wp_version"       => $wp_version,
                    ),
                    "home_url"     => home_url(),
                    "php_version"  => PHP_VERSION,
                    "server_infos" => __delipress__god_get_info_server(),
                    "extras"       => (isset($response["errors"])) ? $response["errors"] : "",
                    "file"         => "mailchimp_api",
                    "line"         => "error_mailchimp_api",
                    "code"         => 1
                )
            );

            AdminNoticesProviderHelper::registerError(
                ProviderHelper::MAILCHIMP,
                (isset($response["detail"])) ? $response["detail"] : "",
                array(
                    "provider" => ProviderHelper::MAILCHIMP
                )
            );

            return array(
                "success" => false,
                "results" => $response,
            );
        }
    }


    /**
     * @return array
     */
    public function getUser(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get("/");

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

        $options = $this->optionServices->getOptions();

        $body = array(
            'type'     => 'regular',
            'settings' => array(
                'subject_line' => $params["subject"],
                'reply_to'     => (!empty($options["options"]["reply_to"]) ) ? $options["options"]["reply_to"] : $options["options"]["from_to"],
                'from_name'    => $options["options"]["from_name"],
                'title'        => $params["subject"]
            )
        );

        $response = $this->client->post("/campaigns", $body);

        if(!$this->client->success()){
            return array(
                "success" => false
            );
        }

        $body = array(
            'html'     => $params["html"]
        );

        $idCampaign = $response["id"];

        $response = $this->client->put(
            sprintf("/campaigns/%s/content", $idCampaign),
            $body
        );

        if(!$this->client->success()){
            return array(
                "success" => false
            );
        }

        $body = array(
            "send_type" => "html"
        );
        foreach($params["emails"] as $key => $email){
            $body["test_emails"][] = $email;
        }

        $response = $this->client->post(
            sprintf("/campaigns/%s/actions/test", $idCampaign),
            $body
        );

        if(!$this->client->success()){
            return array(
                "success" => false
            );
        }

        $response = $this->client->delete(
            sprintf("/campaigns/%s", $idCampaign)
        );

        return $this->returnResponse($response);


    }

    /**
     * Test connexion with provider
     *
     * @return array
     */
    public function testConnexion(){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get("/");

        return $this->returnResponse($response);
    }

    /**
     * @param string $clientSecret
     * @return MailchimpApi
     */
    public function createClient($clientSecret){
        try{
            $this->client             = new MailChimp($clientSecret);
            $this->client->verify_ssl = false;
        }
        catch(\Exception $e){
            return false;
        }

        return $this;
    }

    /**
     * @return MailchimpApi
     */
    public function createClientFromOptionServices(){
        $provider = $this->optionServices->getProvider(true);

        $this->createClient(
            $provider["api_key_mailchimp"]
        );

        return $this;

    }
    
    public function getSenders(){
        return $this->getUser();
    }

    /**
     *
     * @return array
     */
    public function getLists($params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get("/lists", array(
            "offset" => (isset($params["offset"])) ? $params["offset"] : 0,
            "count"  => (isset($params["limit"])) ? $params["limit"] : 10
        ));

        $response = $this->returnResponse($response);

        if($response["success"]){
            $response["total_items"] = $response["results"]["total_items"];
            $response["results"]     = $response["results"]["lists"];
        }


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

        $response = $this->client->delete(
            sprintf("/lists/%s/members/%s", $idList, $params["id"])
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

        $batch 	   = $this->client->new_batch();

        foreach($params as $key => $param){

            $batch->delete(
                "op$key" ,
                sprintf("/lists/%s/members/%s", $idList, $param["id"])
            );

        }

        $batch->execute();

        return array(
            "success" => true
        );
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
            "email_address" => $params["email"],
            "status"        => (isset($params["status"])) ? $params["status"] : "subscribed",
        );

        $prepareParams = $this->prepareParamsSubscriberOnList($params);
        $body          = array_merge($prepareParams, $body);
        $body = apply_filters(DELIPRESS_SLUG . "_mailchimp_api_create_subscriber_on_list", $body, $idList, $params);
        if(isset($body["merge_fields"]) && empty($body["merge_fields"])){
            unset($body["merge_fields"]);
        }
        
        $response = $this->client->post(
            sprintf("/lists/%s/members", $idList),
            $body
        );

        return $this->returnResponse($response);
    }

    /**
     *
     * @param string $dataType
     * @return string
     */
    protected function getDataType($dataType){
        switch($dataType){
            case "str":
            default:
                return "text";
        }
    }


    /**
     *
     * @param array $meta
     * @param array $params
     * @return array
     */
    public function createMetaData($meta, $params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }
        
        $response = $this->client->post(
            sprintf("/lists/%s/merge-fields", $params["list_id"]),
            array(
                "name" => $meta["name"],
                "tag"  => $meta["name"],
                "type" => $this->getDataType($meta["datatype"])
            )
        );

        return $this->returnResponse($response);
    }
    
    /**
     *
     * @param string $name
     * @return array
     */
    public function getMetaDatas($params){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(
            sprintf("/lists/%s/merge-fields", $params["list_id"]),
            array(
                "count" => 1000
            )
        );

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

        if(empty($response["results"]["merge_fields"])){
            return $result;
        }

        foreach($response["results"]["merge_fields"] as $key => $value){

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
     *
     * @param array $params
     * @return array
     */
    protected function prepareParamsSubscriberOnList($params){
        $body = array(
            "merge_fields" => array()
        );

        if(isset($params["status"])){
            $body["status"] = $params["status"];
        }

        if(isset($params["email"])){
            $body["email_address"] = $params["email"];
        }

        if(isset($params["first_name"])){
            $body["merge_fields"]["FNAME"] = $params["first_name"];
        }
        else if(isset($params["metas"]) && isset($params["metas"]["first_name"])){
            $body["merge_fields"]["FNAME"] = $params["metas"]["first_name"];
            unset($params["metas"]["first_name"]);
        }

        if(isset($params["last_name"])){
            $body["merge_fields"]["LNAME"] = $params["last_name"];
        }
        else if(isset($params["metas"]) && isset($params["metas"]["last_name"])){
            $body["merge_fields"]["LNAME"] = $params["metas"]["last_name"];
            unset($params["metas"]["last_name"]);
        }

        if(isset($params["metas"])){
            foreach($params["metas"] as $key => $value){
                if(empty($key)){
                    continue;
                }

                $body["merge_fields"][$key] = $value;
            }
        }

        return $body;

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

        $body = $this->prepareParamsSubscriberOnList($params);
        $body = apply_filters(DELIPRESS_SLUG . "_mailchimp_api_edit_subscriber_on_list", $body, $params, $idList, $idSubscriber);

        if(isset($body["merge_fields"]) && empty($body["merge_fields"])){
            unset($body["merge_fields"]);
        }

        $response = $this->client->patch(
            sprintf("/lists/%s/members/%s", $idList, $idSubscriber),
            $body
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

        $batch 	   = $this->client->new_batch();

        foreach($contacts as $key => $contact){
            $result = $this->createSubscriberOnList($idList, $contact);
            // $args = $this->prepareParamsSubscriberOnList($contact);
            // $batch->post(
            //     "op$key" ,
            //     sprintf("/lists/%s/members", $idList),
            //     $args
            // );

        }

        // $result = $batch->execute();

        return array(
            "success" => true,
            "results" => $result
        );
    }


    /**
     *
     * @param array $params
     * @return array
     */
    public function getMCLocale($wp_locale){
        switch ($wp_locale) {
             case 'en_US':
                return 'en';
                break;
            case 'en_AU':
                return 'en';
                break;
            case 'en_CA':
                return 'en';
                break;
            case 'en_NZ':
                return 'en';
                break;
            case 'en_ZA':
                return 'en';
                break;
            case 'en_GB':
                return 'en';
                break;
            case 'bel':
                return 'be';
                break;
            case 'zh_CN':
                return 'zh';
                break;
            case 'zh_HK':
                return 'zh';
                break;
            case 'zh_TW':
                return 'zh';
                break;
            case 'nl_BE':
                return 'nl';
                break;
            case 'nl_NL':
                return 'nl';
                break;
            case 'fr_FR':
                return 'fr';
                break;
            case 'fr_BE':
                return 'fr';
                break;
            case 'fr_CA':
                return 'fr_CA';
                break;
            case 'cs_CZ':
                return 'cs';
                break;
            case 'da_DK':
                return 'da';
                break;
            case 'de_DE':
                return 'de';
                break;
            case 'de_CH':
                return 'de';
                break;
            case 'hi_IN':
                return 'hi';
                break;
            case 'hu_HU':
                return 'hu';
                break;
            case 'id_ID':
                return 'id';
                break;
            case 'it_IT':
                return 'it';
                break;
            case 'pl_PL':
                return 'pl';
                break;
            case 'pt_BR':
                return 'pt';
                break;
            case 'pl_PL':
                return 'pt_PT';
                break;
            case 'es_CL':
                return 'es_ES';
                break;
            case 'es_CO':
                return 'es_ES';
                break;
            case 'es_GT':
                return 'es_ES';
                break;
            case 'es_MX':
                return 'es_ES';
                break;
            case 'es_PE':
                return 'es_ES';
                break;
            case 'es_PR':
                return 'es_ES';
                break;
            case 'es_ES':
                return 'es_ES';
                break;
            case 'es_VE':
                return 'es_ES';
                break;
            default:
                return $wp_locale;
                break;
        }
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

        $options  = $this->optionServices->getOptions();
        $response = $this->getUser();

        if(!$response["success"] || empty($response["results"]["contact"])){
            $contact = array(
                "company"  => "none",
                "address1" => "none",
                "city"     => "none",
                "state"    => "none",
                "zip"      => "none",
                "country"  => "none"
            );
        }
        else{
            $contact = array(
                "company"  => $response["results"]["contact"]["company"],
                "address1" => $response["results"]["contact"]["addr1"],
                "city"     => $response["results"]["contact"]["city"],
                "state"    => $response["results"]["contact"]["state"],
                "zip"      => $response["results"]["contact"]["zip"],
                "country"  => $response["results"]["contact"]["country"]
            );
        }

        $blogname = get_option("blogname");

        $params = array(
            'name'              => $list->getName(),
            'contact'           => $contact,
            'campaign_defaults' => array(
                'from_name'  => (!empty($options["options"]["from_name"]) ) ? $options["options"]["from_name"] : $blogname,
                'from_email' => $options["options"]["from_to"],
                'subject'    => $blogname,
                'language'   => $this->getMCLocale(get_locale())
            ),
            'email_type_option'   => false,
            'permission_reminder' => apply_filters(DELIPRESS_SLUG . "_mailchimp_permission_reminder",
                sprintf(__("You are receiving this email because you subscribed to %s"), $list->getName() )
            )
        );

        $response = $this->client->post("/lists", $params );

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

        $options  = $this->optionServices->getOptions();

        $blogname = get_option("blogname");
        $params = array(
            'name'              => $params[TaxonomyHelper::LIST_NAME],
        );

        $response = $this->client->patch(
            sprintf("/lists/%s", $list->getId()), 
            $params 
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

        $response = $this->client->delete(
            sprintf("/lists/%s", $id)
        );

        return $this->returnResponse($response);
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

        $response = $this->client->get(
            sprintf("/lists/%s/members/%s", $idList, $idProvider)
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

        $body = array(
            'fields' => (isset($params["fields"])) ? $params["fields"] : array(
                "email_address"
            ),
            'query' => $params["email"]
        );
        
        $response = $this->client->get("/search-members", $body);
        
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
            'type'     => 'regular',
            'settings' => array(
                'subject_line' => $metas[PostTypeHelper::META_CAMPAIGN_SUBJECT],
                'reply_to'     => (!empty($options["options"]["reply_to"]) ) ? $options["options"]["reply_to"] : $options["options"]["from_to"],
                'from_name'    => $options["options"]["from_name"]
            )
        );

        if(isset($params[PostTypeHelper::CAMPAIGN_TAXO_LISTS])){
            $body["recipients"]["list_id"] = $params[PostTypeHelper::CAMPAIGN_TAXO_LISTS]->getId();
        }

        if(isset($params[PostTypeHelper::CAMPAIGN_TAXO_LISTS])){
            $body["settings"]["title"] = $params[PostTypeHelper::CAMPAIGN_NAME];
        }

        $campaignProvider = $metas[PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID];

        if(!empty($campaignProvider)){
            $provider = explode("_", $campaignProvider);
            if($provider[0] === ProviderHelper::MAILCHIMP){
                $response = $this->client->patch(
                    sprintf("/campaigns/%s", $provider[1]),
                    $body
                );
            }
            else{
                $response = $this->client->post("/campaigns", $body);
            }
        }
        else{
            $response = $this->client->post("/campaigns", $body);
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

            $response = $this->client->delete(
                sprintf("/campaigns/%s", $campaign->getCampaignProviderId())
            );
        }
        else{
            $response = $this->client->delete(
                sprintf("/campaigns/%s", $campaign->getCampaignProviderId())
            );
        }

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

        $list    = $campaign->getLists();
        $response = $this->createDraftCampaign(
            array(
                PostTypeHelper::CAMPAIGN_TAXO_LISTS => $list,
                PostTypeHelper::CAMPAIGN_NAME       => $campaign->getTitle()
            ),
            array(
                PostTypeHelper::META_CAMPAIGN_SUBJECT              => $campaign->getSubject(),
                PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID => ""
            )
        );

        $success = $this->client->success();
        if(!$success){
            return $this->returnResponse($response);
        }

        $body = array(
            'html'     => $campaign->getHtml()
        );

        $idCampaign = $campaign->getCampaignProviderId();

        $response = $this->client->put(
            sprintf("/campaigns/%s/content", $idCampaign),
            $body
        );

        $success = $this->client->success();
        if($success){

            $typeSend = $campaign->getSend();
            switch($typeSend){
                case "now":

                    $response = $this->client->post(
                        sprintf("/campaigns/%s/actions/send",$idCampaign)
                    );
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

                        $current_time   =  $dateWP->getTimestamp();
                        $frac = 900;
                        $r = $current_time % $frac;

                        $new_time = $current_time + ($frac-$r) - 900;
                        $date = gmdate(\DateTime::ISO8601, $new_time);

                        $params =  array(
                            'schedule_time' => $date
                        );

                        $response = $this->client->post(
                            sprintf("/campaigns/%s/actions/schedule",$idCampaign),
                            $params
                        );

                    }
                    break;
            }

        }

        return $this->returnResponse($response);
    }

    /** 
     * @param int $campaignId
     */
    public function sendChecklist($campaign){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(
            sprintf("/campaigns/%s/send-checklist", $campaign->getCampaignProviderId())
        );

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

        $response = $this->client->get(
            sprintf("/lists/%s", $listIdProvider)
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
    public function getListContacts($listIdProvider, $offset = 0, $limit = 500, $params = array()){
        if($this->client === null){
            $this->createClientFromOptionServices();
        }

        $response = $this->client->get(
            sprintf("/lists/%s/members", $listIdProvider),
            array_merge(
                $params,
                array(
                    "status"       => "subscribed",
                    "offset"       => $offset,
                    "count"        => $limit
                )
            )
        );

        $response = $this->returnResponse($response);

        if($response["success"]){
            $response["results"] = $response["results"]["members"];
        }

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

       
        $response = $this->client->get(
            sprintf("/reports/%s", $campaignId)
        );

        return $this->returnResponse($response);
    }

}
