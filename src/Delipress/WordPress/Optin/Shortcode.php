<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


use Delipress\WordPress\Optin\BaseOptin;

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\MarkupIncentiveHelper;

/**
 * Shortcode
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Shortcode extends BaseOptin {

    protected $typeOptin = OptinHelper::SHORTCODE;

    public function __construct(){
        add_shortcode( 'delipress_optin', array($this, 'delipressOptinShortcode') );
    }

    /**
     * @see delipress_optin
     *
     * @param array $attrs
     * @return void
     */
    public function delipressOptinShortcode($attrs){

        if(!isset($attrs["id"])){
            return null;
        }

        if(!function_exists("is_plugin_active")){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        if(is_plugin_active("elementor/elementor.php")){
            if(is_admin()){
                return sprintf("[delipress_optin id='%s']", $attrs["id"] );
            }
        }
        
       $optin = $this->optinServices->getShortcodes(array(
            "where" => array(
                array(
                    "field" => "ID",
                    "value" => $attrs["id"]
                )
            )
        ));

        if(empty($optin)){
            return null;
        }

        $isLoadedScript = OptinHelper::isOptinScriptLoaded();
        
        if(!$isLoadedScript){
            OptinHelper::loadOptinScript(true);
        }
        
        $licenseStatusValid = $this->optionServices->isValidLicense();
        
        $markup = "";
        if(!$licenseStatusValid){
            $markup = MarkupIncentiveHelper::printWhiteMarkupOptin();
        }

        return sprintf(
            "<div 
                id='DELI-shortcode-%s' 
                class='delipress-optin' 
                data-config='%s' 
                data-id='%s' 
                data-type='%s'
            ></div>" . $markup,
            $attrs['id'],
            esc_attr($optin[$attrs["id"]]["config"]),
            $attrs['id'],
            $this->typeOptin
        );
    }

    

}
