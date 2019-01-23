<?php

namespace Delipress\WordPress\Cron;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Cron\AbstractCron;

use DeliSkypress\Models\ContainerInterface;

/**
 * LicenseCron
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class LicenseCron extends AbstractCron {
    
    protected $name = "delipress_license_cron";

    public function __construct(){
        $date = new \DateTime("tomorrow midnight");
        parent::__construct($date);
    }

     /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        parent::setContainerServices($containerServices);
        $this->optionServices    = $containerServices->getService("OptionServices");
        
    }


    /**
     * @see AbstractCron
     * @return void
     */
    public function executeCron(){
        $license = $this->optionServices->getLicenseKey();
        if(empty($license)){
            return;
        }
        $result  = $this->optionServices->checkLicense($license);

        if(!$result["success"]){
            $this->optionServices->setOptionsByKey(
                array(
                    "license_status" => array(
                        "status"  => $result["results"]["status"],
                        "message" => $result["results"]["message"],
                    )
                ),
                "options"
            );
        }
    }

}
