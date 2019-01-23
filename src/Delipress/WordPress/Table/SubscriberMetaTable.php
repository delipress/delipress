<?php

namespace Delipress\WordPress\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;


/**
 * SubscriberMetaTable
 *
 * @author DeliPress
 */
class SubscriberMetaTable extends AbstractHook  {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->subscriberMeta    = $containerServices->getService("SubscriberMetaTableServices");
    }


}



