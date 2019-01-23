<?php

namespace DeliSkypress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\TableInterface;
use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\WordPress\Services\SqlBuilderTrait;


class AbstractTable implements TableInterface, ServiceInterface{

    use SqlBuilderTrait;

    protected $charset = null;
     
    protected $fields = array();

    /**
     * @see TableInterface
     */
    public function createTable(){
        global $wpdb;

        if($this->charset === null){
            $this->charset = $wpdb->get_charset_collate();
        }
        if(empty($this->fields) || empty($this->tableName)){
            return;
        }

        $sql   = array();
        $sql[] = "CREATE TABLE {$wpdb->prefix}{$this->tableName} (";
        $sql   = array_merge($sql, $this->fields);
        $sql[] = ") {$this->charset};";
        

        if(!function_exists("dbDelta")){
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }

        $sql = implode("\n", $sql);

        dbDelta( $sql );
    }


    /**
     * @see TableInterface
     */
    public function getTableName(){
        return $this->tableName;
    }

    /**
     * @see TableInterface
     */
    public function getFullTableName(){
        global $wpdb;

        return $wpdb->prefix . $this->tableName;
    }

    /**
     * @see TableInterface
     */
    public function getAlias(){
        return $this->alias;
    }

    /**
     * @see TableInterface
     */
    public function getOffsetLimitByPage($page, $limit = 20){
        if($page === null){
            return "";
        }

        $offset = ($page * $limit) - $limit;

        return "LIMIT {$limit} OFFSET {$offset}";
    }

    /**
     * @see TableInterface
     * @param array $fields
     * @param array $where
     * @return void
     */
    public function update($fields, $where){

        global $wpdb;

        $table        = $this->getFullTableName();
        $formatFields = $this->getFormatFields($fields);
        $whereFields  = $this->getFormatFields($where);
        
        $wpdb->update( 
            $table, 
            $fields, 
            $where, 
            $formatFields, 
            $whereFields
        );
    }

    public function delete($id){
        global $wpdb;

        $table        = $this->getFullTableName();

        $wpdb->delete( 
            $table, 
            array( 
                'id'    => $id,
            ), 
            array( 
                '%d'
            ) 
        );
    }

}
