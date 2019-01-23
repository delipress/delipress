<?php

namespace Delipress\WordPress\Services\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CampaignMetaHelper;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Listing\ListTrait;
use Delipress\WordPress\Traits\Provider\ProviderTrait;
use Delipress\WordPress\Traits\Campaign\CampaignTrait;

use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\TemplateLibraryHelper;


/**
 * CreateCampaignServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateCampaignServices implements ServiceInterface, MediatorServicesInterface {

    use PrepareParams;
    use ListTrait;
    use ProviderTrait;
    use CampaignTrait;

    protected $missingParameters = array();

    protected $paramsNotValid    = 0;

    protected $fieldsPosts = array(
        "step_1" => array(
            PostTypeHelper::CAMPAIGN_NAME         => "sanitize_text_field",
            PostTypeHelper::CAMPAIGN_TAXO_LISTS   => ""
        ),
    );

    protected $fieldsPostMetas = array(
        "step_1" => array(
            PostTypeHelper::META_CAMPAIGN_SUBJECT          => "stripslashes",
            PostTypeHelper::META_CAMPAIGN_SEND             => "checkMetaCampaignSend",
            PostTypeHelper::META_CAMPAIGN_DATE_SEND        => "checkDateSend",
        )
    );

    protected $fieldsRequired = array(
        "step_1" => array(
            PostTypeHelper::CAMPAIGN_NAME,
        )
    );

    protected $fieldsMetasRequired = array(
        "step_1" => array(
            PostTypeHelper::META_CAMPAIGN_SUBJECT,
            PostTypeHelper::META_CAMPAIGN_SEND
        )
    );

    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->campaignServices        = $services["CampaignServices"];
        $this->optionServices          = $services["OptionServices"];
        $this->providerServices        = $services["ProviderServices"];
        $this->synchronizeListServices = $services["SynchronizeListServices"];

    }


    /**
     * @return void
     */
    protected function prepareMissingParameters(){
        foreach($this->missingParameters as $key => $parameter){
            switch($key){
                case PostTypeHelper::CAMPAIGN_NAME:

                    ErrorFieldsNoticesHelper::registerError(
                        CodeErrorHelper::MISSING_CAMPAIGN_NAME,
                        CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_CAMPAIGN_NAME)
                    );
                    break;
                case PostTypeHelper::CAMPAIGN_TAXO_LISTS:
                    ErrorFieldsNoticesHelper::registerError(
                        CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS,
                        CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS)
                    );
                    break;
            }
        }
    }

    /**
     *
     * @param array $params
     * @param array $metas
     * @return void
     */
    protected function verifyParameters($params, $metas){

        if(empty($params[PostTypeHelper::CAMPAIGN_NAME])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::NOT_EMPTY_CAMPAIGN_NAME,
                CodeErrorHelper::getMessage(CodeErrorHelper::NOT_EMPTY_CAMPAIGN_NAME)
            );
        }

        if(empty($metas[PostTypeHelper::META_CAMPAIGN_SUBJECT])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SUBJECT,
                CodeErrorHelper::getMessage(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SUBJECT)
            );
        }

        if(empty($metas[PostTypeHelper::META_CAMPAIGN_SEND])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SEND,
                CodeErrorHelper::getMessage(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SEND)
            );
        }
        else{
            if($metas[PostTypeHelper::META_CAMPAIGN_SEND] === "later" && empty($metas[PostTypeHelper::META_CAMPAIGN_DATE_SEND])){
                $this->paramsNotValid++;
                ErrorFieldsNoticesHelper::registerError(
                    CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_DATE_SEND,
                    CodeErrorHelper::getMessage(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_DATE_SEND)
                );
            }
        }

    }

    /**
     *
     * @action DELIPRESS_SLUG . "_before_create_campaign_step_one"
     * @action DELIPRESS_SLUG . "_after_create_campaign_step_one"
     *
     *
     * @param integer|null $campaignId
     * @return array
     */
    public function createCampaignStepOne($campaignId = null){

        $params = $this->getPostParams("fields", "step_1");
        $metas  = $this->getPostParams("meta", "step_1");

        do_action(DELIPRESS_SLUG . "_before_create_campaign_step_one", $campaignId, $params, $metas);

        $provider          = $this->optionServices->getProvider();
        $providerMD5       = $this->optionServices->getMd5Provider();
        $metas["provider"] = $provider;

        $this->prepareMissingParameters();
        $this->verifyParameters($params, $metas);

        if(
            !empty($this->missingParameters) ||
            $this->paramsNotValid > 0
        ){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false,
                "results" => array(
                    "campaign_id" => $campaignId,
                )
            );
        }

        $postId = null;

        $metasArgsInsert = array();

        foreach($metas as $key => $value){
            if(in_array($key, array(
                PostTypeHelper::META_CAMPAIGN_SUBJECT,
                PostTypeHelper::META_CAMPAIGN_SEND,
                PostTypeHelper::META_CAMPAIGN_DATE_SEND,
            ))){
                $metasArgsInsert[$key] = $value;
            }
        }

        $metasArgsInsert[PostTypeHelper::CAMPAIGN_TAXO_LISTS]             = (isset($params[PostTypeHelper::CAMPAIGN_TAXO_LISTS])) ? $params[PostTypeHelper::CAMPAIGN_TAXO_LISTS] : "";
        $metasArgsInsert[PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER] = $provider["key"];

        if( !$campaignId ){

            $metasArgsInsert[PostTypeHelper::META_CAMPAIGN_TOKEN_ONLINE]    = substr(uniqid("tk"), 0,20);
            $metasArgsInsert[PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG] = "";
            $metasArgsInsert[PostTypeHelper::META_CAMPAIGN_IS_SEND]         = 0;

            $args = array(
                'post_title'    => wp_strip_all_tags( $params[PostTypeHelper::CAMPAIGN_NAME] ),
                'post_content'  => "",
                'post_status'   => 'draft',
                'post_type'     => PostTypeHelper::CPT_CAMPAIGN,
                'meta_input'    => $metasArgsInsert,
            );

            $postId = wp_insert_post($args);

        }
        else{
            $args = array(
                'ID'            => $campaignId,
                'post_title'    => wp_strip_all_tags( $params[PostTypeHelper::CAMPAIGN_NAME] ),
            );

            $postId =  wp_update_post($args);

            foreach($metasArgsInsert as $key => $meta){
                update_post_meta($postId, $key, $meta);
            }

        }

        if(is_wp_error( $postId ) ){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false,
                "results" => $postId->get_error_messages()
            );
        }

        do_action(DELIPRESS_SLUG . "_after_create_campaign_step_one", $postId);

        AdminFormValues::cleanFormValues();

        return array(
            "success" => true,
            "results" => array(
                "campaign_id" => $postId,
            )
        );
    }

    /**
     *
     * @return array
     */
    public function getTemplateFromScratch(){

        $provider   = $this->optionServices->getProvider();

        $configTemplate = array(
            "theme" => array(
                "mj-attributes" => array(
                    "mj-all" => array(),
                    "mj-text" => array(
                        "color" => array(
                            "hex" => "#23282C",
                            "rgb" => array(
                                "r" => 35,
                                "g" => 40,
                                "b" => 44,
                                "a" => 1,
                            )
                        ),
                    ),
                    "mj-container" => array(
                        "background-color" => array(
                            "hex" => "#E0E0E0",
                            "rgb" => array(
                                "r" => 224,
                                "g" => 224,
                                "b" => 224,
                                "a" => 1,
                            )
                        )
                    )
                ),
                "mj-styles" => array(
                    "link-color" => array(
                        "hex" => "#54C4F7",
                        "rgb" => array(
                            "r" => 84,
                            "g" => 196,
                            "b" => 247,
                            "a" => 1,
                        )
                    )
                ),
            ),
            "items"  => array(),
            "email_online"        => $this->createEmailOnline(),
            "unsubscribe"         => $this->createUnsubscribe($provider),
            "email_online_active" => true
        );

        return apply_filters(DELIPRESS_SLUG . "_config_template_default_campaign", $configTemplate);
    }

    /**
     *
     * @action DELIPRESS_SLUG . "_before_create_campaign_step_two"
     * @action DELIPRESS_SLUG . "_after_create_campaign_step_two"
     *
     * @filter DELIPRESS_SLUG . "_config_template_default_campaign"
     * @param integer $campaignId
     * @param string $from
     * @return array
     */
    public function createCampaignStepTwo($campaignId, $from){

        switch($from){
            case "template":
                if(!isset($_GET["id_template"])){
                    return array(
                        "success" => false
                    );
                }
                $idTemplate     = (int) $_GET["id_template"];
                $metaTemplate   = PostTypeHelper::META_TEMPLATE_CONFIG;
                $meta           = PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG;

                global $wpdb;

                $sql = "UPDATE {$wpdb->prefix}postmeta p, (
                    SELECT B.meta_value
                    FROM {$wpdb->prefix}postmeta as A
                    INNER JOIN {$wpdb->prefix}postmeta B ON A.meta_id = B.meta_id
                    WHERE B.meta_key = %s
                    AND B.post_id = {$idTemplate}
                ) as result
                SET p.meta_value = result.meta_value
                WHERE p.post_id = {$campaignId}
                AND p.meta_key = %s";

                $wpdb->query(
                    $wpdb->prepare(
                        $sql,
                        $metaTemplate,
                        $meta
                    )
                );
                break;
            case "library":

                if(!isset($_GET["library_template"])){
                    return array(
                        "success" => false
                    );
                }
                $keyTemplate = (int) $_GET["library_template"];
                $template    = TemplateLibraryHelper::getTemplateByKey($keyTemplate);
                if(!$template){
                    $configTemplate = $this->getTemplateFromScratch();
                }
                else{
                    include_once $template["file"];
                    $configTemplate = $args;
                }

                update_post_meta($campaignId, PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG, json_encode( $args, JSON_UNESCAPED_UNICODE ) );

                break;
            case "scratch":
                $configTemplate = $this->getTemplateFromScratch();
                update_post_meta($campaignId, PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG, json_encode( $configTemplate, JSON_UNESCAPED_UNICODE ) );
                break;
            case "recent":
                if(!isset($_GET["recent_campaign_id"])){
                    return array(
                        "success" => false
                    );
                }

                $recentCampaignId = (int) $_GET["recent_campaign_id"];
                $meta             = PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG;

                global $wpdb;

                $sql = "UPDATE {$wpdb->prefix}postmeta p, (
                    SELECT B.meta_value
                    FROM {$wpdb->prefix}postmeta as A
                    INNER JOIN {$wpdb->prefix}postmeta B ON A.meta_id = B.meta_id
                    WHERE B.meta_key = %s
                    AND B.post_id = {$recentCampaignId}
                ) as result
                SET p.meta_value = result.meta_value
                WHERE p.post_id = {$campaignId}
                AND p.meta_key = %s";

                $wpdb->query(
                    $wpdb->prepare(
                        $sql,
                        $meta,
                        $meta
                    )
                );

                break;
        }


        return array(
            "success" => true
        );

    }

    /**
     *
     * @param string $provider
     * @return array
     */
    public function createEmailOnline($styles = array()){

        $viewOnline = CampaignMetaHelper::VIEW_CAMPAIGN_ONLINE;

        $styleDefault = apply_filters(DELIPRESS_SLUG . "_style_default_item_email_online",
            array(
                "font-size"      =>  12,
                "color"          =>  array(
                    "hex" => "#000000",
                    "rgb" => array(
                        "r" => 0,
                        "g" => 0,
                        "b" => 0,
                        "a" => 1,
                    )
                ),
                "font-family"    =>  "Arial",
                "align"          =>  "right",
                "padding-top"    =>  15,
                "padding-bottom" =>  15,
                "padding-left"   =>  10,
                "padding-right"  =>  10,
                "line-height"    => 1.3
            )
        );

        if(isset($styles["item"])){
            $style = array_merge($styleDefault, $styles["item"]);
        }
        else{
            $style = $styleDefault;
        }

        $args = array(
            array(
                "columns" => array(
                    array(
                        "items" => array(
                            array(
                                "_id"       => 0,
                                "keyRow"    => "email_online",
                                "keyColumn" => 0,
                                "value"     => "<p><a href='" . $viewOnline . "'>" . __("View in browser", "delipress") . "</a></p>",
                                "type"      => 7,
                                "styles"    => $style
                            )
                        ),
                        "styles" => array(
                            "width" => 100
                        )
                    )
                ),
                "styles" => array(
                    "background" => array(
                        "hex" => "#F0F0F0",
                        "rgb" => array(
                            "r" => 224,
                            "g" => 224,
                            "b" => 224,
                            "a" => 1,
                        )
                    ),
                    "padding-top"    => 0,
                    "padding-bottom" => 0,
                    "padding-right"  => 0,
                    "padding-left"   => 0,
                )
            )
        );

        return $args;
    }

    /**
     *
     * @param string $provider
     * @return array
     */
    public function createUnsubscribe($provider, $styles = array()){
        $styleDefault = array(
            "font-size"      =>  11,
            "color"          =>  array(
                "hex" => "#CCC",
                "rgb" => array(
                    "r" => 150,
                    "g" => 150,
                    "b" => 150,
                    "a" => 1,
                )
            ),
            "font-family"    =>  "Arial",
            "align"          =>  "center",
            "padding-top"    =>  15,
            "padding-bottom" =>  15,
            "padding-left"   =>  0,
            "padding-right"  =>  0,
            "line-height"    => 1.3
        );

        if(isset($styles["item"])){
            $style = array_merge($styleDefault, $styles["item"]);
        }
        else{
            $style = $styleDefault;
        }

        $tpl = array(
            array(
                "columns" => array(
                    array(
                        "items"  => array(),
                        "styles" => array(
                            "width"          => 100,
                            array(
                                "hex" => "#FFFFFF",
                                "rgb" => array(
                                    "r" => 255,
                                    "g" => 255,
                                    "b" => 255,
                                    "a" => 0,
                                )
                            ),
                            "padding-top"    =>  0,
                            "padding-bottom" =>  0,
                            "padding-left"   =>  0,
                            "padding-right"  =>  0,
                        )
                    )
                ),
                "styles" => array(
                    array(
                        "hex" => "#FFFFFF",
                        "rgb" => array(
                            "r" => 255,
                            "g" => 255,
                            "b" => 255,
                            "a" => 0,
                        )
                    ),
                    "padding-top"    => 0,
                    "padding-bottom" => 0,
                    "padding-right"  => 0,
                    "padding-left"   => 0,
                )
            )
        );

        switch($provider["key"]){
            case ProviderHelper::MAILCHIMP:
                $tpl[0]["columns"][0]["items"][] = array(
                    "_id"       => 0,
                    "keyRow"    => "unsubscribe",
                    "keyColumn" => 0,
                    "value"     => __("<p><a href='*|UNSUB|*'>Unsubscribe</a> from this list.</p><p> <a href='*|LIST:ADDRESS_VCARD_HREF|*'>Add us</a> to your address book</p><p>*|IF:REWARDS|* *|HTML:REWARDS|* *|END:IF|*</p>","delipress"),
                    "type"      => 8,
                    "styles" => $style
                );
                break;
            default:
                $linkUnsubscribe = CampaignMetaHelper::LINK_UNSUBSCRIBE;
                $tpl[0]["columns"][0]["items"][] = array(
                    "_id"       => 0,
                    "keyRow"    => "unsubscribe",
                    "keyColumn" => 0,
                    "value"     => "<p><a href='" . $linkUnsubscribe . "'> " . __("Unsubscribe from the newsletter", "delipress") . "</a></p>",
                    "type"      => 8,
                    "styles" => $style
                );
                break;
        }


        return $tpl;

    }

}
