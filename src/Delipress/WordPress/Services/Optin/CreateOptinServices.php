<?php

namespace Delipress\WordPress\Services\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\AdminNoticesHelper;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Listing\ListTrait;
use Delipress\WordPress\Traits\Optin\OptinTrait;

/**
 * CreateOptinServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateOptinServices implements ServiceInterface {

    use PrepareParams;
    use ListTrait;
    use OptinTrait;

    protected $paramsNotValid = 0;

    protected $missingParameters = array();

    protected $fieldsPosts = array(
        "step_1" => array(
            PostTypeHelper::OPTIN_NAME           => "sanitize_text_field",
            PostTypeHelper::OPTIN_TAXO_LISTS     => "",
        ),
        "step_3" => array()
    );

    protected $fieldsPostMetas = array(
        "step_1" => array(
            PostTypeHelper::META_OPTIN_IS_ACTIVE => "sanitize_text_field",
            PostTypeHelper::META_OPTIN_TYPE      => "checkOptinType"
        )
    );

    protected $fieldsRequired = array(
        "step_1" => array(
            PostTypeHelper::OPTIN_NAME,
            PostTypeHelper::OPTIN_TAXO_LISTS
        ),
        "step_3" => array()
    );

    protected $fieldsMetasRequired = array(
        "step_1" => array(
            PostTypeHelper::META_OPTIN_TYPE
        ),
        "step_3" => array()
    );

    /**
     * @return void
     */
    protected function prepareMissingParameters(){
        foreach($this->missingParameters as $key => $parameter){
            switch($key){
                case PostTypeHelper::OPTIN_NAME:
                    ErrorFieldsNoticesHelper::registerError(
                        CodeErrorHelper::MISSING_OPTIN_NAME,
                        CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_OPTIN_NAME)
                    );
                    break;
                case PostTypeHelper::OPTIN_TAXO_LISTS:
                case "lists_not_empty":
                    ErrorFieldsNoticesHelper::registerError(
                        CodeErrorHelper::MISSING_OPTIN_TAXO_LISTS,
                        CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_OPTIN_TAXO_LISTS)
                    );
                    break;
                case PostTypeHelper::META_OPTIN_IS_ACTIVE:
                    ErrorFieldsNoticesHelper::registerError(
                        CodeErrorHelper::MISSING_OPTIN_IS_ACTIVE,
                        CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_OPTIN_IS_ACTIVE)
                    );
                    break;
            }
        }
    }

    /**
     *
     * @param array $params
     * @return void
     */
    public function verifyParameters($params){

        if(empty($params[PostTypeHelper::OPTIN_NAME])){
            $this->paramsNotValid++;
            ErrorFieldsNoticesHelper::registerError(
                CodeErrorHelper::MISSING_OPTIN_NAME,
                CodeErrorHelper::getMessage(CodeErrorHelper::MISSING_OPTIN_NAME)
            );
        }

        return;
    }

    /**
     * @filter DELIPRESS_SLUG. "_config_default_optin"
     *
     * @return array
     */
    public function getConfigOptin(){
        $configOptin = array(
            "custom_css" => ".DELI-wrapper { }",
            "default_settings" => array(
                "form_wrapper" => array(
                    "attrs" => array(
                        "naked" => false,
                        "redirect_url" => "",
                        "fields_enable" => true,
                        "metas" => "empty",
                        "firstname_placeholder" => esc_html__('First name', 'delipress'),
                        "lastname_placeholder"  => esc_html__('Last name',  'delipress'),
                        "name_placeholder"      => esc_html__('Name',      'delipress'),
                        "email_placeholder"     => esc_html__('Email',     'delipress'),
                        "form_size" => "default"
                    ),
                    "styling" => array(
                        "backgroundColor" => array(
                            "hex" => '#FFFFFF',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 0,
                            ),
                        )
                    ),
                ),
                "wrapper" => array(
                    "attrs" => array(
                        "orientation"  => "",
                        "animation"  => "none",
                        "maxWidth"     => 600,
                        "fontFamily" => 'WordPress font',
                        "borderRadius" => 0,
                        "backgroundColor" => array(
                            "hex" => '#54C4F7',
                            "rgb" => array(
                                "r" => 84,
                                "g" => 196,
                                "b" => 247,
                                "a" => 1,
                            )
                        ),
                        "borderStyle" => "solid",
                        "borderWidth" => 0,
                        "borderColor" => array(
                            "hex" => '#FFFFFF',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 1,
                            )
                        ),
                    ),
                    "styling" => array(
                        "fontFamily"      => '-apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif',
                        "backgroundColor" => array(
                            "hex" => '#54C4F7',
                            "rgb" => array(
                                "r" => 84,
                                "g" => 196,
                                "b" => 247,
                                "a" => 1,
                            )
                        ),
                        "borderStyle" => "solid",
                        "borderWidth" => "0px",
                        "maxWidth"     => "600px",
                        "borderRadius" => "0px",
                        "color"           => array(
                            "hex" => '#fff',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 1,
                            )
                        )
                    )
                ),
                "wrapper_image" => array(
                    "attrs" => array(
                        "active"     => true,
                        "width"      => 300,
                        "height"     => 300,
                        "range"      => 30,
                        "url"        => DELIPRESS_PATH_PUBLIC_IMG . "/opt-in/presets/letter.svg",
                        "sizeSelect" => "full"
                    ),
                    "styling" => array(
                        "width" => "60%"
                    )
                ),
                "message" => array(
                    "attrs" => array(
                        "content" => __("Join our mailing list to receive our latest news and updates.", "delipress"),
                    ),
                    "styling" => array()
                ),
                "email_input_form_placeholder" => array(
                    "attrs" => array(
                        "content" => __("Your Email","delipress")
                    ),
                    "styling" => array()
                ),
                "rgpd" => array(
                    "attrs" => array(
                        "content" => __("Content privacy", "delipress"),
                        "url_privacy" => ""
                    )
                ),
                "button" => array(
                    "attrs" => array(
                        "content" => __("Subscribe","delipress"),
                        "borderStyle" => "solid",
                        "borderWidth" => 0,
                        "backgroundColor" => array(
                            "hex" => '#F66F9A',
                            "rgb" => array(
                                "r" => 246,
                                "g" => 111,
                                "b" => 154,
                                "a" => 1,
                            )
                        ),
                        "color" => array(
                            "hex" => '#FFFfff',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 1,
                            )
                        )
                    ),
                    "styling" => array(
                        "backgroundColor" => array(
                            "hex" => '#F66F9A',
                            "rgb" => array(
                                "r" => 246,
                                "g" => 111,
                                "b" => 154,
                                "a" => 1,
                            )
                        ),
                        "borderStyle" => "solid",
                        "borderWidth" => "0px",
                        "color" => array(
                            "hex" => '#FFFfff',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 1,
                            )
                        )
                    )
                ),
                "title" => array(
                    "attrs" => array(
                        "content" => sprintf(_x("Subscribe to the %s Newsletter", 'default Opt-In title', "delipress"), get_option('blogname') ),
                    ),
                    "styling" => array(

                    )
                ),
                "fields" => array(
                    "attrs" => array(
                        "borderRadius" => 5,
                        "borderWidth" => 0,
                        "borderStyle" => "solid",
                        "borderColor" => array(
                            "hex" => '#ffffff',
                            "rgb" => array(
                                "r" => 255,
                                "g" => 255,
                                "b" => 255,
                                "a" => 1,
                            )
                        )
                    ),
                    "styling" => array(
                        "color"=> array(
                            "hex" => '#000000',
                            "rgb" => array(
                                "r" => 0,
                                "g" => 0,
                                "b" => 0,
                                "a" => 1,
                            )
                        )
                    )
                )
            ),
            "success_settings" => array(
                "email_input_form" => array(
                    "attrs" => array(
                        "disable_email_input_form" => true,
                    )
                ),
                "message" => array(
                    "attrs" => array(
                        "content" => _x("Thank you for your subscription. See you soon!", 'default Opt-In confirmation message', "delipress")
                    )
                )
            )
        );

        return apply_filters(DELIPRESS_SLUG . "_config_default_optin", $configOptin);
    }

    /**
     * @action DELIPRESS_SLUG . "_before_create_optin_step_one"
     * @action DELIPRESS_SLUG . "_after_create_optin_step_one"
     *
     *
     * @param int $optinId
     * @return array
     */
    public function createOptin($optinId = null){

        $params = $this->getPostParams("fields", "step_1");
        $metas  = $this->getPostParams("meta", "step_1");

        do_action(DELIPRESS_SLUG . "_before_create_optin_step_one", $optinId, $params);

        $this->prepareMissingParameters();
        $this->verifyParameters($params);

        if(
            !empty($this->missingParameters) ||
            $this->paramsNotValid > 0
        ){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false
            );
        }

        $postId = null;
        if( !$optinId ){
            $args = array(
                'post_title'    => $params[PostTypeHelper::OPTIN_NAME],
                'post_content'  => "",
                'post_status'   => "publish",
                'post_type'     => PostTypeHelper::CPT_OPTINFORMS
            );

            $postId = wp_insert_post($args);

        }
        else{
            $args = array(
                'ID'            => $optinId,
                'post_title'    => $params[PostTypeHelper::OPTIN_NAME]
            );

            $postId =  wp_update_post($args);

        }

        if(is_wp_error( $postId ) ){
            return array(
                "success" => false,
                "results" => $postId->get_error_messages()
            );
        }

        $metas[PostTypeHelper::OPTIN_TAXO_LISTS] = $params[PostTypeHelper::OPTIN_TAXO_LISTS];

        foreach($metas as $key => $value){
            update_post_meta($postId, $key, $value);
        }

        if(!array_key_exists(PostTypeHelper::META_OPTIN_IS_ACTIVE, $metas) ) {
            update_post_meta($postId, PostTypeHelper::META_OPTIN_IS_ACTIVE, 0);
        }

        $configOptin = get_post_meta($postId, PostTypeHelper::META_OPTIN_CONFIG, true);

        if(empty($configOptin) ){

            $configOptin = $this->getConfigOptin();

            update_post_meta($postId, PostTypeHelper::META_OPTIN_CONFIG, json_encode( $configOptin, JSON_UNESCAPED_UNICODE ) );
        }

        do_action(DELIPRESS_SLUG . "_after_create_optin_step_one");

        if($metas[PostTypeHelper::META_OPTIN_TYPE] == OptinHelper::CONTACT_FORM_7){
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("Opt-In form successfully created", "delipress")
            );
        }


        AdminFormValues::cleanFormValues();

        return array(
            "success" => true,
            "results" => array(
                "optin_id" => $postId,
                "type"     => $metas[PostTypeHelper::META_OPTIN_TYPE]
            )
        );

    }

    /**
     * @action DELIPRESS_SLUG . "_before_create_optin_step_two"
     * @action DELIPRESS_SLUG . "_after_create_optin_step_two"
     *
     * @param int $optinId
     * @return array
     */
    public function createOptinStepTwo($optinId){

        do_action(DELIPRESS_SLUG . "_before_create_optin_step_two", $optinId);

        $optin = $this->checkOptinExist($optinId);

        if(!$optin){
            return array(
                "success" => false
            );
        }

        $optinHelper = OptinHelper::getOptinByKey($optin->getType());

        if(!$optinHelper["has_behavior"]){
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("Opt-In form successfully created", "delipress")
            );
        }


        do_action(DELIPRESS_SLUG . "_after_create_optin_step_two", $optinId);

        return array(
            "success" => true
        );


    }

    /**
     * @action DELIPRESS_SLUG . "_before_create_optin_step_three"
     * @action DELIPRESS_SLUG . "_after_create_optin_step_three"
     *
     * @param int $optinId
     * @return array
     */
    public function createOptinStepThree($optinId){

        do_action(DELIPRESS_SLUG . "_before_create_optin_step_three", $optinId);

        $optin = $this->checkOptinExist($optinId);

        if(!$optin){

            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );

            return array(
                "success" => false
            );
        }

        $this->fieldsPostMetas["step_3"]     = OptinHelper::getFormPostAuthorize($optin->getType());

        $metas  = $this->getPostParams("meta", "step_3");

        update_post_meta($optin->getId(), PostTypeHelper::META_OPTIN_BEHAVIOR, $metas );

        do_action(DELIPRESS_SLUG . "_after_create_optin_step_three", $optinId);

        return array(
            "success" => true
        );


    }

    /**
     *
     * @param array $values
     * @return array
     */
    public function checkDisplayPages($values){

        foreach($values as $key => $value){
            foreach($value as $keyV => $data){
                switch($keyV){
                    case "all":
                        $values[$key][$keyV] = (bool) $data;
                        break;
                    case "choice_pages":
                        $values[$key][$keyV] = array_map("intval", $data);
                        break;
                }
            }
        }

        return $values;
    }

}
