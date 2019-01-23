<?php

namespace DeliSkypress;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\Models\ContainerServiceInterface;
use DeliSkypress\Models\HooksInterface;
use DeliSkypress\ContainerServiceTrait;

class ContainerActions extends Container implements ContainerInterface
{
    /**
     *
     * @return array
     */
    public function getActions(){
        return $this->getServices();
    } 


    /**
     * @param string $key
     */
    public function getAction($key){
        
        return $this->getAction($key);
        
    }

    /**
     * @param HooksInterface $action
     *
     * @return Container
     */
    public function setAction(HooksInterface $action){

        $this->setService($action);
    }

    /**
     * @param HooksInterface[] $services default empty
     *
     * @return Container
     */
    public function setActions($actions = array()){
        $this->setServices($actions);
    }

}