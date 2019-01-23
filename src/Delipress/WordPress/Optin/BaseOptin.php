<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksFrontInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Models\OptinModel;

/**
 * BaseOptin
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class BaseOptin extends AbstractHook implements HooksFrontInterface{

    protected $typeOptin = null;

    protected $echo = true;

    protected $query = false;

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices = $containerServices->getService("OptionServices");
        $this->optinServices  = $containerServices->getService("OptinServices");
    }


    /**
     * @see HooksFrontInterface
     */
    public function hooks(){
        $this->prepareOptin();
    }

    /**
     * @return array
     */
    public function getOptins(){
        return array();
    }

    public function prepareOptin(){

        $this->optins = $this->getOptins();

        if(empty($this->optins)){
            return;
        }

        add_action("wp_footer", array($this, "delipressOptin"));

    }

    /**
     * @see wp_footer
     */
    public function delipressOptin(){

        $loop = $this->getLoop();
        foreach($this->optins as $key => $optin){

            $optinModel = new OptinModel();
            $optinModel->setOptinById($key);

            $lists = $optinModel->getLists();
            if(empty($lists)){
                continue;
            }

            $behavior      = unserialize( $optin["behavior"] );

            // Check Polylang && WPML
            if ( function_exists( 'pll_current_language' ) ){
                $current_language = pll_current_language('slug');

                if(
                    isset($behavior["display_languages"] ) &&
                    $current_language != $behavior['display_languages'] &&
                    $behavior["display_languages"] !== "all"
                ){
                    continue;
                }
            }

            if ( defined( 'ICL_LANGUAGE_CODE' ) ){
                if(
                    isset($behavior["display_languages"] ) &&
                    ICL_LANGUAGE_CODE != $behavior['display_languages'] &&
                    $behavior["display_languages"] !== "all"
                ){
                    continue;
                }
            }


            if(
                $behavior == null ||
                !isset($behavior["display_pages"]) ||
                $behavior["display_pages"] == null
            ){
                continue;
            }


            if(
                array_key_exists("everything", $behavior["display_pages"]) &&
                $behavior["display_pages"]["everything"]["all"] &&
                $loop != "privacypage"
            ){
                $this->printOptin($key, $optin, $this->typeOptin);
                continue;
            }

            switch($loop){
                case "homepage":
                case "blogpage":
                case "archives":

                    if(!array_key_exists($loop, $behavior["display_pages"])){
                        continue;
                    }

                    $behaviorPages = $behavior["display_pages"][$loop];

                    if(!$behaviorPages){
                        continue;
                    }

                    if(array_key_exists("all", $behaviorPages) && $behaviorPages["all"]){
                        $this->printOptin($key, $optin, $this->typeOptin);
                        continue;
                    }
                    break;
                case "taxonomies":
                    if(array_key_exists("categories", $behavior["display_pages"]) && $behavior["display_pages"]["categories"]["all"] ){
                        $this->printOptin($key, $optin, $this->typeOptin);
                        continue;
                    }

                    $this->printOnTerm($key, $optin);
                    continue;
                    break;
                case "page":
                    $this->printOnPage($key, $optin);
                    continue;
                    break;
                case "single":
                    $this->printOnSingle($key, $optin);
                    continue;
                    break;
            }

        }

    }


    /**
     *
     * @return string
     */
    protected function getLoop(){
        global $wp_query;

        $loop = 'notfound';

        if ( $wp_query->is_page ) {
            if(is_front_page()){
                $loop = "homepage";
            }
            // Handle specific case for GRPD privacy page : don't display Opt-ins in this page
            else if( $wp_query->post->ID == get_option('wp_page_for_privacy_policy') ) {
                $loop = "privacypage";
            }
            else if(is_home()){
                $loop = "blogpage";
            }
            else {
                $loop = "page";
            }
        } elseif ( $wp_query->is_home ) {
            $loop = 'homepage';
        } elseif ( $wp_query->is_single ) {
            $loop = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
        } elseif ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax) {
            $loop = 'taxonomies';
        } elseif ( $wp_query->is_archive ) {
            $loop = 'archives';
        }

        return $loop;
    }

    /**
     *
     * @param int $key
     * @param array $optin
     * @return void
     */
    protected function printOnTerm($key, $optin){
        global $wp_query;

        if(!$wp_query->queried_object){
            return;
        }

        $termId      = $wp_query->queried_object->term_id;
        $taxo        = $wp_query->queried_object->taxonomy;
        $behavior    = unserialize( $optin["behavior"] );

        if(!array_key_exists($taxo, $behavior["display_pages"])){
            return;
        }

        if(
             array_key_exists("categories", $behavior["display_pages"]) &&
            array_key_exists("all", $behavior["display_pages"]["categories"]) &&
            $behavior["display_pages"]["categories"]["all"]
        ){
            $this->printOptin($key, $optin, $this->typeOptin);
            return;
        }


        $behaviorPages = $behavior["display_pages"][$taxo];

        if(array_key_exists("choice_pages", $behaviorPages) && in_array($termId, $behaviorPages["choice_pages"])){
            $this->printOptin($key, $optin, $this->typeOptin);
            return;
        }
    }

    /**
     *
     * @param string $postType
     * @param int $key
     * @param array $optin
     */
    protected function printOnPostType($postType, $key, $optin){
        global $wp_query;

        $postId      = $wp_query->post->ID;
        $behavior    = unserialize( $optin["behavior"] );

        if(!array_key_exists($postType, $behavior["display_pages"])){
            return;
        }

        if(
            array_key_exists("posttypes", $behavior["display_pages"]) &&
            array_key_exists("all", $behavior["display_pages"]["posttypes"]) &&
            $behavior["display_pages"]["posttypes"]["all"]
        ){
            $this->printOptin($key, $optin, $this->typeOptin);
            return;
        }

        $behaviorPages = $behavior["display_pages"][$postType];

        if(
            array_key_exists("all", $behaviorPages) &&
            $behaviorPages["all"]
        ){
            $this->printOptin($key, $optin, $this->typeOptin);
            return;
        }


        if(array_key_exists("choice_pages", $behaviorPages) && in_array($postId, $behaviorPages["choice_pages"])){
            $this->printOptin($key, $optin, $this->typeOptin);
            return;
        }
    }

    /**
     *
     * @param string $key
     * @param array $optin
     * @return void
     */
    protected function printOnPage($key, $optin){

       return $this->printOnPostType("page", $key, $optin);
    }

    /**
     *
     * @param string $key
     * @param array $optin
     * @return void
     */
    protected function printOnSingle($key, $optin){
        global $wp_query;
        return $this->printOnPostType($wp_query->post->post_type, $key, $optin);

    }

    /**
     *
     * @param int $id
     * @param array $optin
     * @param string $type
     */
    protected function printOptin($id, $optin, $type){

        $isLoadedScript = OptinHelper::isOptinScriptLoaded();

        if(!$isLoadedScript){
            OptinHelper::loadOptinScript(true);
        }

        echo sprintf(
            "<div
                id='DELI-%s-%s'
                class='delipress-optin'
                data-config='%s'
                data-id='%s'
                data-type='%s'
                data-behavior='%s'
            ></div>",
            $type,
            $id,
            esc_attr($optin["config"]),
            $id,
            $type,
            wp_json_encode(unserialize($optin["behavior"]))
        );


    }


}
