<?php

namespace Delipress\WordPress\Integration\ContactForm7;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksFrontInterface;
use DeliSkypress\Models\ContainerInterface;

use Delipress\WordPress\Integration\AbstractIntegration;

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;


class ContactForm7Frontend extends AbstractIntegration implements HooksFrontInterface {

    /**
     *
     * @return boolean
     */
    protected function isAuthorize(){
        return parent::isAuthorize() && function_exists( 'wpcf7_add_form_tag' );
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){

        if( !function_exists("wpcf7_add_form_tag")){
            return;
        }

        $shortcode = $this->getShortcode();
        wpcf7_add_form_tag( $shortcode, array( $this, 'shortcode' ), false );
        add_action( 'wpcf7_mail_sent', array( $this, 'mailSent' ), 1 );

    }

    public function mailSent($cf7_form){
        if(!$this->isAuthorize()){
            return;
        }

        $data = $_POST;

        if(
            !isset($data["delipress_checkbox"]) || empty($data["delipress_checkbox"]) ||
            !isset($data["delipress_optin_id"]) || empty($data["delipress_optin_id"]) ||
            !isset($data["delipress-email"]) || empty($data["delipress-email"])
        ){
            return;
        }
        
        $idOptin = (int) $data["delipress_optin_id"];
        $params  = $this->getParams($data);

        $this->addSubscriber($idOptin, $params);

    }

    /**
     *
     * @param array $attrs
     * @return string
     */
    public function shortcode($attrs = array()){
        if(empty($attrs)){
            return;
        }
        if(!isset($attrs["options"][0])){
            return;
        }
        
        $options = array(
            "label"    => ""
        );
        
        $opt     = "delipress-optin";
        $pattern = sprintf( '/%s:%s$/i', preg_quote($opt), ".+" );
        preg_match( $pattern, $attrs["options"][0], $matches );
        $optinId  = substr( $matches[0], strlen( $opt ) + 1 );

        if(empty($optinId)){
            return;
        }

        $options["optin_id"] = $optinId;
        
		if ( ! empty( $attrs['labels'][0] ) ) {
			$options['label'] = $attrs['labels'][0];
		}        
        
        return $this->getHtmlShortcode($optinId, $options);
    }
  

}
