<?php

namespace Delipress\WordPress\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\SpecificationFactoryInterface;

use Delipress\WordPress\Services\Table\TableServices;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;
use Delipress\WordPress\Specification\SqlWooCommerceTrait;
use Delipress\WordPress\Specification\SqlWordPressUserTrait;
use Delipress\WordPress\Specification\SqlWordPressUserMetaTrait;

class SpecificationSqlFactory implements SpecificationFactoryInterface
{       

    use SqlWooCommerceTrait;
    use SqlWordPressUserTrait;
    use SqlWordPressUserMetaTrait;

    protected $alreadyInnerJoinWordPressUserMeta = false;

    protected $innerJoins          = array();
    
    protected $wheres              = array();
    
    /**
     *
     * @param string $operator
     * @return string
     */
    public function getOperatorSql($operator){
        switch($operator){
            case "contains":
                return "LIKE";
            case "not_contains":
                return "NOT LIKE";
            case "not_equals":
            case "not_equals_date":
                return "!=";
            case "equals": 
            case "equals_date":
                return "=";
            case "greater":
            case "greater_date":
                return ">";
            case "less":
            case "less_date":
                return "<";
            case "lesser_than":
            case "lesser_than_date":
                return "<=";
            case "greater_than":
            case "greater_than_date":
                return ">=";
        }
    }

    /**
     *
     * @param string $operator
     * @param string $value
     * @return any
     */
    public function castValueSql($operator, $value){
        switch($operator){
            case "greater":
            case "less":
            case "lesser_than":
            case "greater_than":
                return (int) $value;
            case "equals_date":
            case "greater_date":
            case "less_date":
            case "greater_than_date":
            case "lesser_than_date":
            case "not_equals_date":
                try{
                    $date = new \DateTime($value);
                    return $date->format("Y-m-d");
                }
                catch(\Exception $e){
                    return null;
                }
            default:
                return $value;
        }
    }

    /**
     *
     * @param array $configurationSelectTable
     * @return SpecificationSqlFactory
     */
    public function setConfigurationSelectTable($configurationSelectTable){
        $this->configurationSelectTable = $configurationSelectTable;
        return $this;
    }

    /**
     *
     * @param array $conditionTest
     * @return SpecificationFactory
     */
    public function setConditionTest($conditionTest){
        $this->conditionTest = $conditionTest;
        return $this;
    }

    /**
     *
     * @param array $data
     * @example $data array(
     *      array(
     *          array(
     *              "meta_key" => "first_name",
     *              "operator" => "equals",
     *              "value"    => "John"
     *          ),
     *          ...
     *      ),
     *      ...
     * )
     * @return array
     */
    public function constructSpecification($data){

        $sqlQuery = $this->buildSqlQuery($data);

        if($sqlQuery === null){
            return array(
                "success" => true,
                "results" => array()
            );
        }
        global $wpdb;

        $result = $wpdb->get_results($sqlQuery, ARRAY_A);

        return array(
            "success" => true,
            "results" => $result
        );
    }

    /**
     *
     * @param array $valueOr
     * @return bool
     */
    protected function authorizeQueryOr($valueOr){
        $authorize = false;
        
        if(empty($valueOr)){
            return $authorize;
        }

        foreach ($valueOr as $keyAnd => $valueAnd) {
            if(!empty($valueAnd["value"]) ){
                $authorize = true;
                break;
            }
        }
        
        return $authorize;
    }
        
    /**
     *
     * @param array $data
     * @return void
     */
    public function buildSqlQuery($data){

        $sql = "";  
        $i       = 0;
        $totalOr = count($data);
        foreach ($data as $keyOr => $valueOr) {

            if(!$this->authorizeQueryOr($valueOr)){
                continue;
            }

            if($i < $totalOr && $i > 0){
                $sql .= " UNION ";
            }
            
            $i++;

            $sql .= $this->constructOrQuery($valueOr);

        }

        if(empty($sql)){
            return null;
        }
        
        return $sql;
    }

    /**
     *
     * @param string $key
     * @param array $valueAnd
     * @return void
     */
    protected function createInnerJoin($key, $valueAnd){
        $keysMetasWooCommerce = SubscriberMetaHelper::getKeysMetaWooCommerce();
        $keysWordPressUser    = SubscriberMetaHelper::getKeysWordPressUserMeta();

        if(in_array($valueAnd["meta_id"], $keysWordPressUser) || in_array($valueAnd["meta_id"], $keysMetasWooCommerce)){
            $this->createInnerJoinWordPressUserMeta($key);
            return;
        }

    }

    
    /**
     * @return void
     */
    protected function createInnerJoinWordPressUserMeta($key){

        global $wpdb;

        $aliasInnerUser = "um" . $key;
        if(!$this->alreadyInnerJoinWordPressUserMeta){
            $this->innerJoins[] = "INNER JOIN {$wpdb->usermeta} {$aliasInnerUser} ON u.ID = {$aliasInnerUser}.user_id ";
            $this->alreadyInnerJoinWordPressUserMeta = true;
        }

    }


    /**
     *
     * @param array $valueOr
     * @return string
     */
    protected function constructOrQuery($valueOr){
        
        global $wpdb; 

        $this->innerJoins                                 = array();
        $this->wheres                                     = array();
        $this->alreadyInnerJoinWordPressUserMeta          = false;

        $sql  = "SELECT u.ID "; 

        $sql .= "FROM {$wpdb->users} u ";

        foreach ($valueOr as $keyAnd => $valueAnd) {
            $this->createInnerJoin($keyAnd, $valueAnd);
            $this->constructAndQuery($keyAnd, $valueAnd);
        }

        $sql .= implode(" ",$this->innerJoins);
        $sql .= " WHERE 1=1 ";
        $sql .= implode(" ",$this->wheres);

        $sql .= "GROUP BY u.ID ";

        return $sql;
    }


    /**
     *
     * @param key $valueAnd
     * @param array $valueAnd
     * @return void
     */
    protected function constructAndQuery($key, $valueAnd){

        $keysMetasWooCommerce = SubscriberMetaHelper::getKeysMetaWooCommerce();
        $keysWordPressUser    = SubscriberMetaHelper::getKeysWordPressUserMeta();
        

        if(empty($valueAnd["value"])){
            return;
        }

        if(in_array($valueAnd["meta_id"], $keysMetasWooCommerce)){
            $this->constructAndQueryWooCommerce($key, $valueAnd);
            return;
        }

        if(in_array($valueAnd["meta_id"], $keysWordPressUser)){
            $this->constructAndQueryWordPressUserMeta($key, $valueAnd);
            return;
        }
  
        
        $this->constructAndQueryWordPressUser($key, $valueAnd);
        
    }


}

