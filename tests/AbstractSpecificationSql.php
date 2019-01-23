<?php

use PHPUnit\Framework\TestCase;


use Delipress\WordPress\Specification\SpecificationSqlFactory;
use Delipress\WordPress\Services\Table\SubscriberTableServices;
use Delipress\WordPress\Services\Table\ListSubscriberTableServices;
use Delipress\WordPress\Services\Table\OptinStatsTableServices;
use Delipress\WordPress\Services\Table\MetaTableServices;
use Delipress\WordPress\Services\Table\SubscriberMetaTableServices;
use Delipress\WordPress\Services\Table\TableServices;

use DeliSkypress\Services\Specification\Specification;

/**
 * @covers List Dynamic
 */
abstract class AbstractSpecificationSql extends TestCase
{
    protected function cleanString($string){
        return trim(preg_replace('/\s+/', ' ', $string));        
    }

    protected function setUp(){
        $tableServices = new TableServices();
        $tableServices->setServices(
            array(
                "SubscriberTableServices" => new SubscriberTableServices(),
                "ListSubscriberTableServices" => new ListSubscriberTableServices(),
                "OptinStatsTableServices"     => new OptinStatsTableServices(),
                "MetaTableServices"           => new MetaTableServices(),
                "SubscriberMetaTableServices" => new SubscriberMetaTableServices(),
            )
        );
      
        $this->specificationServices = new Specification();
        $this->specificationServices->setFactory(new SpecificationSqlFactory());
    }

  
}
