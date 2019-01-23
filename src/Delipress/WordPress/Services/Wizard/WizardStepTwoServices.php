<?php

namespace Delipress\WordPress\Services\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;


/**
 * WizardStepTwoServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardStepTwoServices implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices     = $services["OptionServices"];
        $this->providerServices   = $services["ProviderServices"];
        $this->createListServices = $services["CreateListServices"];

    }
    
    public function registerNewsletterDelipress($email){

        if(DELIPRESS_VERSION == "{VERSION}"){
            $url = "http://delipress-site.dev/wp-json/delipress/register";
        }
        else{
            $url = "https://delipress.io/wp-json/delipress/register";
        }

        $params = array(
            "email" => $email
        ); 

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params,true) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, 
            array(
                "Content-Type: application/json",
                "Accept: application/json"
            )
        ); 
        $result = curl_exec($ch);

        return array(
            "success" => true
        );

    }

}
