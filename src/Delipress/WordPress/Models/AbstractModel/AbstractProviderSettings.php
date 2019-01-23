<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;
use Delipress\WordPress\Helpers\ProviderHelper;

/**
 * AbstractProviderSettings
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractProviderSettings implements MediatorServicesInterface {

    /**
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices          = $services["OptionServices"];
        $this->synchronizeListServices = $services["SynchronizeListServices"];
    }

    /**
     * 
     * @return array
     */
    public function getOptions(){
        if($this->options === null){
            $this->options = $this->optionServices->getOptions();
        }

        return $this->options;
    }


    /**
     * @param Object $providerApi
     * @return ObjectSettings
     */
    public function setApi($providerApi){
        $this->providerApi = $providerApi;

        return $this;
    }

    public function getApi(){
        return $this->providerApi;
    }

    /**
     * 
     * @param array $options
     * @return AbstractProviderSettings
     */
    public function createClientApi($options){
        switch($this->provider){
            case ProviderHelper::MAILCHIMP:
                $this->providerApi->createClient($options["api_key_mailchimp"]);
                break;
            case ProviderHelper::MAILJET:
                $this->providerApi->createClient($options["client_id"], $options["client_secret"]);
                break;
            case  ProviderHelper::SENDGRID:
                $this->providerApi->createClient($options["api_key_sendgrid"]);
                break;
            case ProviderHelper::SENDINBLUE:
                $this->providerApi->createClient($options["api_key_sendinblue"]);
                break;
            default:
                do_action(DELIPRESS_SLUG . "_provider_settings_create_client_api_" . $this->provider);
                break;
        }

        return $this;
    }

    public function setSafeError($safeError = false){
        $this->providerApi->setSafeError($safeError);
        return $this;
    }


    /**
     * @param string $provider
     * 
     * @return array
     */
    public function getUserInfo(){

        $response = $this->providerApi->getUser();
        
        $options  = array();
        
        if($response["success"]){
            switch($this->provider){
                case ProviderHelper::MAILJET:
                    $options = array(
                        "options" => array(
                            "from_to" => $response["results"][0]["Email"]
                        )
                    );
                    break;
                case ProviderHelper::MAILCHIMP:
                    $options = array(
                        "options" => array(
                            "from_to"  => $response["results"]["email"],
                            "reply_to" => $response["results"]["email"]
                        )
                    );
                    break;
                case ProviderHelper::SENDINBLUE:
                    if(
                        isset($response["results"][2]) &&
                        isset($response["results"][2]["email"])
                    ){
                        $options = array(
                            "options" => array(
                                "from_to"  => $response["results"][2]["email"]
                            )
                        );
                    }
                    break;
                case ProviderHelper::SENDGRID:
                    // TODO
                    break;
                default:
                    $options = apply_filters(DELIPRESS_SLUG . "_get_user_info_" . $this->provider, array(
                        "options" => array(
                            "from_to" => ""
                        )
                    ));
                    break;
            }
        }

        return $options;
    }

}


