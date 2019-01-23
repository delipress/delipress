<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class MetaProviderHelper{

    const FIRST_NAME = "first_name";
    const LAST_NAME  = "last_name";

    public static function getListMetaProvider(){

        return apply_filters(DELIPRESS_SLUG . "_list_meta_provider", array(
            self::FIRST_NAME => array(
                "name"      => self::FIRST_NAME,
                "active"    => true,
                "meta_list" => false,
                "type"      => "static",
                "datatype"  => "str"
            ),
            self::LAST_NAME => array(
                "name"      => self::LAST_NAME,
                "active"    => true,
                "meta_list" => false,
                "type"      => "static",
                "datatype"  => "str"
            ),
        ));
    }

}
