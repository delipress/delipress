<?php

namespace Delipress\WordPress\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Services\Table\TableServices;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

trait SqlWordPressUserTrait
{       

    /**
     *
     * @param string $key
     * @param array $valueAnd
     * @return void
     */
    public function constructAndQueryWordPressUser($key, $valueAnd){
        $aliasInner = "u";
        
        $value    = $this->castValueSql($valueAnd["operator"], $valueAnd["value"]);
        $operator = $this->getOperatorSql($valueAnd["operator"]);

        switch($valueAnd["operator"]){
            case "contains":
            case "not_contains":
                $this->wheres[] = "AND (
                    {$aliasInner}.{$valueAnd["meta_id"]} {$operator} '%{$value}%'
                ) ";
                break;
            case "equals": 
            case "equals_date":
            case "greater_date":
            case "less_date":
            case "greater_than_date":
            case "lesser_than_date":
            case "not_equals":
            case "not_equals_date":
                
                $this->wheres[] = "AND (
                    {$aliasInner}.{$valueAnd["meta_id"]} {$operator} '{$value}'
                ) ";
                break;
            case "greater":
            case "greater_than":
            case "less":
            case "lesser_than":
                $this->wheres[] = "AND (
                    {$aliasInner}.{$valueAnd["meta_id"]} {$operator} {$value}
                ) ";
                break;

        }
    }
        
        
}
    