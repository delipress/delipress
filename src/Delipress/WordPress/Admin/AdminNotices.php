<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminNoticesProviderHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;

/**
 * AdminNotices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminNotices extends AbstractHook implements HooksAdminInterface{

    protected $keyNotice = CodeErrorHelper::ADMIN_NOTICE;


    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices    = $containerServices->getService("OptionServices");
    }

    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        add_action( DELIPRESS_SLUG . "_admin_notices_error" , array($this, 'adminNoticesErrors') );
        add_action( DELIPRESS_SLUG . "_admin_notices_info", array($this, 'adminNoticesInfos') );
        add_action( DELIPRESS_SLUG . "_admin_notices_success", array($this, 'adminNoticesSuccess') );

        add_action(DELIPRESS_SLUG . "_admin_notices_provider_error", array($this,'displayAdminNoticesProviderError'));

        add_action( DELIPRESS_SLUG . "_admin_notices_generals", array($this, "isLocal"));
        add_action( DELIPRESS_SLUG . "_admin_notices_generals", array($this, "isProviderConfigured"));
        add_action( DELIPRESS_SLUG . "_admin_notices_generals", array($this, "noticeTracking"));

    }

    /**
     * @return void
     */
    public function noticeTracking(){
        __delipress__god_get_admin_notice();
    }

    /**
     *
     * @return void
     */
    public function isProviderConfigured(){
        $provider = $this->optionServices->getProvider();

        $screen = get_current_screen();
        if($screen->id === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SETUP))) {
            return;
        }


        if(!$provider["is_connect"]){
            ?>
            <div id="delipress__notice-provider" class="error notice">
                <p><span class="dashicons dashicons-editor-code"></span> <?php _e("Warning: you don't have any email service provider yet. Go in <a href=\"admin.php?page=delipress-options\">DeliPress > Settings</a> to setup a provider and access all DeliPress features", 'delipress'); ?></p>
            </div>

            <?php
        }

    }

    /**
     *
     * @return void
     */
    public function isLocal(){

        $screen = get_current_screen();

        if(!$screen){
            return;
        }

        $step = (isset($_GET["step"])) ? (int) $_GET["step"] : null;

        if(
            $screen->id !== sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS ) ) || $step !== 4
        ){
            return;      
        }

        $isLocal = delipress_is_local();

        if($isLocal){
            ?>
            <div id="delipress__notice-local" class="error notice is-dismissible js-delipress-notice">
                <p><span class="dashicons dashicons-editor-code"></span> <?php _e('Your website is local. Some features wont work properly. <a href="https://delipress.io/documentation/frequently-asked-questions/working-locally/" target="_blank">Learn more.</a>', 'delipress'); ?></p>
            </div>

            <?php
        }

    }

    /**
     * Print HTML
     *
     * @return void
     */
    public function displayAdminNoticesProviderError(){
        $this->errorsProvider  = AdminNoticesProviderHelper::getErrorNotices();
        if(!$this->errorsProvider){
            return;
        }

        foreach($this->errorsProvider as $key => $error){
            AdminNoticesProviderHelper::displayError($key);
        }

        AdminNoticesProviderHelper::deleteErrorNotices();
    }

    /**
     * Print HTML
     *
     * @return void
     */
    public function adminNoticesErrors(){
        $this->errors  = AdminNoticesHelper::getErrorNotices();

        $viewErrorProvider = true;


        if($this->errors && isset($this->errors[$this->keyNotice])){

        ?>
            <div class="delipress__notice delipress__notice--wrong js-delipress-notice-close">
                <p>
                    <span class="dashicons dashicons-warning"></span>
                    <?php echo $this->errors[$this->keyNotice]["message"]; ?>
                <p>
            </div>

        <?php

            AdminNoticesHelper::deleteErrorNotices();
        }

    }

    /**
     * Print HTML
     *
     * @return void
     */
    public function adminNoticesInfos(){

        $this->infos   = AdminNoticesHelper::getInfoNotices();

        if($this->infos && isset($this->infos[$this->keyNotice])){

        ?>
            <div class="delipress__notice delipress__notice--info js-delipress-notice-close">
                <p>
                    <span class="dashicons dashicons-megaphone"></span>
                    <?php echo $this->infos[$this->keyNotice]["message"]; ?>
                <p>
            </div>

        <?php
            AdminNoticesHelper::deleteInfosNotices();
        }
    }

    /**
     * Print HTML
     *
     * @return void
     */
    public function adminNoticesSuccess(){

        $this->success = AdminNoticesHelper::getSuccessNotices();
        if($this->success && isset($this->success[$this->keyNotice])){

        ?>
            <div class="delipress__notice delipress__notice--ok js-delipress-notice-close">
                <p>
                    <span class="dashicons dashicons-thumbs-up"></span>
                    <?php echo $this->success[$this->keyNotice]["message"]; ?>
                <p>
            </div>

        <?php
            AdminNoticesHelper::deleteSuccessNotices();
        }

    }

}
