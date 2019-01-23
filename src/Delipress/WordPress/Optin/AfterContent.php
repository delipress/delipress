<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


use Delipress\WordPress\Optin\BaseOptin;

use Delipress\WordPress\Helpers\OptinHelper;

/**
 * AfterContent
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class AfterContent extends BaseOptin {

    protected $typeOptin = OptinHelper::AFTER_CONTENT;

    protected $html = false;

    /**
     * @see BaseOptin
     *
     * @return array
     */
    public function getOptins(){
        return $this->optinServices->getAfterContents();
    }

    public function prepareOptin(){

        $this->optins = $this->getOptins();

        if(empty($this->optins)){
            return;
        }

        add_action( 'pre_get_posts', array($this, "isMainQuery") );
        
    }

    public function isMainQuery( $query ) {
        if(!$query->is_main_query()){
            return;
        }

        add_filter(
            "the_content", 
            array($this, "delipressOptinAfterContent"), 
            apply_filters(DELIPRESS_SLUG . "_priority_after_content", 10)
        );
    }


    public function delipressOptinAfterContent($content){

        if(!in_the_loop()){
            return $content;
        }

        ob_start();
        $this->delipressOptin();
        $html = ob_get_contents();
        ob_end_clean();

        remove_filter("the_content", array($this, "delipressOptinAfterContent"));

        return $content . $html;
    }


    

}
