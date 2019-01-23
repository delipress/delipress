<?php

namespace Delipress\WordPress\Services\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;

/**
 * WizardServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardServices implements ServiceInterface {

    const STEP_WIZARD = "_delipress_step_wizard";

    /**
     * @param int $step
     * @param array $params
     * @return string
     */
    public function getPageWizard($step = 1, $params = array()){
        
        return add_query_arg(
            array_merge(
                array(
                    'page' => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_WIZARD),
                    'step' => $step
                ),
                $params   
            ), 
            admin_url( 'admin.php' ) 
        );
    }

    /**
     * 
     * @return string
     */
    public function getPageSetupUrl(){
        return add_query_arg(
            array(
                'page'   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SETUP),
            ), 
            admin_url( 'admin.php' ) 
        );
    }

    /**
     * @return string
     */
    public function getConnectProviderFormUrl(){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => ActionHelper::WIZARD_STEP_1_CONNECT
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::WIZARD_STEP_1_CONNECT
        );
    }

    /**
     * @return string
     */
    public function getSendProviderFormUrl(){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => ActionHelper::WIZARD_STEP_1_SEND
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::WIZARD_STEP_1_SEND
        );
    }
    
    
    public function getSaveSenderFormUrl(){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => ActionHelper::WIZARD_STEP_SAVE_SENDER
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::WIZARD_STEP_SAVE_SENDER
        );
    }

    /**
     * @return string
     */
    public function getRegisterNewsletterUrl(){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => ActionHelper::DP_REGISTER_NEWSLETTER
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DP_REGISTER_NEWSLETTER
        );
    }


    /**
     * @return string
     */
    public function removeSetupWizardPage(){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => ActionHelper::REMOVE_SETUP_WIZARD_PAGE
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::REMOVE_SETUP_WIZARD_PAGE
        );
    }


}
