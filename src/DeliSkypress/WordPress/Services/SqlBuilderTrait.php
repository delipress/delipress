<?php

namespace DeliSkypress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerInterface;

trait SqlBuilderTrait {

    protected $alias = "";
    
    protected $tableName = "";

    /**
     * Quote a string that is used as an identifier
     * (table names, column names etc). This method can
     * also deal with dot-separated identifiers eg table.column
     */
    protected function quoteOneIdentifier($identifier) {
        $parts = explode('$-$', $identifier);
        $parts = array_map(array($this, 'quoteIdentifierPart'), $parts);
        return join('$-$', $parts);
    }

    /**
     * Quote a string that is used as an identifier
     * (table names, column names etc) or an array containing
     * multiple identifiers. This method can also deal with
     * dot-separated identifiers eg table.column
     */
    protected function quoteIdentifier($identifier) {
        if (is_array($identifier)) {
            $result = array_map(array($this, 'quoteOneIdentifier'), $identifier);
            return join(', ', $result);
        } else {
            return $this->quoteOneIdentifier($identifier);
        }
    }

    /**
     * This method performs the actual quoting of a single
     * part of an identifier, using the identifier quote
     * character specified in the config (or autodetected).
     */
    protected function quoteIdentifierPart($part) {
        if ($part === '*') {
            return $part;
        }

        $quote_character = apply_filters(DELIPRESS_SLUG . "_identifier_quote_character", "'");

        return $quote_character .
                str_replace($quote_character,
                            $quote_character . $quote_character,
                            $part
                ) . $quote_character;
    }

    /**
     * Override this function !
     * 
     * @param array $args
     * @return array
     */
    public function getFormatFields($args){
        return array();
    }

    /**
     * Prepare select SQL
     * @see TableInterface
     * @param array $args
     * @return string
     */
    public function getSelects($args){
        $sql = "";

        if($args["selects"] === "*"){
            $sql .= "{$this->alias}.*";
        }
        else if(is_array($args["selects"])){
            $sql .= "{$this->alias}." . implode(", {$this->alias}.", $args["selects"]);
        }

        return $sql;
    }

    /**
     * @see TableInterface
     */
    public function getFrom(){
        global $wpdb;
        
        return " FROM {$wpdb->prefix}{$this->tableName} {$this->alias} ";
    }


    /**
     * @see TableInterface
     */
    public function getQuery($queries){
        $sql = "";

        foreach($queries as $key => $query){
            if(!empty($query["value"])){
                switch($query["operator"]){
                    default:
                        if(!isset($query["type"])){
                            $query["type"] = "string";
                        }
                        switch($query["type"]){
                            case "string":
                            default:
                                $sql .= "AND {$this->alias}.{$query["field"]} = '{$query["value"]}' ";  
                                break;
                            case "datetime":
                                $sql .= "AND {$this->alias}.{$query["field"]} {$query["operator"]} '{$query["value"]}' ";  
                                break;
                            case "int":
                                $sql .= "AND {$this->alias}.{$query["field"]} {$query["operator"]} {$query["value"]} ";  
                                break;
                        }
                        break;
                    case "IN":
                        $valuesIn = $this->quoteIdentifier($query["value"]);
                        $sql .= "AND {$this->alias}.{$query["field"]} IN ({$valuesIn}) ";
                        break;
                    case "IS":
                        $sql .= "AND {$this->alias}.{$query["field"]} IS {$query["value"]} ";
                        break;
                }
            }
        }

        return $sql;
    }

}
