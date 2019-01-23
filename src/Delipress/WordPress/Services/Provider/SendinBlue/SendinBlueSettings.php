<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderSettings;

/**
 * SendinBlueSettings
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SendinBlueSettings extends AbstractProviderSettings {


    protected $provider = Providerhelper::SENDINBLUE;

    /**
     * Create metadata on mailjet
     *
     * @return void
     */
    public function createMetadata(){
        $metas = MetaProviderHelper::getListMetaProvider();

        $metas = array_filter($metas, function($v) {
            return $v["active"];
        });

        foreach($metas as $key => $meta){
            $this->providerApi
                 ->setSafeError(true)
                 ->createMetaData($meta);
        }
    }

    /**
     *
     * @param array $options
     * @return void
     */
    public function prepareProvidersSettings($options){
        $errors = 0;

        if(!array_key_exists("api_key_sendinblue", $options) || empty($options["api_key_sendinblue"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::SENDINBLUE_API_KEY,
                __("API Key field is empty", "delipress")
            );
            $errors++;
        }


        if($this->providerApi && $errors === 0){

            $response = $this->providerApi
                            ->createClient($options["api_key_sendinblue"])
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
                    ProviderHelper::SENDINBLUE
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


        $options["key"] = ProviderHelper::SENDINBLUE;

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
                        ->createClient($params["api_key_sendinblue"])
                        ->testConnexion();

        if(!$response["success"]){
            return array(
                "success" => false
            );

        }

        $prepareParams = array(
            "provider" => array(
                "api_key_sendinblue"     => $params["api_key_sendinblue"],
                "is_connect"             => true,
                "key"                    => ProviderHelper::SENDINBLUE
            )
        );
        
        $response = $this->providerApi->getUser();

        if($response["success"]){
            $prepareParams["options"] = array(
                "from_to"   => $response["results"][2]["email"]
            );
        }

        $this->optionServices->setOptions($prepareParams);

        return array(
            "success" => true
        );
    }
}
