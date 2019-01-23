<?php

require_once __DIR__ . "/AbstractSpecificationSql.php";

use PHPUnit\Framework\TestCase;


/**
 * @covers List Dynamic
 */
final class SpecificationSqlOperatorTest extends AbstractSpecificationSql
{

    public function testGreaterThanDate()
    {
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'user_registered', 
                        'operator' => 'greater_than_date', 
                        'value'    => '09-09-2017'
                    )
                )
            )
        );

        global $wpdb;


        $desiredResult = "SELECT s.id 
            FROM {$wpdb->prefix}delipress_subscriber s 
            INNER JOIN {$wpdb->prefix}users u ON s.email = u.user_email  
            WHERE 1=1 
            AND ( u.user_registered >= '2017-09-09' ) 
            GROUP BY s.id
        ";

        $desiredResult = $this->cleanString($desiredResult);
        $data          = $this->cleanString($data);

        $this->assertEquals(
            $desiredResult,
            $data
        );
    }
    
    public function testGreaterDate()
    {
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'user_registered', 
                        'operator' => 'greater_date', 
                        'value'    => '09-09-2017'
                    )
                )
            )
        );

        global $wpdb;


        $desiredResult = "SELECT s.id 
            FROM {$wpdb->prefix}delipress_subscriber s 
            INNER JOIN {$wpdb->prefix}users u ON s.email = u.user_email  
            WHERE 1=1 
            AND ( u.user_registered > '2017-09-09' ) 
            GROUP BY s.id
        ";

        $desiredResult = $this->cleanString($desiredResult);
        $data          = $this->cleanString($data);

        $this->assertEquals(
            $desiredResult,
            $data
        );
    }
    
    public function testGreaterThan()
    {
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'user_registered', 
                        'operator' => 'greater_than', 
                        'value'    => 1
                    )
                )
            )
        );

        global $wpdb;


        $desiredResult = "SELECT s.id 
            FROM {$wpdb->prefix}delipress_subscriber s 
            INNER JOIN {$wpdb->prefix}users u ON s.email = u.user_email  
            WHERE 1=1 
            AND ( u.user_registered >= 1 ) 
            GROUP BY s.id
        ";

        $desiredResult = $this->cleanString($desiredResult);
        $data          = $this->cleanString($data);

        $this->assertEquals(
            $desiredResult,
            $data
        );
    }


}
