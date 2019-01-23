<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\SourceSubscriberHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\DataEncoder;

use Delipress\WordPress\Models\SubscriberInterface;
use Delipress\WordPress\Models\ListModel;

/**
 * ConfirmSubscribeServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class ConfirmSubscribeServices implements ServiceInterface, MediatorServicesInterface{

    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices                   = $services["OptionServices"];
        $this->providerServices                 = $services["ProviderServices"];
        $this->emailHtmlServices                = $services["EmailHtmlServices"];
        $this->subscriberTableServices          = $services["SubscriberTableServices"];
        $this->listSubscriberTableServices      = $services["ListSubscriberTableServices"];
        $this->synchronizeSubscriberServices    = $services["SynchronizeSubscriberServices"];

    }

    /**
     * @return string
     */
    public function getConfirmSubscribeUrl($listIds, $email, $args = array(), $redirectURL = ""){

        $url = ($redirectURL == "" ) ? home_url() : $redirectURL;

        return wp_nonce_url(
            add_query_arg(
                array(
                    "data"   => DataEncoder::encodeData(
                        array_merge(
                            array(
                                "list_ids"    => $listIds
                            ),
                            $args
                        )
                    ),
                    "action" => ActionHelper::CONFIRM_SUBSCRIBE
                ),
                $url
            ),
            ActionHelper::CONFIRM_SUBSCRIBE
        );
    }


    /**
     * Send confirm subscribe
     *
     * @param string $email
     * @return array
     */
    public function sendConfirmSubscribe($listIds, $email, $args = array()){

        $provider    = $this->optionServices->getProvider();
        $providerApi = $this->providerServices->getProviderApi($provider["key"]);
        $options     = $this->optionServices->getOptions();

        $logo        = $options["subscribers"]["logo_subscription"];
        $message     = $options["subscribers"]["text_subscription"];
        $title       = $options["subscribers"]["title_subscription"];
        $button      = $options["subscribers"]["button_subscription"];
        $redirectURL = $options["subscribers"]["subscription_redirect"];

        ob_start();
        include_once apply_filters(DELIPRESS_SLUG . "_confirm_subscribe_email", DELIPRESS_PLUGIN_DIR_EMAILS . "/confirm_subscribe.php");
        $html = ob_get_contents();
        ob_end_clean();

        $html = $this->emailHtmlServices->prepareEmailHtml($html);
        $html = str_replace("[delipress_request_confirm_subscribe]", $this->getConfirmSubscribeUrl($listIds, $email, $args, $redirectURL), $html);

        $response = $providerApi->sendEmail(
            array(
                "subject" => __("Just one more step to subscribe!", "delipress"),
                "html"    => $html,
                "from_name" => apply_filters(DELIPRESS_SLUG . "_confirm_subscribe_from_name", $options["options"]["from_name"]),
                "emails" => array(
                    $email
                )
            )
        );

        return $response;

    }

    /**
     * @action DELIPRESS_SLUG . "_confirm_subscriber"
     *
     * @param string $email
     * @param array $listIds
     * @param array $args
     * @return void
     */
    public function confirmSubscriber($email, $listIds, $args = array()){

        do_action(DELIPRESS_SLUG . "_confirm_subscriber", $email);

        foreach($listIds as $key => $value){
            $list = new ListModel();
            $list->setId($value);

            $this->synchronizeSubscriberServices->subscriberSynchronizeOnList($list, $email, $args);
        }

        do_action(DELIPRESS_SLUG . "_confirm_subscriber_after", $email);

    }

}
