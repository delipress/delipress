<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class TemplateLibraryHelper{

    /**
     * @param int $key
     * @return array|null
     */
    public static function getTemplateByKey($key){

        $templates = self::getTemplatesLibrary();

        if(!array_key_exists($key, $templates)){
            return null;
        }

        return $templates[$key];

    }

    /**
     *
     * @return array
     */
    public static function getTemplatesLibrary(){
        
        return apply_filters(DELIPRESS_SLUG . "_template_library", 
            array(
                array(
                    "file" => DELIPRESS_PLUGIN_DIR_TEMPLATES_EMAILS . "/one-column.php",
                    "name" => esc_html__("One column", "delipress"),
                    "image" => DELIPRESS_PATH_PUBLIC_IMG . "/templates/1-column.png",
                ),
                array(
                    "file" => DELIPRESS_PLUGIN_DIR_TEMPLATES_EMAILS . "/two-columns.php",
                    "name" => esc_html__("Two columns", "delipress"),
                    "image" => DELIPRESS_PATH_PUBLIC_IMG . "/templates/template-1-2-column.png",
                )
            )
        );

    }
}
