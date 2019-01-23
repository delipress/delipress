<?php

namespace Delipress\WordPress\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;


/**
 * OptinStatsTable
 *
 * @author DeliPress
 */
class OptinStatsTable extends AbstractHook implements ActivationInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optinStatsTable    = $containerServices->getService("OptinStatsTableServices");
    }

    /**
     *  @see ActivationInterface
     */
    public function activation(){
        $this->optinStatsTable->createTable();
    }



}



