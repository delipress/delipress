<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PrepareModelHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Traits\TranslateBuilder;

/**
 * Pages
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Pages extends AbstractHook implements HooksAdminInterface{

    use TranslateBuilder;


    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->pageAdminServices       = $containerServices->getService("PageAdminServices");
        $this->campaignServices        = $containerServices->getService("CampaignServices");
        $this->optionServices          = $containerServices->getService("OptionServices");
        $this->providerServices        = $containerServices->getService("ProviderServices");
        $this->listServices            = $containerServices->getService("ListServices");
        $this->subscriberServices      = $containerServices->getService("SubscriberServices");
        $this->synchronizeServices     = $containerServices->getService("SynchronizeServices");
        $this->synchronizeListServices = $containerServices->getService("SynchronizeListServices");
        $this->wizardServices          = $containerServices->getService("WizardServices");
        $this->optinServices           = $containerServices->getService("OptinServices");
        $this->templateUrlServices     = $containerServices->getService("TemplateUrlServices");
        $this->optinStatsServices      = $containerServices->getService("OptinStatsServices");
        $this->createCampaignServices  = $containerServices->getService("CreateCampaignServices");
        $this->metaSubscriberServices  = $containerServices->getService("MetaSubscriberServices");
        $this->specification           = $containerServices->getService("Specification");
        $this->metaServices            = $containerServices->getService("MetaServices");
        $this->templateServices        = $containerServices->getService("TemplateServices");
    }


    /**
     * @see HooksAdminInterface
     * @filter DELIPRESS_SLUG . "_admin_template_main_page"
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            $this->mainPage = apply_filters(DELIPRESS_SLUG . "_admin_template_main_page", DELIPRESS_PLUGIN_DIR_TEMPLATES . "/admin/page.php");

            add_action( 'admin_menu', array( $this, 'addPluginMenu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
            add_action( 'admin_head', array( $this, 'menuOrderCount' ) );
            add_action( 'admin_head', array( $this, 'menuHighlight' ) );
            add_filter( 'upload_mimes', array($this, 'mimesTypes') );
        }

    }

    /**
     * @see admin_head
     *
     * @return void
     */
    public function menuHighlight() {

        global $parent_file, $submenu_file, $post_type, $taxonomy, $wp_query;

        $alreadyChange = false;

        switch ( $post_type ) {
            case PostTypeHelper::CPT_CAMPAIGN:
                $parent_file   = DELIPRESS_SLUG;
                $alreadyChange = true;
            break;
        }

        if(!$alreadyChange){
            switch ($taxonomy) {
                case TaxonomyHelper::TAXO_LIST:
                    $parent_file   = DELIPRESS_SLUG;
                    $alreadyChange = true;
                    break;
            }
        }

    }

    /**
     * Construct plugin menu
     *
     * @return void
     */
    public function addPluginMenu(){

        $pages = $this->pageAdminServices->getPages();
        add_menu_page( null, "DeliPress", 'manage_options', DELIPRESS_SLUG, null, "none", PageAdminHelper::getPagePosition() );

        foreach ($pages as $key => $page) {
            $callback = $this->getCallbackPage($key);

            if(null !== $callback){
                add_submenu_page(
                    DELIPRESS_SLUG,
                    $page["page_title"],
                    $page["menu_title"],
                    $page["capability"],
                    $page["slug"],
                    array($this, $callback)
                );
            }

            else{
                add_submenu_page(
                    DELIPRESS_SLUG,
                    $page["page_title"],
                    $page["menu_title"],
                    $page["capability"],
                    $page["slug"]
                );
            }
        }

    }

    /**
     * @return void
     */
    public function menuOrderCount() {
        global $submenu;

        if ( isset( $submenu[DELIPRESS_SLUG] ) ) {
            $pagesUnset = $this->pageAdminServices->getPagesUnset();

            foreach ($submenu[DELIPRESS_SLUG] as $key => $value) {
                if(in_array($value[2], $pagesUnset)){
                    unset($submenu[DELIPRESS_SLUG][$key]);
                }
            }
        }

    }

    /**
     * @param string $key
     * @return string
     */
    public function getCallbackPage($key){
        $callback = null;
        switch ($key) {

            case PageAdminHelper::PAGE_SETUP:
                $callback = "delipressSetupPage";
                break;
            case PageAdminHelper::PAGE_CAMPAIGNS:
                $callback = "delipressCampaignsPage";
                break;
            case PageAdminHelper::PAGE_LISTS:
                $callback = "delipressListsPage";
                break;
            case PageAdminHelper::PAGE_AUTOMATION:
                $callback = "delipressAutomationPage";
                break;
            case PageAdminHelper::PAGE_OPTIN_FORMS:
                $callback = "delipressOptinFormsPage";
                break;
            case PageAdminHelper::PAGE_OPTIONS:
                $callback = "delipressOptionsPage";
                break;
            case PageAdminHelper::PAGE_SYNCHRONIZE:
                $callback = "delipressSynchronize";
                break;
            default:
                $this->namePageInclude = $key;
                $callback = "delipressDefaultPage";
                break;
        }

        return $callback;
    }

    /**
     * @see includes_url( 'js/tinymce/wp-tinymce.php' )
     */
    protected function wpTinymcePhp(){
        wp_enqueue_script("delipress-tinymce", includes_url( '/js/tinymce/tinymce.min.js' ) );
        wp_enqueue_script("delipress-compat3x", includes_url( '/js/tinymce/plugins/compat3x/plugin.min.js' ) );

    }

    /**
     * @param string $page
     * @action DELIPRESS_SLUG . '_admin_enqueue_scripts'
     *
     * @return void
     */
    public function adminEnqueueScripts($page){

        do_action(DELIPRESS_SLUG . '_admin_enqueue_scripts', $page);

        // Global styles for admin (menu styles override...)
        wp_enqueue_style('delipress-admin-global', DELIPRESS_PATH_PUBLIC_CSS . '/delipress.css', array());

        if (substr($page, 0, strlen('delipress_page')) == 'delipress_page'){

            wp_enqueue_style('delipress-admin-vendor-css', DELIPRESS_PATH_PUBLIC_CSS . '/vendor.css');
            wp_enqueue_style('delipress-admin-css', DELIPRESS_PATH_PUBLIC_CSS . '/backend.css');

            wp_register_script( 'delipress-vendor', DELIPRESS_PATH_PUBLIC_JS . '/vendor.js' , array( 'jquery' ), DELIPRESS_VERSION);
            wp_enqueue_script( 'delipress-vendor' );

            wp_register_script( 'delipress-backend', DELIPRESS_PATH_PUBLIC_JS . '/backend.js' , array( 'jquery', 'delipress-vendor' ), DELIPRESS_VERSION );


            wp_add_inline_script( 'delipress-backend', "
                function delipressLoadBackend(){
                    require('javascripts/backend/Ux');
                    require('javascripts/backend/Actions');
                }

                window.addEventListener ?
                    window.addEventListener('load',delipressLoadBackend,false) :
                    window.attachEvent && window.attachEvent('onload', delipressLoadBackend);
            " );

            wp_enqueue_script( 'delipress-backend' );

            $translation = $this->getTranslationBuilder();
            wp_localize_script( 'delipress-backend', 'translationDelipressReact', $translation );
            wp_enqueue_script('delipress-backend');
        }

        // Only react
        if(
            $page === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS ) ) ||
            $page === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS ) )
        ){

            wp_enqueue_media();


            wp_register_script( 'delipress-react-js', DELIPRESS_PATH_PUBLIC_JS . '/react.js' , array( 'jquery', 'delipress-vendor', 'delipress-backend' ), DELIPRESS_VERSION );

            //  Compatibility NGINX / Apache
             if(isset($_SERVER["SERVER_SOFTWARE"]) && strpos($_SERVER["SERVER_SOFTWARE"], "Apache") !== false){
                wp_enqueue_script("delipress-tinymce", includes_url( 'js/tinymce/wp-tinymce.php' ) );
             }
             else{
                 $this->wpTinymcePhp();
             }

            add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

            // all actions related to emojis
            remove_action( 'admin_print_styles', 'print_emoji_styles' );
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
            remove_action( 'wp_print_styles', 'print_emoji_styles' );
            remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
            remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
            remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );


            $fontsSystem = array(
                "Arial",
                "Arial Black",
                "Tahoma",
                "Trebuchet MS",
                "Georgia",
                "Verdana",

            );

            $loadFontsGoogle = apply_filters(DELIPRESS_SLUG . "_load_google_fonts_builder",
                array(
                    "Ubuntu"
                )
            );

            foreach($loadFontsGoogle as $key => $value){
                 wp_enqueue_style("delipress-$value", "https://fonts.googleapis.com/css?family=$value:300,400,500,700");
            }

            $containerWidth = apply_filters(DELIPRESS_SLUG . "_container_width_email", 600);
            if(!is_int($containerWidth)){
                $containerWidth = 600;
            }
            else if($containerWidth < 500 || $containerWidth > 700){
                $containerWidth = 600;
            }

            $suffix = SCRIPT_DEBUG ? '' : '.min';
            $configReact = array(
                "locale"          => get_locale(),
                "container_width" => $containerWidth,
                "tinymce_plugins" => array(
                    "link" => DELIPRESS_PLUGIN_URL . "plugins_tinymce/link/plugin$suffix.js"
                ),
                "fonts"       => array_merge(
                    $fontsSystem,
                    $loadFontsGoogle
                ),
            );

            if(get_locale() != "en_US") {
                $configReact["tinymce_lang_url"] = DELIPRESS_PLUGIN_URL . "plugins_tinymce/langs/".get_locale().".js";
            }

            wp_localize_script( 'delipress-react-js', 'configDelipressReact', $configReact );


            if(isset($_GET['step']) && $_GET['page'] == 'delipress-campaigns' &&  $_GET['step'] == 3 || isset($_GET['step']) && $_GET['page'] == 'delipress-optin-forms' && $_GET['step'] == 2){

                add_filter( 'admin_body_class', array($this, 'delipressBuilderBodyClass') );

            }

            wp_enqueue_script('delipress-react-js');

        }

        // Only optin
        if($page === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS ) )){

            wp_enqueue_style( 'delipress-style', DELIPRESS_PATH_PUBLIC_CSS.'/optins.css' );

            wp_register_script( 'delipress-optin',  DELIPRESS_PATH_PUBLIC_JS.'/optins.js' , array('jquery', 'delipress-vendor') );
            wp_localize_script( 'delipress-optin', 'configDelipressRgpd', array(
                    'url_privacy_policy' => get_permalink( get_option('wp_page_for_privacy_policy') ),
                    'email_admin' => get_option('admin_email'),
                    'wp_version' => get_bloginfo('version'),
                    'admin_privacy' => admin_url('privacy.php'),
                )
            );
            wp_enqueue_script( 'delipress-optin' );

        }

    }

    /**
     * @see upload_mimes
     *
     * @param array $mimes
     * @return array
     */
    public  function mimesTypes( $mimes ) {

        if(!function_exists("get_current_screen")){
            return $mimes;
        }

        $screen = get_current_screen();

        if(!$screen){
            return $mimes;
        }

        if(!property_exists($screen, "id") || !$screen instanceof \WP_Screen){
            return $mimes;
        }

        if(
            $screen->id === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS ) ) ||
            $screen->id === sprintf("%s_page_%s", DELIPRESS_SLUG, PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS ) )
        ){
            $arr['jpg|jpeg|jpe'] = 'image/jpeg';
            $arr['gif']          = 'image/gif';
            $arr['png']          = 'image/png';
            $arr['bmp']          = 'image/bmp';
            $arr['tiff|tif']     = 'image/tiff';

            return $arr;
        }

        return $mimes;
    }

    /**
     * @param string $classes
     * @return string
     */
    public function delipressBuilderBodyClass( $classes ) {
        $classes .= ' delipress-is-builder';
        return $classes;
    }

    /**
     * @action DELIPRESS_SLUG . '_include_file_page'
     * @filter DELIPRESS_SLUG . '_before_include_file_page'
     *
     * @param string $file
     */
    protected function includeFilePage($file){

        $file = apply_filters(DELIPRESS_SLUG . '_include_file_page', $file);

        do_action(DELIPRESS_SLUG . '_before_include_file_page', $file);

        if(file_exists($file)){
            include_once($file);
        }
        else{
            wp_redirect(admin_url());
            die;
        }
    }

    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressSetupPage(){
        $this->currentStep     = PageAdminHelper::getCurrentStep();
        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->namePageInclude = PageAdminHelper::PAGE_SETUP;

        $this->includeFilePage($this->mainPage);
    }

    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressCampaignsPage(){
        $this->currentStep     = PageAdminHelper::getCurrentStep();
        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->namePageInclude = PageAdminHelper::PAGE_CAMPAIGNS;
        $this->campaign        = PrepareModelHelper::getCampaignFromUrl();

        $this->includeFilePage($this->mainPage);
    }

    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressListsPage(){
        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->namePageInclude = PageAdminHelper::PAGE_LISTS;
        $this->currentStep     = PageAdminHelper::getCurrentStep();
        $this->includeFilePage($this->mainPage);
    }


    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressOptinFormsPage() {
        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->currentStep     = PageAdminHelper::getCurrentStep();
        $this->namePageInclude = PageAdminHelper::PAGE_OPTIN_FORMS;
        $this->optin           = PrepareModelHelper::getOptinFromUrl();
        $this->includeFilePage($this->mainPage);
    }

    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressOptionsPage(){
        $this->tabs            = $this->pageAdminServices->getTabsByPage(PageAdminHelper::PAGE_OPTIONS);
        $this->currentTab      = PageAdminHelper::getCurrentTab();
        if(empty($this->currentTab)){
            $this->currentTab = "options";
        }

        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->options         = $this->optionServices->getOptions();
        $this->namePageInclude = PageAdminHelper::PAGE_OPTIONS;
        $this->includeFilePage($this->mainPage);
    }


    /**
     * Callback from getCallbackPage
     *
     * @return void
     */
    public function delipressDefaultPage() {
        $this->currentTab      = PageAdminHelper::getCurrentTab();
        $this->currentStep     = PageAdminHelper::getCurrentStep();
        $this->currentAction   = PageAdminHelper::getCurrentAction();
        $this->includeFilePage($this->mainPage);
    }

    public function disable_emojicons_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
            return array();
        }
    }
}
