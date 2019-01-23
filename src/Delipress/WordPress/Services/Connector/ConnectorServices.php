<?php

namespace Delipress\WordPress\Services\Connector;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\InterfaceModel\ConnectorInterface;

use Delipress\WordPress\Helpers\ConnectorHelper;

/**
 * ConnectorServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ConnectorServices implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->wordpressUserServices = $services["WordPressUserServices"];
        $this->woocommerceServices   = $services["WooCommerceServices"];
    }

    /**
     *
     * @param string $connector
     * @return ConnectorInterface
     */
    public function getConnector($connector){

        switch($connector){
            case ConnectorHelper::WORDPRESS_USER:
                $connectorService = $this->wordpressUserServices;
                break;
            case ConnectorHelper::WOOCOMMERCE:
                $connectorService = $this->woocommerceServices;
                break;
            default:
                $connectorService = apply_filters(DELIPRESS_SLUG . "_create_list_connector", $connector);
                break;
        }

        return $connectorService;
    }

    /**
     *
     * @param string $connector
     * @return void
     */
    public function createListConnector($connector){
        
        $connectorService = $this->getConnector($connector);

        if($connectorService instanceOf ConnectorInterface){
            $connectorService->prepareConnector();
        }

    }


}









