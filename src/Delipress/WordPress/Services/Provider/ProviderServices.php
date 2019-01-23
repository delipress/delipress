<?php

namespace Delipress\WordPress\Services\Provider;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\FactoryInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;


use Delipress\WordPress\Services\Provider\Mailjet\MailjetList;
use Delipress\WordPress\Services\Provider\Mailjet\MailjetSubscriber;
use Delipress\WordPress\Services\Provider\Mailjet\MailjetMeta;

use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpList;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpSubscriber;
use Delipress\WordPress\Services\Provider\Mailchimp\MailchimpMeta;

use Delipress\WordPress\Services\Provider\SendGrid\SendGridList;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridSubscriber;
use Delipress\WordPress\Services\Provider\SendGrid\SendGridMeta;


use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueList;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueSubscriber;
use Delipress\WordPress\Services\Provider\SendinBlue\SendinBlueMeta;


/**
 * ProviderServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ProviderServices implements ServiceInterface, MediatorServicesInterface{

    public function __construct(
        FactoryInterface $providerSettingsFactory, 
        FactoryInterface $providerApiFactory, 
        FactoryInterface $providerImportExportFactory
    ){
        $this->providerSettingsFactory         = $providerSettingsFactory;
        $this->providerApiFactory              = $providerApiFactory;
        $this->providerImportExportFactory     = $providerImportExportFactory;
    }

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices = $services["OptionServices"];

        if($this->providerApiFactory instanceOf MediatorServicesInterface){
            $this->providerApiFactory->setServices($services); 
        }

        if($this->providerSettingsFactory instanceOf MediatorServicesInterface){
            $this->providerSettingsFactory->setServices($services); 
        }

        if($this->providerImportExportFactory instanceOf MediatorServicesInterface){
            $this->providerImportExportFactory->setServices($services); 
        }

    }

    /**
     * @return string
     */
    public function getUrlFormAdminPost(){
        
        $provider = ProviderHelper::getProviderFromUrl();

        return add_query_arg(
            array(
                'action'   => ActionHelper::SETTINGS_PROVIDER,
                'provider' => $provider["key"]
            ), 
            admin_url( 'options.php' )
        );
    }

    /**
     * 
     * @param string $provider
     * @return string
     */
    public function getUrlDisconnectProvider($provider){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"   => ActionHelper::DISCONNECT_PROVIDER,
                    "provider" => $provider
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DISCONNECT_PROVIDER
        );
    }

    /**
     *
     * @param string $provider
     * @param null|array $object
     * @return SubscriberInterface
     */
    public function getSubscriberModel($provider, $object = null){
         
        switch($provider){
            case ProviderHelper::MAILJET:
                return new MailjetSubscriber($object);
                break;
            case ProviderHelper::MAILCHIMP:
                return new MailchimpSubscriber($object);
                break;
            case ProviderHelper::SENDGRID:
                return new SendGridSubscriber($object);
                break;
            case ProviderHelper::SENDINBLUE:
                return new SendinBlueSubscriber($object);
                break;
        }
    }

    /**
     *
     * @param string $provider
     * @param null|array $object
     * @return ListInterface
     */
    public function getListModel($provider, $object = null){
         
        switch($provider){
            case ProviderHelper::MAILJET:
                return new MailjetList($object);
                break;
            case ProviderHelper::MAILCHIMP:
                return new MailchimpList($object);
                break;
            case ProviderHelper::SENDGRID:
                return new SendGridList($object);
                break;
            case ProviderHelper::SENDINBLUE:
                return new SendinBlueList($object);
                break;
        }
    }

    public function getMetaModel($object = null){
        $providerKey = $this->optionServices->getProviderKey();

        switch($providerKey){
             case ProviderHelper::MAILJET:
                return new MailjetMeta($object);
            case ProviderHelper::MAILCHIMP:
                return new MailchimpMeta($object);
            case ProviderHelper::SENDGRID:
                return new SendGridMeta($object);
            case ProviderHelper::SENDINBLUE:
                return new SendinBlueMeta($object);
        }
    }

    /**
     * 
     * @param string $provider
     * @param array $options
     * @return Object
     */
    public function prepareProvidersSettings($provider, $options){
        return $this->getProviderSettings($provider)->prepareProvidersSettings($options);

    }

    /**
     * 
     * @param string $provider
     * @return Object
     */
    public function getProviderSettings($provider = null){
        
        if($provider === null){
            $provider = $this->optionServices->getProviderKey();
        }

        $providerSettings = $this->providerSettingsFactory
                                 ->getProviderSettings($provider);

        $providerSettings->setApi(
            $this->getProviderApi($provider)
        );
        
        return $providerSettings;
    }

    /**
     * 
     * @param string $provider
     * @return Object
     */
    public function getProviderImport($provider){
        $providerImport = $this->providerImportExportFactory
                               ->getProviderImport($provider);


        return $providerImport->setApi(
                        $this->getProviderApi($provider)
                    );

    }

    /**
     * 
     * @param string $provider
     * @return Object
     */
    public function getProviderExport($provider){
        $providerExport = $this->providerImportExportFactory
                               ->getProviderExport($provider);

        return $providerExport->setApi(
                        $this->getProviderApi($provider)
                    );

    }
   

    /**
     * 
     * @param string $provider
     * @return Object
     */
    public function getProviderApi($provider = null){
        if($provider === null){
            $provider = $this->optionServices->getProviderKey();
        }

        return $this->providerApiFactory
                    ->getProviderApi($provider);
    }

    /**
     * 
     * @param string $provider
     * @return Object
     */
    public function getProviderStatistic($provider){
        $providerStatistic = $this->providerApiFactory
                                 ->getProviderStatistic($provider);

        return $providerStatistic->setApi(
                        $this->getProviderApi($provider)
                    );
    }
}


