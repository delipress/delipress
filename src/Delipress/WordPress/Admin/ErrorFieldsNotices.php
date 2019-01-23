<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;

/**
 * ErrorFieldsNotices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ErrorFieldsNotices extends AbstractHook implements HooksAdminInterface{


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action('current_screen', array($this, "checkDeliPressPages"));
            
        }
    }

    public function executeErrorFieldsNoticesHelper(){
        $this->errors  = ErrorFieldsNoticesHelper::getErrorNotices();
        $this->success = ErrorFieldsNoticesHelper::getSuccessNotices();
        $this->infos   = ErrorFieldsNoticesHelper::getInfoNotices();

        if($this->errors){
            ErrorFieldsNoticesHelper::deleteErrorNotices();
        }

        if($this->success){
            ErrorFieldsNoticesHelper::deleteSuccessNotices();
        }

        if($this->infos){
            ErrorFieldsNoticesHelper::deleteInfosNotices();
        }

    }

    public function checkDeliPressPages($current_screen){

        if(!$current_screen){
            return;
        }   

        if(strpos($current_screen->id, sprintf("%s_page", DELIPRESS_SLUG) ) !== false) {
            $this->executeErrorFieldsNoticesHelper();
        }

    }

    /**
     * Print HTML
     *
     * @return void
     */
    public function displayAdminNoticesProviderError(){
        foreach($this->errorsProvider as $key => $error){
            AdminNoticesProviderHelper::displayError($key);
        }

        AdminNoticesProviderHelper::deleteErrorNotices();
    }

}
