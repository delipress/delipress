<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class ConnectorHelper {

    const WORDPRESS_USER         = "wordpress_user";
    const WOOCOMMERCE            = "woocommerce";

    /**
     * @filter DELIPRESS_SLUG . "_list_connectors"
     *
     * @return void
     */
    public static function getConnectors(){

        return apply_filters(DELIPRESS_SLUG . "_list_connectors",
            array(
                self::WORDPRESS_USER       => array(
                    "label"         => __("WordPress users", 'delipress'),
                    "key"           => self::WORDPRESS_USER,
                    "description"   => __("Connector WordPress User", "delipress"),
                    "premium"       => false,
                    "full_premium"  => false
                ),
                self::WOOCOMMERCE       => array(
                    "label"         => __("WooCommerce",'delipress'),
                    "key"           => self::WOOCOMMERCE,
                    "description"   => __("WooCommerce Connector", "delipress"),
                    "premium"       => true,
                    "full_premium"  => true
                )
            )
        );

    }

    /**
     * @param string $connector
     * @return array
     */
    public static function getConnectorByKey($connector){
        $connectors = self::getConnectors();
        if(
            !array_key_exists($connector, $connectors )
        ){
            return null;
        }

        return $connectors[$connector];
    }

    /**
     * @param string $optin
     * @return bool
     */
    protected static function verifyConnector($connector){

        $connectors = self::getConnectors();
        if(
            !array_key_exists($connector, $connectors )
        ){
            wp_redirect(admin_url());
            exit;
        }

        return true;

    }


}
