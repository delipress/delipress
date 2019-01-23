<?php

namespace Delipress\WordPress\Integration;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksFrontInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;


class AbstractIntegration extends AbstractHook {

    protected $shortcode = "delipress_checkbox";

    protected $checkboxName = "delipress_checkbox";

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices            = $containerServices->getService("OptionServices");
        $this->optinServices             = $containerServices->getService("OptinServices");
        $this->listSubscriberServices    = $containerServices->getService("ListSubscriberServices");
    }


    protected $fieldsAvailable = array(
        array(
            "key"      => "delipress-email",
            "required" => true,
            "callback" => "sanitize_email",
            "meta"     => false,
            "ref"      => "email",
        ),
        array(
            "key"      => "delipress-first_name",
            "required" => false,
            "callback" => "sanitize_text_field",
            "meta"     => false,
            "ref"      => "first_name",
        ),
        array(
            "key"      => "delipress-last_name",
            "required" => false,
            "callback" => "sanitize_text_field",
            "meta"     => false,
            "ref"      => "last_name",
        )
    );

    public function getFieldsAvailable(){
        return apply_filters(DELIPRESS_SLUG . "_integration_optin_fields_available", $this->fieldsAvailable);
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function getParams($data){

        $fields     = $this->getFieldsAvailable();
        $dataFilter = array_filter($fields, function($value){
            return !$value["meta"];
        });

        $params = array();

        foreach($fields as $key => $value){
            if(!isset($data[$value["key"]])){
                continue;
            }

            $params[$value["ref"]] = call_user_func($value["callback"], $data[$value["key"]]);
        }

        return $params;
    }

    public function getMetas(){}

    /**
     *
     * @return boolean
     */
    protected function isAuthorize(){
        return $this->optionServices->isValidLicense();
    }

    /**
     *
     * @return string
     */
    public function getShortcode(){
        return $this->shortcode;
    }

    /**
     * @return string
     */
    public function getCheckboxAttribute(){
        return apply_filters(DELIPRESS_SLUG . "_checkbox_attributes_integration", "");
    }

    /**
     *
     * @param int $optinId
     * @param array $args
     * @return string
     */
    public function getHtmlShortcode($optinId, $args = array()){

        $label = (isset($args["label"]) && !empty($args["label"])) ? $args["label"] : apply_filters(DELIPRESS_SLUG . "_label_shortcode_integration", __("Subscribe to newsletter","delipress") );


        ob_start();
        do_action( DELIPRESS_SLUG . "_before_html_shortcode_integration");

        echo sprintf('<label for="%s">', $this->checkboxName);
            echo sprintf("<input type='hidden' name='delipress_optin_id' value='%s' />", esc_attr($optinId) );
            echo sprintf( '<input id="%s" type="checkbox" name="%s" value="1" %s style="margin-right:10px"/>', esc_attr( $this->checkboxName ), esc_attr( $this->checkboxName ), $this->getCheckboxAttribute() );
            if(!empty($label)){
                echo sprintf( '<span>%s</span>', $label );
            }
        echo '</label>';


        $isLoadedScript = OptinHelper::isOptinScriptLoaded();

        if(!$isLoadedScript){
            OptinHelper::loadOptinScript(true);
        }

        echo sprintf(
            "<div
                id='DELI-cf7-%s'
                class='delipress-optin'
                data-config='%s'
                data-id='%s'
                data-type='%s'
            ></div>",
            $optinId,
            "{}",
            $optinId,
            OptinHelper::CONTACT_FORM_7
        );

        do_action( DELIPRESS_SLUG . "_after_html_shortcode_integration");

        $html = ob_get_clean();
        return $html;

    }

    public function addSubscriber($idOptin, $params){
        $this->listSubscriberServices->addSubscriberFromOptin($idOptin, $params);
    }


}
