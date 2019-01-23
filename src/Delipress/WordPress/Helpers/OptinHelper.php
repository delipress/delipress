<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class OptinHelper{

    const POPUP                  = "popup_optin";
    const SMARTBAR               = "smartbar_optin";
    const FLY                    = "fly_optin";
    const WIDGET                 = "widget_optin";
    const AFTER_CONTENT          = "after_content_optin";
    const LOCKED                 = "locked_optin";
    const SHORTCODE              = "shortcode_optin";
    const WOOCOMMERCE_ORDER      = "woocommerce_order_optin";
    const GRAVITY_FORM           = "gravity_form_optin";
    const CONTACT_FORM_7         = "contact_form_7";

    const COUNTER_VIEW           = "counter_view";
    const COUNTER_CONVERT        = "counter_convert";

    protected static $loaded = false;


    public static function loadOptinScript($load){

        if(!function_exists("is_plugin_active")){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        if(is_plugin_active("elementor/elementor.php")){
            if(is_admin()){
                return;
            }
        }

        self::$loaded = true;

        $loadAfterAdmin = false;
        if ( current_theme_supports( 'admin-bar' ) && is_user_logged_in()) {
            $loadAfterAdmin = 'admin-bar';
        }

        wp_enqueue_style( 'delipress-style', DELIPRESS_PATH_PUBLIC_CSS.'/optins.css' );

        wp_register_script( 'delipress-optin',  DELIPRESS_PATH_PUBLIC_JS.'/optins.js' , $loadAfterAdmin, false, true );
        wp_enqueue_script( 'delipress-optin' );

        // wp_add_inline_script( 'delipress-optin', "
        //     function delipressLoadOptin(){
        //         require('javascripts/optins/BaseOptin');
        //     }

        //     window.addEventListener ?
        //         window.addEventListener('load',delipressLoadOptin,false) :
        //         window.attachEvent && window.attachEvent('onload', delipressLoadOptin);
        // " );

        wp_localize_script('delipress-optin', 'translationDelipressReact', array(
            'text_link_rgpd' =>  __('View Privacy Policy', 'delipress')
        ));

        wp_localize_script('delipress-optin', 'DelipressGRPD', array(
            'privacy_page_url' =>  get_permalink( get_option('wp_page_for_privacy_policy') )
        ));

        wp_localize_script('delipress-optin', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    }

    public static function isOptinScriptLoaded(){
        return self::$loaded;
    }


    /**
     * @filter DELIPRESS_SLUG . "_list_providers"
     *
     * @return void
     */
    public static function getListOptins(){

        return apply_filters(DELIPRESS_SLUG . "_list_providers",
            array(
                self::SHORTCODE   => array(
                    "label"        => __("Shortcode", 'delipress'),
                    "key"          => self::SHORTCODE,
                    "description"  => __('Place it wherever you want (for developers).', 'delipress'),
                    "is_active"    => true,
                    "is_premium"   => false,
                    "full_premium" => false,
                    "has_behavior" => false,
                    "third_party"  => false,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => false
                    )
                ),
                self::WIDGET      => array(
                    "label"        => __("Widget", 'delipress'),
                    "key"          => self::WIDGET,
                    "description"  => __('Define an Opt-In form directly in a widget area of your choice.', 'delipress'),
                    "is_active"    => true,
                    "is_premium"   => false,
                    "full_premium" => false,
                    "third_party"  => false,
                    "has_behavior" => false,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => false
                    )
                ),
                self::POPUP       => array(
                    "label"        => __("Popup",'delipress'),
                    "key"          => self::POPUP,
                    "description"  => __('Allows you to display beautifully designed popups at the right time. <strong>You</strong> decide when they appear — delay, scroll, click...', 'delipress'),
                    "is_active"    => true,
                    "third_party"  => false,
                    "is_premium"   => true,
                    "full_premium" => false,
                    "has_behavior" => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => true
                    ),
                    "form_post_authorize" => array(
                        "number_days_session"        => "intval",
                        "range_session"              => "sanitize_text_field",
                        "visibility_subscribers"     => "boolval",
                        "auto_close_after_subscribe" => "boolval",
                        "hide_on_mobile"             => "boolval",
                        "trigger_after_time_delay"   => "boolval",
                        "after_time_delay"           => "intval",
                        "trigger_after_scrolling"    => "boolval",
                        "after_scrolling_percent"    => "intval",
                        "exit_intent"                => "boolval",
                        "display_pages"              => "checkDisplayPages",
                        "display_languages"          => "sanitize_text_field"
                    )
                ),
                self::AFTER_CONTENT  => array(
                    "label"        => __("After content", 'delipress'),
                    "key"          => self::AFTER_CONTENT,
                    "description"  => __('Appears after pages and/or posts content.', 'delipress'),
                    "is_active"    => true,
                    "is_premium"   => true,
                    "full_premium" => false,
                    "third_party"  => false,
                    "has_behavior" => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => true
                    ),
                    "form_post_authorize" => array(
                        "number_days_session"        => "intval",
                        "range_session"              => "sanitize_text_field",
                        "visibility_subscribers"     => "boolval",
                        "auto_close_after_subscribe" => "boolval",
                        "hide_on_mobile"             => "boolval",
                        "display_pages"              => "checkDisplayPages",
                        "display_languages"          => "sanitize_text_field"
                    )
                ),
                self::FLY         => array(
                    "label"        => __("Fly in",'delipress'),
                    "key"          => self::FLY,
                    "description"  => __('A box in the corner that appears after a while or when user scrolls.', 'delipress'),
                    "is_premium"   => true,
                    "full_premium" => false,
                    "is_active"    => true,
                    "third_party"  => false,
                    "has_behavior" => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => true
                    ),
                    "form_post_authorize" => array(
                        "number_days_session"        => "intval",
                        "range_session"              => "sanitize_text_field",
                        "visibility_subscribers"     => "boolval",
                        "auto_close_after_subscribe" => "boolval",
                        "hide_on_mobile"             => "boolval",
                        "trigger_after_time_delay"   => "boolval",
                        "after_time_delay"           => "intval",
                        "trigger_after_scrolling"    => "boolval",
                        "after_scrolling_percent"    => "intval",
                        "exit_intent"                => "boolval",
                        "display_pages"              => "checkDisplayPages",
                        "display_languages"          => "sanitize_text_field"
                    )
                ),
                self::SMARTBAR    => array(
                    "label"        => __("Smart Bar",'delipress'),
                    "key"          => self::SMARTBAR,
                    "description"  => __('A fixed bar at the top or bottom of the screen.', 'delipress'),
                    "is_active"    => false,
                    "is_premium"   => true,
                    "full_premium" => false,
                    "third_party"  => false,
                    "has_behavior" => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => true
                    )
                ),
                self::LOCKED      => array(
                    "label"        => __("Locked content", 'delipress'),
                    "key"          => self::LOCKED,
                    "description"  => __('The user must give his email to access a content.', 'delipress'),
                    "is_active"    => false,
                    "is_premium"   => true,
                    "full_premium" => false,
                    "third_party"  => false,
                    "has_behavior" => false,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => true,
                        "step3" => true
                    )
                ),
                self::CONTACT_FORM_7   => array(
                    "label"        => __("Contact Form 7", 'delipress'),
                    "key"          => self::CONTACT_FORM_7,
                    "description"  => __("Add a subscribe checkbox to any Contact Form 7 form!", 'delipress'),
                    "is_active"    => true,
                    "is_premium"   => true,
                    "full_premium" => false,
                    "has_behavior" => false,
                    "third_party"  => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => false,
                        "step3" => false
                    )
                ),
                self::GRAVITY_FORM  => array(
                    "label"        => __("Gravity Forms", 'delipress'),
                    "key"          => self::GRAVITY_FORM,
                    "description"  => __("Add a subscribe checkbox to any Gravity Form form!", 'delipress'),
                    "is_active"    => false,
                    "is_premium"   => true,
                    "full_premium" => true,
                    "has_behavior" => false,
                    "third_party"  => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => false,
                        "step3" => false
                    )
                ),
                self::WOOCOMMERCE_ORDER   => array(
                    "label"        => __("WooCommerce", 'delipress'),
                    "key"          => self::WOOCOMMERCE_ORDER,
                    "description"  => __('Add a subscribe checkbox on WooCommerce checkout page', 'delipress'),
                    "is_active"    => false,
                    "is_premium"   => true,
                    "full_premium" => true,
                    "has_behavior" => false,
                    "third_party"  => true,
                    "url_how_work" => "",
                    "steps"        => array(
                        "step1" => true,
                        "step2" => false,
                        "step3" => false
                    )
                ),
            )
        );

    }

    /**
     *
     * @param string $optin
     * @return array
     */
    public static function getFormPostAuthorize($optin){
        $optin = self::getOptinByKey($optin);

        if(!$optin){
            return null;
        }

        if(!isset($optin["form_post_authorize"])){
            return array();
        }

        return $optin["form_post_authorize"];
    }

    /**
     * @param string $optin
     * @return array
     */
    public static function getOptinByKey($optin){
        $optins = self::getListOptins();
        if(
            !array_key_exists($optin, $optins )
        ){
            return null;
        }

        return $optins[$optin];
    }

    /**
     * @param string $optin
     * @return bool
     */
    protected static function verifyOptin($optin){

        $optins = self::getListOptins();
        if(
            !array_key_exists($optin, $optins )
        ){
            wp_redirect(admin_url());
            die;
        }

        return true;

    }


}
