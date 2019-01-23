<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.1.0
 * @since 1.1.0
 */
abstract class SubscriberMetaHelper{

    const EMAIL      = "user_email";
    const WORDPRESS_USER_CREATED    = "user_registered";

    const WORDPRESS_META_FIRST_NAME = "first_name";
    const WORDPRESS_META_LAST_NAME  = "last_name";

    const WOO_BILLING_CITY       = "billing_city";
    const WOO_BILLING_FIRST_NAME = "billing_first_name";
    const WOO_BILLING_LAST_NAME  = "billing_last_name";
    const WOO_BILLING_EMAIL      = "billing_email";
    const WOO_BILLING_COUNTRY    = "billing_country";
    const WOO_BILLING_POSTCODE   = "billing_postcode";
    const WOO_BILLING_PHONE      = "billing_phone";
    const WOO_BILLING_STATE      = "billing_state";
    const WOO_BILLING_COMPANY    = "billing_company";

    public static function getMetaWooCommerce(){
        global $delipressPlugin;

        $optionServices = $delipressPlugin->getService("OptionServices");
        if(!$optionServices->isValidLicense()){
            return array();
        }
        
        return array(
            array(
                "id"            => self::WOO_BILLING_CITY,
                "name"          => __("(WooCommerce) Billing city", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_FIRST_NAME,
                "name"          => __("(WooCommerce) Billing First name", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_LAST_NAME,
                "name"          => __("(WooCommerce) Billing Last name", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_EMAIL,
                "name"          => __("(WooCommerce) Billing email", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_COUNTRY,
                "name"          => __("(WooCommerce) Billing country", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_POSTCODE,
                "name"          => __("(WooCommerce) Billing postcode", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_PHONE,
                "name"          => __("(WooCommerce) Billing phone", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_STATE,
                "name"          => __("(WooCommerce) Billing state", "delipress"),
            ),
            array(
                "id"            => self::WOO_BILLING_COMPANY,
                "name"          => __("(WooCommerce) Billing company", "delipress"),
            ),
        );
        

    }

    /**
     *
     * @return array
     */
    public static function getSubscriberMetas(){

        global $delipressPlugin;

        $optionServices = $delipressPlugin->getService("OptionServices");

        $metaWoo = array();
        if( 
            is_plugin_active("woocommerce/woocommerce.php") && 
            $optionServices->isValidLicense()
        ){
            $metaWoo        = self::getMetaWooCommerce();
        }

        $metaUsers = self::getWordPressUser();
        $metaUsersMetas = self::getWordPressUserMeta();

        return array_merge(
            apply_filters(DELIPRESS_SLUG . "_get_subscriber_metas_available", array()),
            $metaWoo,
            $metaUsers,
            $metaUsersMetas
        );
    }

    /**
     *
     * @return array
     */
    public static function getWordPressUserMeta(){
        return array(
            array(
                "id"   => self::WORDPRESS_META_FIRST_NAME,
                "name" => self::WORDPRESS_META_FIRST_NAME,
                "title" => __("First name", "delipress")
            ),
            array(
                "id"        => self::WORDPRESS_META_LAST_NAME,
                "name"      => self::WORDPRESS_META_LAST_NAME,
                "title"     => __("Last name", "delipress"),
            )
        );
    }

    /**
     * @return array
     */
    public static function getWordPressUser(){
        return array(
            array(
                "id"   => self::WORDPRESS_USER_CREATED,
                "name" => __("Date user registered", "delipress")
            ),
            array(
                "id"        => self::EMAIL,
                "name"      => __("Email", "delipress"),
            )
        );
    }

    /**
     * @return array
     */
    public static function getKeysWordPressUser(){
        return array(
            self::WORDPRESS_USER_CREATED,
            self::EMAIL
        );
    }

    /**
     * @return array
     */
    public static function getKeysWordPressUserMeta(){
        return array(
            self::WORDPRESS_META_FIRST_NAME,
            self::WORDPRESS_META_LAST_NAME
        );
    }

    /**
     * @return array
     */
    public static function getKeysMetaWooCommerce(){
        return array(
            self::WOO_BILLING_CITY,      
            self::WOO_BILLING_FIRST_NAME,
            self::WOO_BILLING_LAST_NAME, 
            self::WOO_BILLING_EMAIL,     
            self::WOO_BILLING_COUNTRY,   
            self::WOO_BILLING_POSTCODE,  
            self::WOO_BILLING_PHONE,     
            self::WOO_BILLING_STATE,     
            self::WOO_BILLING_COMPANY
        );
    }

}










