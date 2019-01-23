<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ConnectorHelper;

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class SourceSubscriberHelper{

    const ADMINISTRATION      = "administration";
    const IMPORT_MAILJET      = "import_mailjet";
    const IMPORT_MAILCHIMP    = "import_mailchimp";

    
    public static function getSourceSubscriber($sourceSubscriber){

        $messages = array(
            self::ADMINISTRATION                   => __("Administration", "delipress"),
            self::IMPORT_MAILJET                   => __("Import Mailjet", "delipress"),
            self::IMPORT_MAILCHIMP                 => __("Import Mailchimp", "delipress"),
            ConnectorHelper::WORDPRESS_USER        => __("WordPress User Connector", "delipress"),
            ConnectorHelper::WOOCOMMERCE           => __("WooCommerce Connector", "delipress"),
        );

        if(array_key_exists($sourceSubscriber, $messages)){
            return $messages[$sourceSubscriber];
        }

        return apply_filters(DELIPRESS_SLUG . "_get_source_subscriber", false, $sourceSubscriber);
    }
}









