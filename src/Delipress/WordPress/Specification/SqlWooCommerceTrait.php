<?php

namespace Delipress\WordPress\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Services\Table\TableServices;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

trait SqlWooCommerceTrait
{       

    /**
     *
     * @param string $key
     * @param array $valueAnd
     * @return void
     */
    public function constructAndQueryWooCommerce($key, $valueAnd){
        $aliasInner = "um" . $key;

        $value    = $this->castValueSql($valueAnd["operator"], $valueAnd["value"]);
        $operator = $this->getOperatorSql($valueAnd["operator"]);

        switch($valueAnd["operator"]){
            case "contains":
            case "not_contains":
                $this->wheres[] = "AND (
                    {$aliasInner}.meta_key = '{$valueAnd["meta_id"]}'
                    AND {$aliasInner}.meta_value {$operator} '%{$value}%'
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
                    {$aliasInner}.meta_key = '{$valueAnd["meta_id"]}'
                    AND {$aliasInner}.meta_value {$operator} '{$value}'
                ) ";
                break;
                
            case "greater":
            case "greater_than":
            case "less":
            case "lesser_than":
                
                $this->wheres[] = "AND (
                    {$aliasInner}.meta_key = '{$valueAnd["meta_id"]}'
                    AND {$aliasInner}.meta_value {$operator} {$value}
                ) ";
                break;
        }
    }


}
