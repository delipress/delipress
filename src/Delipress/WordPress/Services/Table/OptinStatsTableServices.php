<?php

namespace Delipress\WordPress\Services\Table;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Services\AbstractTable;

use Delipress\WordPress\Helpers\TableHelper;
use Delipress\WordPress\Helpers\OptinHelper;

/**
 * OptinStatsTableServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class OptinStatsTableServices extends AbstractTable {

    protected $charset = null;

    protected $tableName = TableHelper::TABLE_OPTIN_STATS;

    protected $alias = "os";

    protected $fields = array(
        "id bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
        "optin_id bigint(20) unsigned NOT NULL DEFAULT '0',",
        "type varchar(50) DEFAULT NULL,",
        "created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
        "PRIMARY KEY (id, optin_id)"
    );

    /**
     * Get args default
     * 
     * @return string
     */
    protected function getArgsInsertDefault(){
        $now         = new \DateTime("now");

        return array(
            "type"              => "",
            "optin_id"          => "",
            "created_at"        => $now->format("Y-m-d H:i:s")
        );
    }

    /**
     * Prepare values insert SQL
     * 
     * @param array $args
     * @return string
     */
    protected function insertOptinStatValues($args){

        $argsDefault = $this->getArgsInsertDefault();

        $args = array_merge($argsDefault, $args);

        return "(
            {$args["optin_id"]},
            '{$args["type"]}',
            '{$args["created_at"]}'
        )";
    }

    /**
     * Prepare begin insert SQL
     * @return string
     */
    protected function insertOptinStatInstruction(){
        global $wpdb;

        return "
            INSERT INTO {$wpdb->prefix}{$this->tableName} 
            (
                optin_id,
                type,
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
                case "type":
                case "created_at":
                    $formats[] = "%s";
                    break;
                case "id":
                case "optin_id":
                    $formats[] = "%d";
                    break;
            }
        }
        
        return $formats;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_insert_optin_stat"
     * @action DELIPRESS_SLUG . "_after_insert_optin_stat"
     * 
     * @param array $args
     * @return void
     */
    public function insertOptinStat($args = array()){
        
        do_action(DELIPRESS_SLUG . "_before_insert_optin_stat", $args);
        
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

        do_action(DELIPRESS_SLUG . "_after_insert_optin_stat", $args, $wpdb->insert_id);

        return $wpdb->insert_id;

    }

    /**
     * Insert multiple optin stats
     * @action DELIPRESS_SLUG . "_before_insert_optin_stats"
     * @action DELIPRESS_SLUG . "_after_insert_optin_stats"
     * 
     * @param array $optinStats
     * @return void
     */
    public function insertOptinStats($optinStats){

        do_action(DELIPRESS_SLUG . "_before_insert_optin_stats", $optinStats);

        global $wpdb;

        $sql = $this->insertOptinStatInstruction();
        
        $i = 1;
        $total = count($optinStats);
        foreach($optinStats as $key => $optinStat){
            $sql .= $this->insertOptinStatValues($optinStat);

            if($i < $total){
                $sql .= ",";
            }
            $i++;
        }

        $wpdb->query($sql);

        do_action(DELIPRESS_SLUG . "_after_insert_optin_stats", $optinStats);
    }   

    /**
     * @param array $ids
     * @return void
     */
    public function deleteOptinStats($ids){

        do_action(DELIPRESS_SLUG . "_before_delete_optin_stats", $ids);

        foreach($ids as $key => $id){
            $this->deleteOptinStat($id);
        }

        do_action(DELIPRESS_SLUG . "_after_delete_optin_stats", $ids);
    }

    /**
     * 
     * @param int $id
     * @return void
     */
    public function deleteOptinStat($id){
        $this->delete($id);
    }

  
    /**
     * 
     * @param array $args
     * @param int $page
     * @param int $limit
     * @return string
     */
    protected function getSqlOptinStats($args = array(), $page = 1, $limit = 20){

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
    public function getOptinStats($args = array(), $page = 1, $limit = 20){
        global $wpdb;

        $args = array(
            "selects" => array(
                "type",
                "created_at"
            ),
            "query" => array(
                array(
                    "field"    => "created_at",
                    "operator" => ">=",
                    "value"    => "2017-07-20 00:00:00",
                    "type"     => "datetime"
                ),
                array(
                    "field"    => "optin_id",
                    "operator" => "=",
                    "value"    => 80,
                    "type"     => "int"
                )
            )
        );

        $sql = $this->getSqlOptinStats($args, $page, $limit);
        return $wpdb->get_results($sql, ARRAY_A);

    }


    /**
     * 
     * @param array $args
     * @return array
     */
    public function getOptinStat($args){
        global $wpdb;

        $sql = $this->getSqlOptinStats($args, null, null);

        $optinStat = $wpdb->get_row($sql, ARRAY_A);

        return $optinStat;

    }

    protected function getCountMeta($optinId, $args){
        global $wpdb;

        $meta = OptinHelper::COUNTER_VIEW;

        $sql = "
            SELECT COUNT({$this->getAlias()}.type) as nb
            FROM {$wpdb->prefix}{$this->tableName} {$this->getAlias()}
            WHERE 1=1
            AND {$this->getAlias()}.optin_id = {$optinId}
            AND {$this->getAlias()}.type = '{$args["meta"]}'
        ";
        
        return $wpdb->get_var($sql);

    }

    /**
     *
     * @param int $optinId
     * @return array
     */
    public function getCountView($optinId){

        return $this->getCountMeta($optinId, array(
            "meta" => OptinHelper::COUNTER_VIEW
        ));

    }

    /**
     *
     * @param int $optinId
     * @return array
     */
    public function getCountConvert($optinId){
        return $this->getCountMeta($optinId, array(
            "meta" => OptinHelper::COUNTER_CONVERT
        ));
    }

    public function getStatsTimeseries($optinId, $args = array()){
        global $wpdb;

        if(!isset($args["start"]) ){
            return array();
        }

        $argsDefault = array(
            "range" => "day",
            "meta"  => OptinHelper::COUNTER_VIEW
        );

        $args = array_merge($argsDefault, $args);

        $selects = "SELECT COUNT({$this->alias}.id) as nb, ";
        $from    = "FROM {$wpdb->prefix}{$this->tableName} {$this->alias} ";
        $where   = "WHERE 1=1 
            AND {$this->alias}.optin_id = {$optinId} 
            AND {$this->alias}.type = '{$args["meta"]}'
            AND {$this->alias}.created_at >= '{$args["start"]}'
        ";
        $groupBy = "";

        switch ($args["range"]) {
            case 'minute':
                $selects .= "
                    DATE_FORMAT({$this->alias}.created_at, '%i'), 
                    DATE_FORMAT({$this->alias}.created_at, '%h'), 
                    DATE_FORMAT({$this->alias}.created_at, '%d') as day, 
                    DATE_FORMAT({$this->alias}.created_at, '%m') as month, 
                    DATE_FORMAT({$this->alias}.created_at, '%Y') as year ";
                $groupBy = "GROUP BY
                        DATE_FORMAT({$this->alias}.created_at, '%i'),
                        DATE_FORMAT({$this->alias}.created_at, '%h'),
                        DATE_FORMAT({$this->alias}.created_at, '%d'),
                        DATE_FORMAT({$this->alias}.created_at, '%m'),
                        DATE_FORMAT({$this->alias}.created_at, '%Y')
                ";
                break;
            case 'hour':
                $selects .= "
                    DATE_FORMAT({$this->alias}.created_at, '%h'), 
                    DATE_FORMAT({$this->alias}.created_at, '%d') as day, 
                    DATE_FORMAT({$this->alias}.created_at, '%m') as month, 
                    DATE_FORMAT({$this->alias}.created_at, '%Y') as year ";
                $groupBy = "GROUP BY
                        DATE_FORMAT({$this->alias}.created_at, '%h'),
                        DATE_FORMAT({$this->alias}.created_at, '%d'),
                        WEEK({$this->alias}.created_at),
                        DATE_FORMAT({$this->alias}.created_at, '%m'),
                        DATE_FORMAT({$this->alias}.created_at, '%Y')
                ";
                break;
            case 'day':
            default:
                $selects .= "
                    DATE_FORMAT({$this->alias}.created_at, '%d') as day, 
                    DATE_FORMAT({$this->alias}.created_at, '%m') as month, 
                    DATE_FORMAT({$this->alias}.created_at, '%Y') as year ";
                $groupBy = "GROUP BY
                        DATE_FORMAT({$this->alias}.created_at, '%d'),
                        DATE_FORMAT({$this->alias}.created_at, '%m'),
                        DATE_FORMAT({$this->alias}.created_at, '%Y')
                ";
                break;
            case 'month':
                $selects .= "
                    DATE_FORMAT({$this->alias}.created_at, '%m') as month, 
                    DATE_FORMAT({$this->alias}.created_at, '%Y') as year ";
                $groupBy = "GROUP BY
                        DATE_FORMAT({$this->alias}.created_at, '%m'),
                        DATE_FORMAT({$this->alias}.created_at, '%Y')
                ";
                break;
            case 'year':
                $selects .= "
                    DATE_FORMAT({$this->alias}.created_at, '%Y') as year ";
                $groupBy = "GROUP BY
                        DATE_FORMAT({$this->alias}.created_at, '%Y')
                ";
                break;


        }

        $sql = $selects . $from . $where . $groupBy;

        return $wpdb->get_results($sql, ARRAY_A);
    }


}









