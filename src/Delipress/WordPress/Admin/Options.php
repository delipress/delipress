<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ConnectorHelper;

/**
 * Options
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class Options extends AbstractHook implements HooksInterface, ActivationInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices       = $containerServices->getService("OptionServices");
        $this->providerServices     = $containerServices->getService("ProviderServices");
        $this->connectorServices    = $containerServices->getService("ConnectorServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_init', array( $this, 'optionsInit') );
            add_action( 'admin_post_' . ActionHelper::DISCONNECT_PROVIDER, array($this, 'disconnectProvider') );
        }
    }


    /**
     * @see admin_init
     * 
     * @return void
     */
    public function optionsInit(){
        register_setting( OptionHelper::OPTIONS_GROUP, OptionHelper::OPTIONS_NAME, array( $this, 'optionsValidate' ) );
    }

    /**
     * 
     * @return void
     */
    public function disconnectProvider(){
        if(
            !isset($_GET["_wpnonce"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DISCONNECT_PROVIDER )
        ){
            wp_nonce_ays( '' );
        }

        $this->optionServices->setOptions(
            array(
                "provider"      => array(
                    "is_connect" => false,
                    "key"        => "",
                )
            )
        );

        $url = $this->optionServices->getPageUrl();

        wp_redirect($url);
        exit;

    }

    /**
     *
     * @param array $options
     * @return array
     */
    protected function optionsValidateConnectors($options){
        $connectors      = ConnectorHelper::getConnectors();
        $optionsExist    = $this->optionServices->getOptions();

        foreach($connectors as $key => $connector){
            if(!$options || !isset($options["connectors"][$connector["key"]]) ){
                $options["connectors"][$connector["key"]]["active"] = false;
            }
            else{
                $options["connectors"][$connector["key"]]["active"] = true;
            }

            if(
                array_key_exists($connector["key"], $optionsExist["connectors"]) &&
                $optionsExist["connectors"][$connector["key"]]["active"] && 
                $options["connectors"][$connector["key"]]["active"]
            ){
                $options["connectors"][$connector["key"]]["list_id"] = $optionsExist["connectors"][$connector["key"]]["list_id"];
                continue;
            }
            
            $connectorServices = $this->connectorServices->getConnector($connector["key"]);
            
            if($options["connectors"][$connector["key"]]["active"]){
                $result = $connectorServices->prepareConnector();
                if($result["success"]){
                    $options["connectors"][$connector["key"]]["list_id"] = $result["results"]["list"]->getId();
                }
            }
            else{
                if(isset($optionsExist["connectors"][$connector["key"]]["list_id"])){
                    $connectorServices->removeConnector($optionsExist["connectors"][$connector["key"]]["list_id"]);
                }
            }
        }
        
        AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));

        return $options;
        
    }


    /**
     * Callback optionsInit
     *
     * @param array $options
     * @return array
     */
    public function optionsValidate($options){

        $optionsExist    = $this->optionServices->getOptions();
        $currentTab      = PageAdminHelper::getCurrentTab();
        $currentAction   = PageAdminHelper::getCurrentAction();
        $msgSuccess      = "";

        if($currentAction === ActionHelper::SETTINGS ){
            if(empty($currentTab) ){
                $currentTab = "options";
            }

            switch ($currentTab) {
                case 'options':
                    $options[$currentTab] = $this->prepareOptionsSettings($options[$currentTab]);
                    break;
                case 'others':
                    if(!$options || !isset($options[$currentTab]["show_setup"])){
                        $options[$currentTab]["show_setup"] = false;
                    }
                    else{
                        $options[$currentTab]["show_setup"] = true;
                    }
                    AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));
                    break;
                case 'subscribers':
                    if(!$options || !isset($options[$currentTab]["double_optin"]) ){
                        $options[$currentTab]["double_optin"] = false;
                    }
                    else{
                        $options[$currentTab]["double_optin"] = true;
                    }

                    AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));
                    break;
                case 'connectors':
                    $options = $this->optionsValidateConnectors($options);
                    break;
            }

            $optionsExist[$currentTab] = wp_parse_args($options[$currentTab], $optionsExist[$currentTab]);
        }
        else if($currentAction === ActionHelper::SETTINGS_PROVIDER){
            $provider = ProviderHelper::getProviderFromPostRequest();
            
            if(!array_key_exists("key", $provider)) {
                AdminNoticesHelper::registerError(
                    CodeErrorHelper::ADMIN_NOTICE,
                    CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
                );
            }
            else{

                $options["provider"] = $this->providerServices->prepareProvidersSettings(
                    $provider["key"],
                    $options["provider"]
                );

                $optionsExist["provider"] = wp_parse_args(
                    $options["provider"],
                    $optionsExist["provider"]
                );

                $optionsExist["provider"]["key"] = $provider["key"];

                if($optionsExist["provider"]["is_connect"]){
                    $optionsUserInfo = $this->providerServices
                                            ->getProviderSettings($provider["key"])
                                            ->createClientApi($optionsExist["provider"])
                                            ->setSafeError(true)
                                            ->getUserInfo();

                    if(!empty($optionsUserInfo)){
                        $optionsExist["options"] = wp_parse_args(
                            $optionsUserInfo["options"],
                            $optionsExist["options"]
                        );
                    }

                    AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));

                }

            }
        }
        else{
            $optionsExist = wp_parse_args($options, $optionsExist);
            
            AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));
        }

        return $optionsExist;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function prepareOptionsSettings($options){
        $errors = 0;

        if(!empty($options["from_to"]) && !is_email($options["from_to"]) ) {
            ErrorFieldsNoticesHelper::registerError(CodeErrorHelper::OPTIONS_FROM_TO, __("From field is not an email", "delipress"));
            $options["from_to"] = "";
            $errors++;
        }
        else{
            $options["from_to"] = sanitize_email($options["from_to"]);
        }

        if(!empty($options["reply_to"]) && !is_email($options["reply_to"]) ) {
            ErrorFieldsNoticesHelper::registerError(CodeErrorHelper::OPTIONS_REPLY_TO, __("Reply to field is not an email", "delipress"));
            $options["reply_to"] = "";
            $errors++;
        }
        else{
            $options["reply_to"] = sanitize_email($options["reply_to"]);
        }
        
        if(empty($options["from_name"])){
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::OPTIONS_FROM_NAME
                , __("From name field is empty", "delipress")
            );
            $errors++;
        }
        else if(!empty($options["from_name"]) ) {
            $options["from_name"] = sanitize_text_field($options["from_name"]);
        }

        if(!empty($options["license_key"])){

            $response                  = $this->verifyLicenseKey($options["license_key"]);
            $options["license_status"] = $response["results"];

            if(!$response["success"]){
                $errors++;
            }
        }
        else{
            $options["license_status"] = array(
                "status"  => "empty",
                "message" => esc_html__("No license", "delipress")
            );
        }

        if($errors === 0){
            AdminNoticesHelper::registerSuccess(CodeErrorHelper::ADMIN_NOTICE, __("Settings saved","delipress"));
        }
        else{
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE, 
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );
        }

        return $options;
    }

    /**
     *
     * @param string $license
     * @return array
     */
    protected function verifyLicenseKey($license){

		$result = $this->optionServices->activateLicenseKey($license);

        if(!$result["success"]){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::OPTIONS_LICENSE_KEY,
                $result["results"]["message"]
            );

        }

        return $result;

    }


    /**
     * @see ActivationInterface
     */
    public function activation(){

        $this->optionServices->setOptions(
            array(
                "version"      => DELIPRESS_VERSION,
                "date_install" => new \DateTime( current_time("mysql") )
            )
        );
    }

}
