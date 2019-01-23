<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderSettings;

/**
 * SendGridSettings
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SendGridSettings extends AbstractProviderSettings {


    protected $provider = Providerhelper::SENDGRID;

    /**
     *
     * @param array $options
     * @return void
     */
    public function prepareProvidersSettings($options){
        $errors = 0;

        if(!array_key_exists("api_key_sendgrid", $options) || empty($options["api_key_sendgrid"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::SENDGRID_API_KEY,
                __("API Key field is empty", "delipress")
            );
            $errors++;
        }

        
        if($this->providerApi && $errors === 0){

            $response = $this->providerApi
                            ->createClient($options["api_key_sendgrid"])
                            ->testConnexion();

            if($response["success"]){
                $options["is_connect"] = true;
            }
            else{
                $errors++;
                ErrorFieldsNoticesHelper::registerError(
                    CodeErrorHelper::PROVIDER_CONNEXION_ERROR, 
                    CodeErrorHelper::getMessage(CodeErrorHelper::PROVIDER_CONNEXION_ERROR)
                );
                ErrorFieldsNoticesHelper::registerError(
                    CodeErrorHelper::TRANSIT_KEY_PROVIDER, 
                    $this->provider
                );
                $options["is_connect"] = false;
            }
        }

        if($errors === 0){
            ErrorFieldsNoticesHelper::registerSuccess(
                CodeErrorHelper::OPTIONS_SUCCESS,
                ""
            );
        }


        $options["key"] = $this->provider;

        return $options;

    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function saveOptions($params){
        if(!$this->providerApi){
            return array(
                "success" => false
            );
        }


        $response = $this->providerApi
                        ->createClient($params["api_key_sendgrid"])
                        ->setSafeError(true)
                        ->testConnexion();

        if(!$response["success"]){
            return array(
                "success" => false
            );

        }

        $prepareParams = array(
            "provider" => array(
                "api_key_sendgrid"     => $params["api_key_sendgrid"],
                "is_connect"             => true,
                "key"                    => ProviderHelper::SENDGRID
            )
        );
        
        $response = $this->providerApi->getSenders();

        if($response["success"]){
            $fromTo = "";
            foreach($response["results"] as $sender){
                if($sender["locked"] || !$sender["verified"]["status"]){
                    continue;
                }

                if(!empty($fromTo)){
                    break;
                }

                $fromTo = $sender["from"]["email"];
            }

            if(!empty($fromTo)){
                $prepareParams["options"] = array(
                    "from_to"   => $fromTo
                );
            }

        }

        $this->optionServices->setOptions($prepareParams);

        return array(
            "success" => true
        );
    }
}
