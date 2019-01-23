<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ConnectorHelper;

use Delipress\WordPress\Models\ListModel;



/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractConnectorHook extends AbstractHook implements  HooksInterface {

    protected $connector = null;

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->connectorServices              = $containerServices->getService("ConnectorServices");
        $this->optionServices                 = $containerServices->getService("OptionServices");
        $this->createSubscriberServices       = $containerServices->getService("CreateSubscriberServices");
        $this->deleteSubscriberServices       = $containerServices->getService("DeleteSubscriberServices");
        $this->synchronizeListServices        = $containerServices->getService("SynchronizeListServices");
        $this->synchronizeSubscriberServices  = $containerServices->getService("SynchronizeSubscriberServices");
        $this->listServices                   = $containerServices->getService("ListServices");
        $this->subscriberServices             = $containerServices->getService("SubscriberServices");
        $this->providerServices               = $containerServices->getService("ProviderServices");
    }


}









