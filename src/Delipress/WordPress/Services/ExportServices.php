<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ActionHelper;


/**
 * ExportServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ExportServices implements ServiceInterface, MediatorServicesInterface {
    
    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        if(!array_key_exists("ListServices", $services)){
            throw new Exception("Miss ListServices");
        }

        $this->listServices = $services["ListServices"];

    }

    public function setTypeExport($type){
        $this->type = $type;
        return $this;
    }

    /**
     * @see StrategyInterface
     * @return void
     */
    public function execute(){
        if($this->strategy === null){
            throw new Exception("No strategy");
        }

        $data     = null;
        $filename = "export";
        switch ($this->type) {
            case 'settings':
            default:

                break;
            case ActionHelper::LIST_EXPORT:
                $date     = new \DateTime("now");
                $filename = sprintf("list_export_%s", $date->format("Y-m-d"));
                $data     = $this->listServices->getLists();
                break;

        }

        $this->strategy
             ->setData($data)
             ->setFilename($filename)
             ->execute();


    }

}
