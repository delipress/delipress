<?php

namespace Delipress\WordPress\Services\Provider;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\FactoryInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Services\Provider\Mailjet\MailjetExport;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpExport;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridExport;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueExport;

use Delipress\WordPress\Async\ExportToProviderBackgroundProcess;
/**
 * ProviderImportExportFactory
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ProviderImportExportFactory implements FactoryInterface, MediatorServicesInterface{

    /**
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices          = $services["OptionServices"];
        $this->providerServices        = $services["ProviderServices"];
        $this->listServices            = $services["ListServices"];
        $this->synchronizeListServices = $services["SynchronizeListServices"];


    }



    /** 
     * @filter DELIPRESS_SLUG . "_get_provider_export_factory"
     * 
     * @param  string $provider
     * @return string
     */
    public function getProviderExport($provider){
        
        switch($provider){
            case ProviderHelper::MAILJET:
                $export = new MailjetExport();
                break;
            case ProviderHelper::MAILCHIMP:
                $export = new MailchimpExport();
                break;
            case ProviderHelper::SENDGRID:
                $export = new SendGridExport();
                break;
            case ProviderHelper::SENDINBLUE:
                $export = new SendinBlueExport();
                break;
            default:
                $export = apply_filters(DELIPRESS_SLUG . "_get_provider_export_factory", $provider);
                break;
        }

        if($export instanceOf MediatorServicesInterface){
            $export->setServices(
                array(
                    "OptionServices"          => $this->optionServices,
                    "SynchronizeListServices" => $this->synchronizeListServices,
                    "ProviderServices"        => $this->providerServices
                )
            );
        }
        
        return $export;
    }

}


