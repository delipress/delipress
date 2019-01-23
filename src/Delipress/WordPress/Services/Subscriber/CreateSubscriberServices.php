<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\SourceSubscriberHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\AdminFormValues;

use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Models\OptinModel;
use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Traits\PrepareParams;

use Delipress\WordPress\Services\Table\TableServices;


/**
 * CreateSubscriberServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateSubscriberServices implements ServiceInterface, MediatorServicesInterface{

    use PrepareParams;

    protected $missingParameters = array();

    protected $paramsNotValid = 0;

    protected $subscriberExist = null;

    protected $fieldsPosts = array(
        "email"            => "is_email",
        "first_name"       => "sanitize_text_field",
        "last_name"        => "sanitize_text_field",
        "source"           => "sanitize_text_field"
    );

    protected $fieldsPostMetas = array();

    protected $fieldsRequired = array();

    protected $fieldsMetasRequired = array(
        "email",
        "source"
    );

    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices                = $services["OptionServices"];
        $this->optinServices                 = $services["OptinServices"];
        $this->optinStatsServices            = $services["OptinStatsServices"];
        $this->providerServices              = $services["ProviderServices"];
        $this->subscriberServices            = $services["SubscriberServices"];
        $this->confirmSubscribeServices      = $services["ConfirmSubscribeServices"];
        $this->synchronizeSubscriberServices = $services["SynchronizeSubscriberServices"];
        $this->listServices                  = $services["ListServices"];

    }


    /**
     * Create Subscriber from a optin
     *
     * @action DELIPRESS_SLUG . "_before_create_subscriber_on_list_from_optin"
     * @action DELIPRESS_SLUG . "_after_create_subscriber_on_list_from_optin"
     *
     *
     * @param OptinModel $optin
     * @param array $params
     * @return array
     */
    public function createSubscriberOnListsFromOptin(OptinModel $optin, $params){

        $options         = $this->optionServices->getOptions();
        $provider        = $this->optionServices->getProvider();
        $lists           = $optin->getLists();
        $response        = array(
            "success" => true
        );

        if(empty($lists)){
            $this->optinServices->deactivateOptin($optin);
            return array(
                "success" => true
            );
        }


        do_action(DELIPRESS_SLUG . "_before_create_subscriber_on_list_from_optin", $optin, $params);

        $email = $params["email"];

        switch($provider["key"]){
            case ProviderHelper::MAILCHIMP:
                $status = StatusHelper::PENDING;
                break;
            default:
                $status = StatusHelper::UNSUBSCRIBE;
                break;
        }


        if(!$options["subscribers"]["double_optin"]){
            $status      =  StatusHelper::SUBSCRIBE;
        }

        $listIds = array();
        foreach($lists as $list){
            $listIds[] = $list->getId();
        }


        if($options["subscribers"]["double_optin"]){
            switch($provider["key"]){
                case ProviderHelper::MAILCHIMP:
                    foreach($lists as $key => $list){
                        $response = $this->synchronizeSubscriberServices->subscriberSynchronizeOnList($list, $email,
                            array_merge(
                                array(
                                    "safeError"    => true,
                                    "status"       => $status
                                ),
                                $params
                            )
                        );
                    }
                    break;
                default:
                    $this->confirmSubscribeServices->sendConfirmSubscribe($listIds, $email, $params);
                    break;
            }
        }
        else{
            foreach($lists as $key => $list){
                $this->synchronizeSubscriberServices->subscriberSynchronizeOnList($list, $email,
                    array_merge(
                        array(
                            "safeError"    => true,
                        ),
                        $params
                    )
                );
            }
        }

        // Stats conversion
        $this->optinStatsServices->incrementConvert($optin->getId());

        do_action(DELIPRESS_SLUG . "_after_create_subscriber_on_list_from_optin", $optin, $params);

        return $response;

    }

    /**
     * @action DELIPRESS_SLUG . "_before_edit_subscriber_on_list"
     * @action DELIPRESS_SLUG . "_after_edit_subscriber_on_list"
     *
     * @return void
     */
    public function editSusbcriberOnList(){

        $this->fieldsRequired = array(
            "subscriber_id",
            "email"
        );

        $this->fieldsPosts["list_id"]       = "";
        $this->fieldsPosts["subscriber_id"] = "";
        $this->fieldsPosts["metas"]         = "";

        $params = $this->getPostParams("fields");

        do_action(DELIPRESS_SLUG . "_before_edit_subscriber_on_list", $params);

        $this->verifyParameters($params);

        if(
            !empty($this->missingParameters) ||
            $this->paramsNotValid > 0

        ){
            return array(
                "success" => false
            );
        }

        $list       = $this->listServices->getList($params["list_id"]);
        $subscriber = $this->subscriberServices->getSubscriberByList($list->getId(), $params["subscriber_id"]);

        $params = array_merge(
            $params,
            array(
                "list"       => $list,
                "subscriber" => $subscriber
            )
        );

        $response = $this->editSubscriber($params);

        do_action(DELIPRESS_SLUG . "_after_edit_subscriber_on_list", $response);

        return $response;

    }


    /**
     *
     * @param array $params
     * @example array(
     *      "subscriber" => SubscriberInterface,
     *      "list" => ListInterface,
     *      "metas" => array(),
     *      "email" => "johndoe@gmail.com",
     *      "first_name" => "John",
     *      "last_name"  => "Doe"
     * )
     * @return array
     */
    public function editSubscriber($params, $withNotice = true){

        $result = $this->synchronizeSubscriberServices
                       ->editSubscriberSynchronizeOnList($params["list"], $params["subscriber"], $params);

        if($result["success"]){
            if($withNotice){
                AdminNoticesHelper::registerSuccess(
                    CodeErrorHelper::ADMIN_NOTICE,
                    __("Subscriber updated", "delipress")
                );
            }
        }
        else{
            $provider        = $this->optionServices->getProvider();
            if($withNotice){
                AdminNoticesHelper::registerSuccess(
                    CodeErrorHelper::ADMIN_NOTICE,
                    sprintf(__("An error occurred during the update on %s ", "delipress"), $provider["key"] )
                );
            }
        }

        AdminFormValues::cleanFormValues();

        return $result;
    }


    /**
     *
     * @param args $params
     * @return void
     */
    protected function verifyParameters($params){

        if(empty($params["email"])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::META_SUBSCRIBER_EMAIL,
                __("Email field can not be empty", "delipress")
            );
        }
    }

    /**
     * @action DELIPRESS_SLUG . "_before_create_subscriber_on_list"
     * @action DELIPRESS_SLUG . "_after_create_subscriber_on_list"
     *
     * @return void
     */
    public function createSubscriberOnList(){
        $this->fieldsRequired = array(
            "list_id",
            "email",
            "confirm"
        );

        $this->fieldsPosts["list_id"]   = "";
        $this->fieldsPosts["metas"]     = "";
        $this->fieldsPosts["confirm"]   = "";

        $params = $this->getPostParams("fields");

        $this->verifyParameters($params);

        if(empty($params["confirm"])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::META_SUBSCRIBER_CONFIRM,
                __("This person gave me consentment to add him/her on this list", "delipress")
            );
        }

        if(
            !empty($this->missingParameters) ||
            $this->paramsNotValid > 0

        ){

            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false
            );
        }

        do_action(DELIPRESS_SLUG . "_before_create_subscriber_on_list", $params);

        $response = $this->createSubscriberOnListStandalone($params["list_id"], $params);


        if($response["success"]){

            AdminFormValues::cleanFormValues();

            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::CREATE_SUBSCRIBER_ON_LIST_SUCCESS),
                array(
                    "from" => "add_subscriber_admin"
                )
            );

        }

        do_action(DELIPRESS_SLUG . "_after_create_subscriber_on_list", $response);

        return $response;

    }

    /**
     * @action DELIPRESS_SLUG . "_before_create_subscriber_on_list_standalone"
     * @action DELIPRESS_SLUG . "_after_create_subscriber_on_list_standalone"
     *
     * @return void
     */
    public function createSubscriberOnListStandalone($listId, $params){

        do_action(DELIPRESS_SLUG . "_before_create_subscriber_on_list_standalone", $params);

        $this->verifyParameters($params);

        if(
            !empty($this->missingParameters) ||
            $this->paramsNotValid > 0

        ){
            return array(
                "success" => false
            );
        }

        $provider   = $this->optionServices->getProvider();
        $list       = $this->listServices->getList($listId);

        $response = $this->synchronizeSubscriberServices->subscriberSynchronizeOnList(
            $list,
            $params["email"],
            $params
        );

        do_action(DELIPRESS_SLUG . "_after_create_subscriber_on_list_standalone", $response);

        return $response;

    }
}
