<?php

require_once __DIR__ . "/AbstractSpecificationSql.php";

use PHPUnit\Framework\TestCase;


/**
 * @covers List Dynamic
 */
final class SpecificationSqlFactoryTest extends AbstractSpecificationSql
{

    public function testUserRegisteredMeta()
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

    public function testMultipleCondition()
    {
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'billing_first_name', 
                        'operator' => 'contains', 
                        'value'    => 'thomas'
                    )
                ),
                array(
                    array(
                        'meta_id'  => 1, 
                        'operator' => 'not_equals_date', 
                        'value'    => '2017-10-05'
                    )
                )
            )
        );

        global $wpdb;


        $desiredResult = "SELECT s.id 
            FROM {$wpdb->prefix}delipress_subscriber s 
            INNER JOIN {$wpdb->prefix}users u ON s.email = u.user_email 
            INNER JOIN {$wpdb->prefix}usermeta um0 ON u.ID = um0.user_id 
            WHERE 1=1 
            AND ( um0.meta_key = 'billing_first_name' AND um0.meta_value LIKE '%thomas%' )
            GROUP BY s.id 
            UNION 
            SELECT s.id 
            FROM {$wpdb->prefix}delipress_subscriber s 
            INNER JOIN {$wpdb->prefix}delipress_subscriber_meta sm0 ON s.id = sm0.subscriber_id
            WHERE 1=1 
            AND ( sm0.meta_id = 1 AND sm0.meta_value != '2017-10-05' ) 
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
