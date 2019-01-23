<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderSettings;

/**
 * MailchimpSettings
 *
 * @author Delipress
 */
class MailchimpSettings extends AbstractProviderSettings {

    protected $provider = Providerhelper::MAILCHIMP;

    /**
     *
     * @param array $options
     * @return void
     */
    public function prepareProvidersSettings($options){

        $errors = 0;

        if(!array_key_exists("api_key_mailchimp", $options) || empty($options["api_key_mailchimp"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::MAILCHIMP_API_KEY,
                __("API Key field is empty", "delipress")
            );
            $errors++;
        }

        if($this->providerApi && $errors === 0){

            $mailchimpClient = $this->providerApi
                                    ->createClient($options["api_key_mailchimp"]);

            if(!$mailchimpClient){
                $errors++;
            }
            else{
                $response = $mailchimpClient->testConnexion();

                if($response["success"]){
                    $options["is_connect"] = true;
                }
                else{
                    $errors++;
                }
            }


        }

        if($errors === 0){
            ErrorFieldsNoticesHelper::registerSuccess(
                CodeErrorHelper::OPTIONS_SUCCESS,
                ""
            );
        }
        else{
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::PROVIDER_CONNEXION_ERROR,
                CodeErrorHelper::getMessage(CodeErrorHelper::PROVIDER_CONNEXION_ERROR)
            );
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::TRANSIT_KEY_PROVIDER,
                ProviderHelper::MAILCHIMP
            );
            $options["is_connect"] = false;
        }


        $options["key"] = ProviderHelper::MAILCHIMP;

        return $options;

    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function saveOptions($params){

        if($this->providerApi){

            $mailchimpClient = $this->providerApi
                            ->createClient($params["api_key_mailchimp"]);

            if(!$mailchimpClient){
                return array(
                    "success" => false
                );
            }

            $response = $mailchimpClient->testConnexion();

            if($response["success"]){

                $prepareParams = array(
                    "provider" => array(
                        "api_key_mailchimp" => $params["api_key_mailchimp"],
                        "is_connect"    => true,
                        "key"           => ProviderHelper::MAILCHIMP
                    )
                );

                $response = $mailchimpClient->getUser();
                if($response["success"]){

                    $fromName = get_bloginfo("name");
                    if(isset($response["results"]["account_name"])){
                        $fromName = $response["results"]["account_name"];
                    }

                    $prepareParams["options"] = array(
                        "from_to"   => $response["results"]["email"],
                        "reply_to"  => $response["results"]["email"],
                        "from_name" => $fromName
                    );
                }
                $this->optionServices->setOptions($prepareParams);

                return array(
                    "success" => true
                );
            }
            else{

                return array(
                    "success" => false
                );
            }
        }
    }
}
