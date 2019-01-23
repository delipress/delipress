<?php

namespace DeliSkypress\Factory\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\SpecificationFactoryInterface;
use DeliSkypress\Models\Specification\ContainsSpecification;
use DeliSkypress\Models\Specification\EqualsSpecification;
use DeliSkypress\Models\Specification\NotX;
use DeliSkypress\Models\Specification\OrX;
use DeliSkypress\Models\Specification\AndX;

class SpecificationFactory implements SpecificationFactoryInterface
{   
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
     * @return array
     */
    public function constructSpecification($data){

        $arraySpec = array();

        foreach ($data as $keyOr => $valueOr) {
            foreach ($valueOr as $keyAnd => $valueAnd) {
                $arraySpec[$keyOr][] = $this->getSpecificationFromConditionTest($valueAnd['condition_test'], $valueAnd['value']);
            }
        }

        
        $specification = $this->constructOrSpecification($arraySpec);

        return $specification;
    }

    /**
     *
     * @param array $arraySpec
     * @return array
     */
    protected function constructOrSpecification($arraySpec){
        $specification = null;

        if(count($arraySpec) > 1){
            foreach ($arraySpec as $key => $value) {
                if (array_key_exists($key+1, $arraySpec)) {
                    $specification = ($specification === null) ? 
                                      new OrX($this->constructAndSpecification($value), $this->constructAndSpecification($arraySpec[$key+1])) : 
                                      new OrX($specification, $this->constructAndSpecification($arraySpec[$key+1]));
                }
                else{
                    $specification = new OrX($specification, $this->constructAndSpecification($value));
                }
            }
        }
        else{
            $arraySpec     = array_values($arraySpec);
            $specification = $this->constructAndSpecification($arraySpec[0]);
        }

        return $specification;
    }

    /**
     *
     * @param array $andSpecs
     * @return array
     */
    protected function constructAndSpecification($andSpecs){
        $andSpecification = null;

        if(!empty($andSpecs) && count($andSpecs) === 1){
            return $andSpecs[0];
        }
        else{
            foreach ($andSpecs as $key => $value) {
                if (array_key_exists($key+1, $andSpecs)) {
                    $andSpecification = ($andSpecification === null) ? 
                                        new AndX($value, $andSpecs[$key+1]) : 
                                        new AndX($andSpecification, $andSpecs[$key+1]);       
                }
            }
        }

        return $andSpecification;
    }

    /**
     *
     * @param string $conditionTest
     * @param any $value
     * @return void
     */
    public function getSpecificationFromConditionTest($conditionTest, $value){

        if(!array_key_exists($conditionTest, $this->conditionTest)){
            return false;
        }

        $stringChoice = new ContainsSpecification($conditionTest);
        $spec         = "";

        if($stringChoice->isSatisfedBy("not")){
            switch($conditionTest){
                case "not_equals":
                    $spec = new NotX(new EqualsSpecification($value));
                    break;
                case "not_contains":
                    $spec = new NotX(new ContainsSpecification($value));
                    break;
            }
        }
        else{
            switch($conditionTest){
                case "equals":
                    $spec = new EqualsSpecification($value);
                    break;
                case "contains":
                    $spec = new ContainsSpecification($value);
                    break;
            }
        }

        return apply_filters("_specification_factory_from_condition_test", $spec, $conditionTest, $value);
    }
}