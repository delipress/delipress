<?php

namespace Delipress\WordPress\Services\Provider;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\FactoryInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Services\Provider\Mailjet\MailjetSettings;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpSettings;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueSettings;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridSettings;
/**
 * ProviderSettingsFactory
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ProviderSettingsFactory implements FactoryInterface, MediatorServicesInterface{

    /**
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices            = $services["OptionServices"];
        $this->listServices              = $services["ListServices"];
        $this->synchronizeListServices   = $services["SynchronizeListServices"];

    }

    /** 
     * @filter DELIPRESS_SLUG . "_get_provider_settings_factory"
     * 
     * @param  string $provider
     * @return string
     */
    public function getProviderSettings($provider){

        switch($provider){
            case ProviderHelper::MAILJET:
                $settings = new MailjetSettings();
                break;
            case ProviderHelper::MAILCHIMP:
                $settings = new MailchimpSettings();
                break;
            case ProviderHelper::SENDINBLUE:
                $settings = new SendinBlueSettings();
                break;
            case ProviderHelper::SENDGRID:
                $settings = new SendGridSettings();
                break;
            default:
                $settings = apply_filters(DELIPRESS_SLUG . "_get_provider_settings_factory", $provider);
                break;
        }

        if($settings instanceOf MediatorServicesInterface){
            $settings->setServices(
                array(
                    "OptionServices"            => $this->optionServices,
                    "ListServices"              => $this->listServices,
                    "SynchronizeListServices"   => $this->synchronizeListServices,
                )
            );
        }

        return $settings;
    }
}


