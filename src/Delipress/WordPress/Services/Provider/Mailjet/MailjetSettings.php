<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderSettings;

/**
 * MailjetSettings
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class MailjetSettings extends AbstractProviderSettings {


    protected $provider = Providerhelper::MAILJET;

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

        if(!array_key_exists("client_id", $options) || empty($options["client_id"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::MAILJET_CLIENT_ID,
                __("API Key field is empty", "delipress")
            );
            $errors++;
        }

        if(!array_key_exists("client_secret", $options) || empty($options["client_secret"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::MAILJET_CLIENT_SECRET,
                __("Secret Key field is empty", "delipress")
            );
            $errors++;
        }

        if($this->providerApi && $errors === 0){
            
            $response = $this->providerApi
                            ->createClient($options["client_id"], $options["client_secret"])
                            ->testConnexion();

            if($response["success"]){
                $options["is_connect"] = true;

                $this->createMetadata();
            }
            else{
                $errors++;
                ErrorFieldsNoticesHelper::registerError(
                    CodeErrorHelper::PROVIDER_CONNEXION_ERROR, 
                    CodeErrorHelper::getMessage(CodeErrorHelper::PROVIDER_CONNEXION_ERROR)
                );
                ErrorFieldsNoticesHelper::registerError(
                    CodeErrorHelper::TRANSIT_KEY_PROVIDER, 
                    ProviderHelper::MAILJET
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


        $options["key"] = ProviderHelper::MAILJET;

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
                        ->createClient($params["client_id"], $params["client_secret"])
                        ->testConnexion();

        if(!$response["success"]){
            return array(
                "success" => false
            );

        }

        $prepareParams = array(
            "provider" => array(
                "client_id"     => $params["client_id"],
                "client_secret" => $params["client_secret"],
                "is_connect"    => true,
                "key"           => ProviderHelper::MAILJET
            )
        );
        
        $response = $this->providerApi->getUser();

        if($response["success"]){
            $prepareParams["options"] = array(
                "from_to"   => $response["results"][0]["Email"],
                "reply_to"  => $response["results"][0]["Email"]
            );
        }

        $this->optionServices->setOptions($prepareParams);

        return array(
            "success" => true
        );
    }
}
