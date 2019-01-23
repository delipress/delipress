<?php

namespace DeliSkypress\Services\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\SpecificationFactoryInterface;
use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Factory\Specification\SpecificationFactory;

class Specification implements ServiceInterface
{
    
    /**
     *
     * @param SpecificationFactoryInterface $factory
     */
    public function __construct(SpecificationFactoryInterface $factory = null){
        $this->specificationFactory = $factory;
    }

    /** 
     * @return array
     */
    public function getConditionTest(){
        return apply_filters('_specification_condition_test', array(
            "equals"            => __('Equals', 'delipress'),
            "equals_date"       => __('Equals (Date Format)', 'delipress'),
            "contains"          => __('Contains', 'delipress'),
            "not_equals"        => __('Not equals', 'delipress'), 
            "not_equals_date"   => __('Not equals (Date Format)', 'delipress'), 
            "not_contains"      => __('Not contains', 'delipress'),
            "greater"           => ">",
            "less"              => "<",
            "greater_than"      => ">=",
            "lesser_than"       => "<=",
            "greater_date"      => __("> (Date Format)","delipress"),
            "less_date"         => __("< (Date Format)","delipress"),
            "greater_than_date" => __(">= (Date Format)","delipress"),
            "lesser_than_date"  => __("<= (Date Format)","delipress"),
        ));
    }

    /**
     *
     * @return SpecificationFactory
     */
    public function getSpecificationFactory(){
        if (is_null($this->specificationFactory)) {
            $this->specificationFactory = $this->createDefaultSpecificationFactory();
        }

        return $this->specificationFactory;
    }

    /**
     *
     * @return SpecificationFactory
     */
    public function createDefaultSpecificationFactory(){
        $factory = new SpecificationFactory();
        $factory->setConditionTest($this->getConditionTest());
        return $factory;
    }
    
    /**
     *
     * @param SpecificationFactoryInterface $factory
     * @return Specification
     */
    public function setFactory(SpecificationFactoryInterface $factory){
        $this->specificationFactory = $factory;
        return $this;
    }

    /**
     *
     * @param array $data
     * @return Specification
     */
    public function constructSpecification($data){
        return $this->getSpecificationFactory()
                    ->constructSpecification($data);
    }
}