<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\InterfaceModel\ApiProviderInterface;
use Delipress\WordPress\Models\ListModel;


/**
 * AbstractProviderApi
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractProviderApi implements MediatorServicesInterface, ApiProviderInterface {
    
    protected $options = null;

    protected $client  = null;

    protected $safeError = false;

    public function setSafeError($safeError){
        $this->safeError = $safeError;
        return $this;
    }

    /**
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices = $services["OptionServices"];

    }

    public function getOptions(){
        if($this->options === null){
            $this->options = $this->optionServices->getOptions();
        }

        return $this->options;
    }

     /**
     *
     * @param array $params
     * @return array
     */
    public function sendCampaignTestEmail($params){
        $subject = "[Test] : " . $params["subject"];
        
        $options = $this->optionServices->getOptions(true);

        $fromTo = $options["options"]["from_to"];

        $headers = array('Content-Type: text/html; charset=UTF-8');
        foreach($params["emails"] as $email){
            wp_mail($email, $subject, $params["html"], $headers);
        }

        return array(
            "success" => true
        );
    }



}


