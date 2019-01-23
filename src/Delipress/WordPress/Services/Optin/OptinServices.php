<?php

namespace Delipress\WordPress\Services\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Models\OptinModel;

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\OptinHelper;


/**
 * OptinServices
 *
 * @author Delipress
 */
class OptinServices implements ServiceInterface{

    protected $countPosts = 0;

    /**
     * 
     * @return int
     */
    public function getCountLastgetOptins(){
        return $this->countPosts;
    }

    /**
     * 
     * @param array $args
     * @return OptinModel[]
     */
    public function getOptins($args = array()){

        $optins = new \WP_Query(
            array_merge(
                array(
                    "post_type"   => PostTypeHelper::CPT_OPTINFORMS,
                ),
                $args
            )
        );


        $optinsModels = array();
        while($optins->have_posts()){
            $value = $optins->next_post(); 
            $optin = new OptinModel();
            $optin->setOptin($value);
            $optinsModels[] = $optin;
        }


        $this->countPosts = $optins->found_posts;

        return $optinsModels;

    }


    /**
     * 
     * @return boolean
     */
    public function hasOneOptin(){
        $args = array(
            "post_type"     => PostTypeHelper::CPT_OPTINFORMS,
            "post_per_page" => 1
        );

        $posts = get_posts($args);
        if(!empty($posts)){
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCreateUrl($nextStep = 1, $optinId = null){

        return add_query_arg(
            array(
                "page"     => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS),
                "action"   => "create",
                "step"     => $nextStep,
                "optin_id" => $optinId
            ),
            admin_url("admin.php")
        );

    }
    
    /**
     * 
     * @return string
     */
    public function getPageUrl(){
        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS),
            ),
            admin_url("admin.php")
        );

    }


    /**
     * @param int $optinId
     * @return string
     */
    public function getDeleteOptinUrl($optinId){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"       => ActionHelper::DELETE_OPTIN,
                    "optin_id"     => $optinId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_OPTIN
        );
    }

    /**
     * @param int $optinId
     * @return string
     */
    public function getDeleteOptinsUrl($type){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"       => ActionHelper::DELETE_OPTINS,
                    "type"         => $type
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_OPTINS
        );
    }


    /**
     * @return string
     */
    public function getCreateOptinUrlFormAdminPost($step = 1, $optinId = null){

        $action = ActionHelper::CREATE_OPTIN;
        switch($step){
            case 2:
                $action = ActionHelper::CREATE_OPTIN_STEP_TWO;
                break;
            case 3:
                $action = ActionHelper::CREATE_OPTIN_STEP_THREE;
                break;

        }

        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"      => $action,
                    "optin_id"    => $optinId,
                ),
                admin_url("admin-post.php")
            ),
            $action
        );
    }

    /**
     *
     * @param int $optinId
     * @return void
     */
    public function getOptinStatistic($optinId){
        return add_query_arg(
            array(
                "page"        => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS),
                "action"      => "statistic",
                "optin_id" => $optinId
            ),
            admin_url("admin.php")
        );
    }



    /**
     *
     * @param array $params
     * @return array
     */
    public function getOptinsSql($optinType, $params = array()){

        if(!isset($params["active"])){
            $params["active"] = 1;
        }

        if(!isset($params["status"])){
            $params["status"] = "publish";
        }

        global $wpdb;
        
        $selects = " SELECT 
                p.ID as ID, 
                mt2.meta_value as config ";

        if(isset($params["selects"])){
            foreach($params["selects"] as $key => $select){
                $selects .= ", p.{$select}";
            }
        }

        $from = " FROM {$wpdb->prefix}postmeta pm
            INNER JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
            INNER JOIN {$wpdb->prefix}postmeta AS mt1 ON mt1.post_id = p.ID
            INNER JOIN {$wpdb->prefix}postmeta AS mt2 ON mt2.post_id = p.ID ";

        $where = " WHERE p.post_type = %s
            AND p.post_status = %s
            AND ( pm.meta_key = %s AND pm.meta_value = %s )
            AND ( mt1.meta_key = %s AND mt1.meta_value = %s )
            AND ( mt2.meta_key = %s AND mt2.meta_value IS NOT NULL ) ";

        if(isset($params["where"])){
            foreach($params["where"] as $key => $pWhere){
                switch($pWhere['field']){
                    case "ID":
                        $val = (int) $pWhere['value'];
                        $where .= " AND p.{$pWhere['field']} = {$val} ";
                        break;
                }
            }
        }
        
        $optinHelper = OptinHelper::getOptinByKey($optinType);
        
        if($optinHelper["has_behavior"]){
            $selects .= ", mt3.meta_value as behavior ";
            $from    .= " INNER JOIN {$wpdb->prefix}postmeta AS mt3 ON mt3.post_id = p.ID "; 
            $where   .= " AND ( mt3.meta_key = %s AND mt3.meta_value IS NOT NULL ) ";
            $sql     = $selects . $from . $where; 
            $query     = $wpdb->prepare( $sql, 
                PostTypeHelper::CPT_OPTINFORMS, 
                $params["status"],
                PostTypeHelper::META_OPTIN_IS_ACTIVE, 
                $params["active"], 
                PostTypeHelper::META_OPTIN_TYPE,
                $optinType,
                PostTypeHelper::META_OPTIN_CONFIG,
                PostTypeHelper::META_OPTIN_BEHAVIOR
            );
        }
        else{
            $sql     = $selects . $from . $where; 
            $query     = $wpdb->prepare( $sql, 
                PostTypeHelper::CPT_OPTINFORMS, 
                $params["status"],
                PostTypeHelper::META_OPTIN_IS_ACTIVE, 
                $params["active"], 
                PostTypeHelper::META_OPTIN_TYPE,
                $optinType,
                PostTypeHelper::META_OPTIN_CONFIG
            );
        }

        $results    = $wpdb->get_results( 
            $query, 
            ARRAY_A
        );

        $optins = array();
        foreach($results as $key => $result){
            $optins[$result["ID"]]["config"]    = $result["config"];
            $optins[$result["ID"]]["behavior"]  = array();
            if(!empty($result["behavior"])){
                $optins[$result["ID"]]["behavior"]  = $result["behavior"];
            }

            if(isset($params["selects"])){
                foreach($params["selects"] as $key => $select){
                    $optins[$result["ID"]][$select]  = $result[$select];
                }
            }
        }
        

        return $optins;
    }

    /**
     * Get optin shortcodes
     * 
     * @param array $params
     * @return array
     */
    public function getShortcodes($params = array()){
        return $this->getOptinsSql(OptinHelper::SHORTCODE, $params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPopups($params = array()){
        return $this->getOptinsSql(OptinHelper::POPUP, $params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getFlyOptins($params = array()){
        return $this->getOptinsSql(OptinHelper::FLY, $params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getWidgets($params = array()){
        return $this->getOptinsSql(OptinHelper::WIDGET, $params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getAfterContents($params = array()){
        return $this->getOptinsSql(OptinHelper::AFTER_CONTENT, $params);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getContactForm7($params = array()){
        return $this->getOptinsSql(OptinHelper::CONTACT_FORM_7, $params);
    }

    /**
     * @return array
     */
    public function deactivateOptin(OptinModel $optin){
        update_post_meta($optin->getId(), PostTypeHelper::META_OPTIN_IS_ACTIVE, 0);
    }

}









