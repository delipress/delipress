<?php

namespace Delipress\WordPress\Services\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Services\AbstractTable;

use Delipress\WordPress\Helpers\TableHelper;

/**
 * SubscriberTableServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SubscriberTableServices extends AbstractTable {

    protected $charset = null;

    protected $tableName = TableHelper::TABLE_SUBSCRIBER;

    protected $alias = "s";

    protected $fields = array(
        "id bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
        "first_name varchar(255) DEFAULT NULL,",
        "last_name varchar(255) DEFAULT NULL,",
        "created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
        "email varchar(170) NOT NULL DEFAULT '',",
        "salt longtext,",
        "provider_id longtext,",
        "token_unsubscribe longtext,",
        "is_synchronize tinyint(11) DEFAULT NULL,",
        "is_confirmed tinyint(4) DEFAULT '0',",
        "PRIMARY KEY (id),",
        "UNIQUE KEY email (email)"
    );

    /**
     * Get args default
     * 
     * @return string
     */
    protected function getArgsInsertDefault(){
        $now         = new \DateTime("now");

        return array(
            "first_name"        => "",
            "last_name"         => "",
            "email"             => "",
            "salt"              => md5(strtotime("now")),
            "provider_id"       => "",
            "token_unsubscribe" => "",
            "is_confirmed"      => 0,
            "is_synchronize"    => 0,
            "created_at"        => $now->format("Y-m-d H:i:s")
        );
    }

    /**
     * Prepare values insert SQL
     * 
     * @param array $args
     * @return string
     */
    protected function insertSubscriberValues($args){

        $argsDefault = $this->getArgsInsertDefault();

        $args = array_merge($argsDefault, $args);

        if(empty($args["email"])){
            return false;
        }

        return "(
            '{$args["first_name"]}',
            '{$args["last_name"]}',
            '{$args["email"]}',
            '{$args["salt"]}',
            '{$args["provider_id"]}',
            '{$args["token_unsubscribe"]}',
            {$args["is_synchronize"]},
            {$args["is_confirmed"]},
            '{$args["created_at"]}'
        )";
    }

    /**
     * Prepare begin insert SQL
     * @return string
     */
    protected function insertSubscriberInstruction(){
        global $wpdb;

        return "
            INSERT INTO {$wpdb->prefix}{$this->tableName} 
            (
                first_name,
                last_name,
                email,
                salt,
                provider_id,
                token_unsubscribe,
                is_synchronize,
                is_confirmed,
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
                case "email":
                case "first_name":
                case "last_name":
                case "salt":
                case "token_unsubscribe":
                case "created_at":
                case "provider_id":
                    $formats[] = "%s";
                    break;
                case "id":
                case "is_synchronize":
                case "is_confirmed":
                    $formats[] = "%d";
                    break;
            }
        }
        
        return $formats;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_insert_subscriber"
     * @action DELIPRESS_SLUG . "_after_insert_subscriber"
     * 
     * @param array $args
     * @return void
     */
    public function insertSubscriber($args = array()){
        
        do_action(DELIPRESS_SLUG . "_before_insert_subscriber", $args);
        
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

        do_action(DELIPRESS_SLUG . "_after_insert_subscriber", $args, $wpdb->insert_id);

        return $wpdb->insert_id;

    }

    /**
     * Insert multiple subscribers
     * 
     * @param array $subscribers
     * @return void
     */
    public function insertSubscribers($subscribers){

        do_action(DELIPRESS_SLUG . "_before_insert_subscribers", $subscribers);

        global $wpdb;

        $sql = $this->insertSubscriberInstruction();
        
        $i = 1;
        $total = count($subscribers);
        foreach($subscribers as $key => $subscriber){
            $sql .= $this->insertSubscriberValues($subscriber);

            if($i < $total){
                $sql .= ",";
            }
            $i++;
        }

        $wpdb->query($sql);

        do_action(DELIPRESS_SLUG . "_after_insert_subscribers", $subscribers);
    }   

    /**
     * @param array $ids
     * @return void
     */
    public function deleteSubscribers($ids){

        do_action(DELIPRESS_SLUG . "_before_delete_subscribers", $ids);

        foreach($ids as $key => $id){
            $this->deleteSubscriber($id);
        }

        do_action(DELIPRESS_SLUG . "_after_delete_subscribers", $ids);
    }

    /**
     * 
     * @param int $id
     * @return void
     */
    public function deleteSubscriber($id){
        $this->delete($id);
    }

  
    /**
     * 
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return string
     */
    protected function getSqlSubscribers($args = array(), $page = 1, $limit = 20){

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

        $sql .= $this->getFrom();
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
    public function getSubscribers($args = array(), $page = 1, $limit = 20){
        global $wpdb;

        $sql = $this->getSqlSubscribers($args, $page, $limit);

        $subscribers = $wpdb->get_results($sql, ARRAY_A);

        return $subscribers;

    }


    /**
     * 
     * @param array $args
     * @return array
     */
    public function getSubscriber($args){
        global $wpdb;

        $sql = $this->getSqlSubscribers($args, null, null);

        $subscriber = $wpdb->get_row($sql, ARRAY_A);
        
        return $subscriber;

    }

    /**
     * 
     * @param int $id
     * @return array
     */
    public function getSubscriberById($id){
        global $wpdb;

        $sql = $this->getSqlSubscribers(
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

        $subscriber = $wpdb->get_row($sql, ARRAY_A);

        return $subscriber;

    }

    /**
     *
     * @param string $email
     * @return array
     */
    public function getSubscriberByEmail($email){

        global $wpdb;
        
        $sql = $this->getSqlSubscribers(
            array(
                "query" => array(
                    array(
                        "field"    => "email",
                        "operator" => "=",
                        "value"    => $email
                    )
                )
            ), 
            null
        );

        $subscriber = $wpdb->get_row($sql, ARRAY_A);

        return $subscriber;
    }
}









