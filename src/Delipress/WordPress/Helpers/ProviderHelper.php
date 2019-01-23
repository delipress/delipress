<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class ProviderHelper{

    const MAILJET         = "mailjet";
    const SENDGRID        = "sendgrid";
    const MAILCHIMP       = "mailchimp";
    const SENDINBLUE      = "sendinblue";

    protected static $providerUrl = null;

    protected static $providerPost = null;

    public static function getListProviders(){

        $providers = apply_filters(DELIPRESS_SLUG . "_list_providers",
            array(
                self::MAILCHIMP => array(
                   "label"        => "MailChimp",
                   "key"          => self::MAILCHIMP,
                   "hook"         => __('Free up to 2000 subscribers and 12000 emails per month.', "DeliPress"),
                   "description"  => __("MailChimp is an email marketing service founded in 2001 sending over 10 billion emails per month.", 'delipress'),
                   "img_src"      => DELIPRESS_PATH_PUBLIC_IMG . "/providers/mailchimp.png",
                   "active"       => true,
                   "sticky"       => true,
                   "url"          => "https://mailchimp.com/",
                   "url_pricing"  => "https://mailchimp.com/pricing/",
                   "url_register" => "https://login.mailchimp.com/signup/",
                   "url_api_key"  => "https://login.mailchimp.com/?referrer=%2Faccount%2Fapi%2F",
                   "url_senders"  => "https://login.mailchimp.com/"
               ),
                self::MAILJET => array(
                    "label"        => "Mailjet",
                    "key"          => self::MAILJET,
                    "description"  => __("Mailjet is a Paris-based, all-in-one Email Service Provider that allows businesses to send Marketing, Transactional Email and Email Automation.", 'delipress'),
                    "img_src"      => DELIPRESS_PATH_PUBLIC_IMG . "/providers/mailjet.png",
                    "active"       => true,
                    "sticky"       => false,
                    "url"          => "https://www.mailjet.com/?aff=delipress",
                    "url_pricing"  => "https://www.mailjet.com/pricing_v3?aff=delipress",
                    "url_register" => "https://www.mailjet.com/?aff=delipress",
                    "url_senders"  => "https://app.mailjet.com/account/sender"
                ),
                self::SENDINBLUE => array(
                    "label"        => "SendinBlue",
                    "key"          => self::SENDINBLUE,
                    "description"  => __('Join over 50000 companies on the most easy-to-use all-in-one email marketing, SMS, transactional email (SMTP), and marketing automation platform.', "delipress"),
                    "img_src"      => DELIPRESS_PATH_PUBLIC_IMG . "/providers/sendinblue.png",
                    "active"       => true,
                    "sticky"       => false,
                    "url"          => "https://sendinblue.com/",
                    "url_pricing"  => "https://sendinblue.com/pricing/",
                    "url_register" => "https://sendinblue.com/users/signup/",
                    "url_api_key"  => "https://account.sendinblue.com/advanced/api",
                    "url_senders"  => "https://account.sendinblue.com/senders"
                ),
                self::SENDGRID => array(
                    "label"        => "Sendgrid",
                    "key"          => self::SENDGRID,
                    "description"  => __("Delivering your transactional and marketing emails through the world's largest cloud-based email delivery platform. Send with confidence.", "delipress"),
                    "img_src"      => DELIPRESS_PATH_PUBLIC_IMG . "/providers/sendgrid.png",
                    "active"       => true,
                    "sticky"       => false,
                    "url"          => "https://sendgrid.com/",
                    "url_pricing"  => "https://sendgrid.com/pricing/",
                    "url_register" => "https://sendgrid.com/pricing/",
                    "url_api_key"  => "https://app.sendgrid.com/settings/api_keys",
                    "url_senders"  => "https://app.sendgrid.com/marketing_campaigns/ui/senders"
                )
            )
        );

        return self::array_sort($providers, 'active', SORT_DESC);
    }

    protected static function array_sort($array, $on, $order){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    /**
     * @param string $provider
     * @return array
     */
    public static function getProviderByKey($provider){
        $providers = self::getListProviders();
        if(
            !array_key_exists($provider, $providers )
        ){
            return null;
        }

        return $providers[$provider];
    }

    /**
     * @param string $provider
     * @return bool
     */
    protected static function verifyProvider($provider){

        $providers = self::getListProviders();
        if(
            !array_key_exists($provider, $providers )
        ){
            wp_redirect(admin_url());
            die;
        }

        return true;

    }

    /**
     * @return array
     */
    public static function getProviderFromUrl(){

        if(self::$providerUrl){
            return self::$providerUrl;
        }

        if(isset($_GET["provider"])){
            if(self::verifyProvider($_GET["provider"])){
                $providers = self::getListProviders();

                self::$providerUrl = $providers[$_GET["provider"]];
            }
        }

        return self::$providerUrl;
    }

    /**
     * @return array
     */
    public static function getProviderFromPostRequest(){
        if(self::$providerPost){
            return self::$providerPost;
        }

        if(isset($_POST["provider"])){
            if(self::verifyProvider($_POST["provider"])){
                $providers = self::getListProviders();
                self::$providerPost = $providers[$_POST["provider"]];
            }
        }

        return self::$providerPost;
    }
}
