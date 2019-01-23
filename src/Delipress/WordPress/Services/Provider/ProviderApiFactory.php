<?php

namespace Delipress\WordPress\Services\Provider;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\FactoryInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Services\Provider\Mailjet\MailjetApi;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpApi;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueApi;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridApi;

use Delipress\WordPress\Services\Provider\Mailjet\MailjetStatistic;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpStatistic;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridStatistic;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueStatistic;



/**
 * ProviderApiFactory
 *
 * @author Delipress
 */
class ProviderApiFactory implements FactoryInterface, MediatorServicesInterface{

    /**
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices                  = $services["OptionServices"];
        $this->listServices                    = $services["ListServices"];
        $this->createSubscriberServices        = $services["CreateSubscriberServices"];
        $this->subscriberServices              = $services["SubscriberServices"];
        $this->deleteSubscriberServices        = $services["DeleteSubscriberServices"];
        $this->synchronizeListServices         = $services["SynchronizeListServices"];

    }

    /** 
     * @filter DELIPRESS_SLUG . "_get_provider_api_factory"
     * 
     * @param  string $provider
     * @return string
     */
    public function getProviderApi($provider = null){
        if($provider === null){
            $provider = $this->optionServices->getProviderKey();
        }
        
        switch($provider){
            case ProviderHelper::MAILJET:
                $api = new MailjetApi();
                break;
            case ProviderHelper::MAILCHIMP:
                $api = new MailchimpApi();
                break;
            case ProviderHelper::SENDINBLUE:
                $api = new SendinBlueApi();
                break;
            case ProviderHelper::SENDGRID:
                $api = new SendGridApi();
                break;
            default:
                $api = apply_filters(DELIPRESS_SLUG . "_get_provider_api_factory", $provider);
                break;
        }

        if($api instanceOf MediatorServicesInterface){
            $api->setServices(
                array(
                    "OptionServices" => $this->optionServices
                )
            );
        }

        return $api;
    }


    /**
     *
     * @param string $provider
     * @return AbstractProviderStatistic
     */
    public function getProviderStatistic($provider){
        switch($provider){
            case ProviderHelper::MAILJET:
                $statistic = new MailjetStatistic();
                break;
            case ProviderHelper::MAILCHIMP:
                $statistic = new MailchimpStatistic();
                break;
            case ProviderHelper::SENDGRID:
                $statistic = new SendGridStatistic();
                break;
            case ProviderHelper::SENDINBLUE:
                $statistic = new SendinBlueStatistic();
                break;
            default:
                $statistic = apply_filters(DELIPRESS_SLUG . "_get_provider_statistic_factory", $provider);
                break;
        }

        return $statistic;
    }

  
}


