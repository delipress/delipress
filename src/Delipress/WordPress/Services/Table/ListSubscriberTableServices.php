<?php

namespace Delipress\WordPress\Services\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Services\AbstractTable;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TableHelper;
use Delipress\WordPress\Helpers\SourceSubscriberHelper;

use Delipress\WordPress\Services\Table\TableServices;


/**
 * SubscriberTableServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ListSubscriberTableServices extends AbstractTable {

    protected $charset = null;

    protected $tableName = TableHelper::TABLE_LIST_SUBSCRIBER;

    protected $alias = "ls";

    protected $fields = array(
        "id bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
        "subscriber_id bigint(20) unsigned NOT NULL DEFAULT '0',",
        "term_taxonomy_id bigint(20) unsigned NOT NULL DEFAULT '0',",
        "source varchar(100) NOT NULL DEFAULT '0',",
        "status varchar(100) NOT NULL DEFAULT '0',",
        "is_synchronize tinyint(11) DEFAULT NULL,",
        "provider varchar(255) DEFAULT NULL,",
        "provider_id longtext,",
        "created_at datetime NULL DEFAULT NULL,",
        "PRIMARY KEY (id, subscriber_id, term_taxonomy_id),",
        "KEY term_taxonomy_id (term_taxonomy_id)"
    );

    /**
     * Get VALUES for INSERT INTO
     *
     * @param array $args
     * @return string
     */
    protected function insertListSubscriberValues($args){

        $now         = new \DateTime("now");

        $argsDefault = array(
            "subscriber_id"        => "",
            "term_taxonomy_id"     => "",
            "source"               => SourceSubscriberHelper::ADMINISTRATION,
            "status"               => "subscribe",
            "is_synchronize"       => 0,
            "provider"             => "",
            "provider_id"          => "",
            "created_at"           => $now->format("Y-m-d H:i:s"),
        );

        $args = array_merge($argsDefault, $args);

        if(empty($args["subscriber_id"]) || empty($args["term_taxonomy_id"])){
            return false;
        }


        return "(
            {$args["subscriber_id"]},
            {$args["term_taxonomy_id"]},
            '{$args["source"]}',
            '{$args["status"]}',
            {$args["is_synchronize"]},
            '{$args["provider"]}',
            '{$args["provider_id"]}',
            '{$args["created_at"]}'
        )";
    }

    /**
     * Get INSERT INTO sql
     *
     * @return string
     */
    protected function insertSubscriberInstruction(){
        global $wpdb;

        return "
            INSERT INTO {$wpdb->prefix}{$this->tableName}
            (
                subscriber_id,
                term_taxonomy_id,
                source,
                status,
                is_synchronize,
                provider,
                provider_id,
                created_at
            ) VALUES
        ";
    }

    protected function getSubscriberInnerJoin(){
        global $wpdb;

        $table = TableServices::getTable("SubscriberTableServices");

        return "INNER JOIN {$table->getFullTableName()} {$table->getAlias()} ON {$this->alias}.subscriber_id = {$table->getAlias()}.id ";
    }

    /**
     *
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return string
     */
    protected function getSqlListSubscribers($args = array(), $page = 1, $limit = 20){

        global $wpdb;

        $argsDefault = array(
            "selects"        => "*",
            "query"          =>  array(
                array(
                    "field"    => "term_taxonomy_id",
                    "operator" => "=",
                    "value"    => ""
                )
            ),
            "links"          => array()
        );

        $args = array_merge($argsDefault, $args);

        $sql = "SELECT ";

        $sql .= $this->getSelects($args);

        if(!empty($args["links"])){

            if(array_key_exists("subscribers", $args["links"])){
                $sql .= ", ";
                $sql .= TableServices::getTable("SubscriberTableServices")->getSelects($args["links"]["subscribers"]);
            }
        }

        $sql .= $this->getFrom();

        if(!empty($args["links"])){

            if(array_key_exists("subscribers", $args["links"])){
                $sql .= $this->getSubscriberInnerJoin();
            }
        }

        $sql .= "WHERE 1=1 ";

        $sql .= $this->getQuery($args["query"]);

        $sql .= $this->getOffsetLimitByPage($page, $limit);

        return $sql;
    }


    /**
     * @param array $listSubscribers
     */
    public function insertListSubscribers($listSubscribers){

        global $wpdb;

        $sql = $this->insertSubscriberInstruction();

        $i = 1;
        $total = count($listSubscribers);
        foreach($listSubscribers as $key => $listSubscriber){
            $sql .= $this->insertListSubscriberValues($listSubscriber);

            if($i < $total){
                $sql .= ",";
            }
            $i++;
        }

        return $wpdb->query($sql);
    }

    /**
     * @see AbstractTable
     * @param array $args
     * @return array
     */
    public function getFormatFields($args){
        $formats = array();
        foreach($args as $key => $arg){
            switch($key){
                case "subscriber_id":
                case "term_taxonomy_id":
                case "is_synchronize":
                    $formats[] = "%d";
                    break;
                case "source":
                case "status":
                case "created_at":
                case "provider":
                case "provider_id":
                    $formats[] = "%s";
                    break;
            }
        }

        return $formats;
    }

    /**
     *
     * @param array $args
     * @return int
     */
    public function insertListSubscriber($args){

        do_action(DELIPRESS_SLUG . "_before_insert_list_subscriber", $args);

        global $wpdb;

        $table = $wpdb->prefix . $this->tableName;


        if(!isset($args["created_at"])){
            $now                = new                 \DateTime("now");
            $args["created_at"] = $now->format("Y-m-d H:i:s");
        }
        
        $formats = $this->getFormatFields($args);

        $wpdb->insert(
            $table,
            $args,
            $formats
        );

        do_action(DELIPRESS_SLUG . "_after_insert_list_subscriber", $args, $wpdb->insert_id);

        return $wpdb->insert_id;

    }

    /**
     *
     * @param int $idList
     * @return array
     */
    public function getEmailListSubscribers($idList){
        global $wpdb;

        $subscriberTable = TableServices::getTable("SubscriberTableServices");
        $sAlias          = $subscriberTable->getAlias();

        $sql         = "
            SELECT {$sAlias}.email
            FROM {$wpdb->prefix}{$this->tableName} {$this->alias}
            INNER JOIN {$subscriberTable->getFullTableName()} {$sAlias} ON {$this->alias}.subscriber_id = {$sAlias}.id
            WHERE {$this->alias}.term_taxonomy_id = %s
        ";

        $results    = $wpdb->get_results(
            $wpdb->prepare( $sql, $idList ), ARRAY_A
        );

        return $results;

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

        $argsDefault = array(
            "selects" => array(
                "source"
            ),
            "links" => array(
                "subscribers" => array(
                    "selects" => "*"
                )
            )
        );

        $args        = array_merge($argsDefault, $args);
    
        return $this->getListSubscribers($args, $page, $limit);
    }

    /**
     *
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getListSubscribers($args = array(), $page = 1, $limit = 20){
        global $wpdb;

        $argsDefault = array(
            "selects" => "*",
            "links" => array()
        );

        $args        = array_merge($argsDefault,           $args);
        $sql         = $this->getSqlListSubscribers($args, $page, $limit);

        $results     = $wpdb->get_results($sql);

        return $results;
    }

    /**
     *
     * @param int $listId
     * @param int $subscriberId
     * @return array|null
     */
    public function getListSubscriberByListAndSubscriber($listId, $subscriberId){
        return $this->getListSubscriber(array(
            "selects" => "*",
            "query" => array(
                array(
                    "field"    => "subscriber_id",
                    "value"    => $subscriberId,
                    "operator" => "=",
                    "type"     => "int"
                ),
                array(
                    "field"    => "term_taxonomy_id",
                    "value"    => $listId,
                    "operator" => "=",
                    "type"     => "int"
                )
            )
        ));
    }

    /**
     *
     * @param int $idList
     * @param string $email
     * @return array|null
     */
    public function getListSubscriberByListIdAndEmail($idList, $email){

        global $wpdb;
        
        $alias           = $this->getAlias();
        $subscriberTable = TableServices::getTable("SubscriberTableServices");
        $sAlias          = $subscriberTable->getAlias();

        $sql         = "
            SELECT {$alias}.*
            FROM {$this->getFullTableName()} {$alias}
            INNER JOIN {$subscriberTable->getFullTableName()} {$sAlias} ON {$alias}.subscriber_id = {$sAlias}.id
            WHERE {$alias}.term_taxonomy_id = %s
            AND {$sAlias}.email = %s
        ";

        $result    = $wpdb->get_row( 
            $wpdb->prepare( $sql, $idList, $email),
            ARRAY_A
        );

        return $result;

    }

    /**
     *
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getListSubscriber($args = array()){
        global $wpdb;

        $sql = $this->getSqlListSubscribers($args, null);

        $listSubscriber = $wpdb->get_row($sql, ARRAY_A);

        return $listSubscriber;
    }

    /**
     * @param int $idList
     * @return int
     */
    public function countListSubscribers($idList){
        global $wpdb;

        $alias           = $this->getAlias();
        $subscriberTable = TableServices::getTable("SubscriberTableServices");
        $sAlias          = $subscriberTable->getAlias();

        $sql         = "
            SELECT count({$alias}.subscriber_id)
            FROM {$this->getFullTableName()} {$alias}
            INNER JOIN {$subscriberTable->getFullTableName()} {$sAlias} ON {$alias}.subscriber_id = {$sAlias}.id
            WHERE {$alias}.term_taxonomy_id = %s
        ";

        $result    = $wpdb->get_var( 
            $wpdb->prepare( $sql, $idList)
        );

        return $result;

    }

    /**
     * @return int
     */
    public function countListSubscribersNoSynchronize(){
        global $wpdb;

        $alias           = $this->getAlias();

        $sql         = "
            SELECT count({$alias}.id)
            FROM {$this->getFullTableName()} {$alias}
            WHERE ( {$alias}.is_synchronize IS NULL OR {$alias}.is_synchronize = 0 )
            AND {$alias}.provider_id IS NULL
        ";

        $result    = $wpdb->get_var( 
            $sql
        );

        return (int) $result;

    }

    /**
     *
     * @param int $idList
     * @return void
     */
    public function deleteListSubscribersByIdList($idList){
        global $wpdb;

        $table = $wpdb->prefix . $this->tableName;

        $wpdb->delete(
            $table,
            array(
                'term_taxonomy_id' => $idList,
            ),
            array(
                '%d'
            )
        );
    }

    /**
     *
     * @param int $idSubscriber
     * @param int $idList
     * @return void
     */
    public function deleteListSubscriber($idSubscriber, $idList){
        global $wpdb;

        $table        = $this->getFullTableName();

        $wpdb->delete(
            $table,
            array(
                'subscriber_id'    => $idSubscriber,
                'term_taxonomy_id' => $idList,
            ),
            array(
                '%d',
                '%d'
            )
        );

    }

    /**
     *
     * @param array $args
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    public function getSubscribersNoSynchronize($args = array(), $page = 1, $limit = 20){

        return $this->getSubscribers(
            array_merge_recursive(
                $args,
                array(
                    "selects" => array(
                        "id",
                        "source",
                        "subscriber_id",
                        "term_taxonomy_id"
                    ),
                    "links" => array(
                        "subscribers" => array(
                            "selects" => array(
                                "email"
                            )
                        )
                    ),
                    "query" => array(
                        array(
                            "field"    => "is_synchronize",
                            "operator" => "IS",
                            "value"    => "NULL"
                        ),
                        array(
                            "field"    => "provider_id",
                            "operator" => "IS",
                            "value"    => "NULL"
                        ),
                    )
                )   
            ),
            $page,
            $limit
        );

    }

}
