<?php

namespace Delipress\WordPress\Services\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Traits\PrepareParams;


/**
 * WizardStepOneServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardStepOneServices implements ServiceInterface, MediatorServicesInterface {

    use PrepareParams;


    protected $missingParameters = array();

    protected $fieldsPosts = array();
    
    protected $fieldsPostMetas = array();

    protected $fieldsRequired = array();

    protected $fieldsMetasRequired = array();


    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        if(!array_key_exists("OptionServices", $services)){
            throw new Exception("Miss OptionServices");
        }
        if(!array_key_exists("ProviderServices", $services)){
            throw new Exception("Miss ProviderServices");
        }

        $this->campaignServices = $services["CampaignServices"];
        $this->optionServices   = $services["OptionServices"];
        $this->providerServices = $services["ProviderServices"];
        $this->optinServices    = $services["OptinServices"];

    }

    /**
     * @action DELIPRESS_SLUG . "_before_connect_provider_step_one"
     * @action DELIPRESS_SLUG . "_after_connect_provider_step_one"
     * 
     * @param string $provider
     * @return array
     */
    public function connectProvider($provider){
        
        do_action(DELIPRESS_SLUG . "_before_connect_provider_step_one", $provider);

        $providerApi = $this->providerServices->getProviderApi($provider);
        switch($provider){
            case ProviderHelper::MAILJET:
                $this->fieldsPosts = array(
                    "client_id"     => "sanitize_text_field",
                    "client_secret" => "sanitize_text_field"
                );
                break;
            case ProviderHelper::MAILCHIMP:
                $this->fieldsPosts = array(
                    "api_key_mailchimp"     => "sanitize_text_field"
                );
                break;
            case ProviderHelper::SENDINBLUE:
                $this->fieldsPosts = array(
                    "api_key_sendinblue"   => "sanitize_text_field"
                );
                break;
            case ProviderHelper::SENDGRID:
                $this->fieldsPosts = array(
                    "api_key_sendgrid"   => "sanitize_text_field"
                );
                break;
            default:
                $this->fieldsPosts = apply_filters(DELIPRESS_SLUG . "_connect_provider_wizard_fields_posts", $this->fieldsPosts);
                break;
        }


        $params = $this->getPostParams("fields");

        if(!empty($this->missingParameters)){
            return array(
                "success" => false
            );
        }   

        $response = $this->providerServices->getProviderSettings($provider)->saveOptions($params);
        
        if(!$response["success"]){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::PROVIDER_CONNEXION_ERROR, 
                CodeErrorHelper::getMessage(CodeErrorHelper::PROVIDER_CONNEXION_ERROR)
            );

            return $response;
        }

        do_action(DELIPRESS_SLUG . "_after_connect_provider_step_one", $provider);

        return $response;


    }

    /**
     * @action DELIPRESS_SLUG . "_before_send_provider_step_one"
     * @action DELIPRESS_SLUG . "_after_send_provider_step_one"
     * 
     * @param string $provider
     * @return array
     */
    public function sendProvider($provider){

        do_action(DELIPRESS_SLUG . "_before_send_provider_step_one", $provider);

        $providerApi = $this->providerServices->getProviderApi($provider);
        
        $this->fieldsPosts = array(
            "email_send"     => "sanitize_email",
        );
        
        $params = $this->getPostParams("fields");

        if(!empty($this->missingParameters)){
            return array(
                "success" => false
            );
        }
        
        ob_start();
        include_once DELIPRESS_PLUGIN_DIR_EMAILS . "/welcome.php";
        $html = ob_get_contents(); 
        ob_end_clean();

        $response = $providerApi->sendEmail(
            array(
                "subject" => __("Your first newsletter with DeliPress", "delipress"),
                "html"    => $html,
                "emails" => array(
                    $params["email_send"]
                )
            )
        );


        do_action(DELIPRESS_SLUG . "_after_send_provider_step_one", $provider);

        return $response;

    }

}









