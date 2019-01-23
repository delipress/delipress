<?php

namespace Delipress\WordPress\Services\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Services\AbstractTable;

use Delipress\WordPress\Helpers\TableHelper;

/**
 * SubscriberMetaTableServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SubscriberMetaTableServices extends AbstractTable {

    protected $charset = null;

    protected $tableName = TableHelper::TABLE_SUBSCRIBER_META;

    protected $alias = "sm";

    protected $fields = array(
        "id bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
        "subscriber_id bigint(20) unsigned NOT NULL DEFAULT '0',",
        "meta_id bigint(20) unsigned NOT NULL DEFAULT '0',",
        "meta_value longtext,",
        "created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
        "PRIMARY KEY (id),",
        "KEY subscriber_id (subscriber_id),",
        "KEY meta_id (meta_id)"
    );

    /**
     * Get args default
     * 
     * @return array
     */
    protected function getArgsInsertDefault(){
        $now         = new \DateTime("now");

        return array(
            "subscriber_id"   => "",
            "meta_id"        => "",
            "meta_value"      => "",
            "created_at"      => $now->format("Y-m-d H:i:s")
            
        );
    }

    /**
     * Prepare values insert SQL
     * 
     * @param array $args
     * @return string
     */
    protected function insertSubscriberMetaValues($args){

        $argsDefault = $this->getArgsInsertDefault();

        $args = array_merge($argsDefault, $args);

        return "(
            {$args["subscriber_id"]},
            {$args["meta_id"]},
            '{$args["meta_value"]}',
            '{$args["created_at"]}'
        )";
    }

    /**
     * Prepare begin insert SQL
     * @return string
     */
    protected function insertSubscriberMetaInstruction(){
        global $wpdb;

        return "
            INSERT INTO {$wpdb->prefix}{$this->tableName} 
            (
                subscriber_id,
                meta_id,
                meta_value,
                created_at
            ) VALUES 
        ";
    }

    /**
     * @see AbstractTable
     * 
     * @param array $args
     * @return array
     */
    public function getFormatFields($args){
        $formats = array();
        foreach($args as $key => $arg){
            switch($key){
                case "subscriber_id":
                case "meta_id":
                    $formats[] = "%d";
                break;
                case "meta_value":
                case "created_at":
                    $formats[] = "%s";
                    break;
            }
        }
        
        return $formats;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_insert_subscriber_meta"
     * @action DELIPRESS_SLUG . "_after_insert_subscriber_meta"
     * 
     * @param array $args
     * @return void
     */
    public function insertSubscriberMeta($args = array()){
        
        do_action(DELIPRESS_SLUG . "_before_insert_subscriber_meta", $args);
        
        global $wpdb;
        
        $table       = $wpdb->prefix . $this->tableName;
        
        $argsDefault = $this->getArgsInsertDefault();
        $args        = array_merge($argsDefault, $args);

        $formats = $this->getFormatFields($args);

        $wpdb->insert( 
            $table,
            $args, 
            $formats
        );

        do_action(DELIPRESS_SLUG . "_after_insert_subscriber_meta", $args, $wpdb->insert_id);

        return $wpdb->insert_id;

    }

    /**
     * 
     * @action DELIPRESS_SLUG . "_before_insert_subscriber_metas"
     * @action DELIPRESS_SLUG . "_after_insert_subscriber_metas"
     * 
     * @param array $subscriberMetas
     * @return void
     */
    public function insertSubscriberMetas($subscriberMetas){

        do_action(DELIPRESS_SLUG . "_before_insert_subscriber_metas", $subscriberMetas);

        global $wpdb;

        $sql = $this->insertSubscriberMetaInstruction();
        
        $i = 1;
        $total = count($subscriberMetas);
        foreach($subscriberMetas as $key => $subscriberMeta){
            $sql .= $this->insertSubscriberMetaValues($subscriberMeta);

            if($i < $total){
                $sql .= ",";
            }
            $i++;
        }

        $wpdb->query($sql);

        do_action(DELIPRESS_SLUG . "_after_insert_subscriber_metas", $subscriberMetas);
    }   


    /**
     * 
     * @param int $id
     * @return void
     */
    public function deleteSubscriberMeta($id){
        $this->delete($id);
    }

    protected function getMetaInnerJoin(){
        global $wpdb;

        $table = TableServices::getTable("MetaTableServices");

        return "INNER JOIN {$table->getFullTableName()} {$table->getAlias()} ON {$this->alias}.meta_id = {$table->getAlias()}.id ";
    }

  
    /**
     * 
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return string
     */
    protected function getSqlSubscriberMetas($args = array(), $page = 1, $limit = 20){

        global $wpdb;

        $argsDefault = array(
            "selects"      => "*",
            "query"        => array(
                array(
                    "field"    => "id",
                    "operator" => "=",
                    "value"    => ""
                )
            ),
            "limit"        => 20,
        );

        $args = array_merge($argsDefault, $args);

        $sql = "SELECT ";

        $sql .= $this->getSelects($args);


        if(!empty($args["links"])){

            if(array_key_exists("meta", $args["links"])){
                $sql .= ", ";
                $sql .= TableServices::getTable("MetaTableServices")->getSelects($args["links"]["meta"]);
            }
        }

        $sql .= $this->getFrom();

        if(!empty($args["links"])){

            if(array_key_exists("meta", $args["links"])){
                $sql .= $this->getMetaInnerJoin();
            }
        }

        $sql .= "WHERE 1=1 ";

        $sql .= $this->getQuery($args["query"]);

        $sql .= $this->getOffsetLimitByPage($page, $limit);

        return $sql;
    }

    /**
     * 
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getSubscriberMetas($args = array(), $page = 1, $limit = 20){
        global $wpdb;

        $sql = $this->getSqlSubscriberMetas($args, $page, $limit);

        $subscriberMetas = $wpdb->get_results($sql, ARRAY_A);

        return $subscriberMetas;

    }


    /**
     * 
     * @param array $args
     * @return array
     */
    public function getSubscriberMeta($args){
        global $wpdb;

        $sql = $this->getSqlSubscriberMetas($args, null, null);

        $subscriber = $wpdb->get_row($sql, ARRAY_A);
        
        return $subscriber;

    }

    /**
     * 
     * @param int $id
     * @return array
     */
    public function getSubscriberMetaById($id){
        global $wpdb;

        $sql = $this->getSqlSubscriberMetas(
            array(
                "query" => array(
                    array(
                        "field"    => "id",
                        "operator" => "=",
                        "value"    => $id,
                        "type"     => "int"
                    )
                )
            ), 
            null
        );

        $subscriberMeta = $wpdb->get_row($sql, ARRAY_A);

        return $subscriberMeta;

    }

    /**
     * 
     * @param int $id
     * @return array
     */
    public function getSubscriberMetaBySubscriberId($subscriberId, $page = 1, $limit = 20, $params = array()){
        global $wpdb;

        return $this->getSubscriberMetas(
            array_merge_recursive(
                array(
                    "query" => array(
                        array(
                            "field"    => "subscriber_id",
                            "operator" => "=",
                            "value"    => $subscriberId,
                            "type"     => "int"
                        )
                    )
                ),
                $params
            ), 
            $page, $limit
        );

    }

}









