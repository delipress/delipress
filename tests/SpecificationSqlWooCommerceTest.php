<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/AbstractSpecificationSql.php";

/**
 * @covers List Dynamic
 */
final class SpecificationSqlWooCommerceFactoryTest extends AbstractSpecificationSql
{

    public function testFirstNameWooCommerce(){
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'billing_first_name', 
                        'operator' => 'contains', 
                        'value'    => 'thomas'
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
        ";

        $desiredResult = $this->cleanString($desiredResult);
        $data          = $this->cleanString($data);

        $this->assertEquals(
            $desiredResult,
            $data
        );
    }

    public function testLastNameWooCommerce(){
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'billing_last_name', 
                        'operator' => 'contains', 
                        'value'    => 'thomas'
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
            AND ( um0.meta_key = 'billing_last_name' AND um0.meta_value LIKE '%thomas%' ) 
            GROUP BY s.id
        ";

        $desiredResult = $this->cleanString($desiredResult);
        $data          = $this->cleanString($data);

        $this->assertEquals(
            $desiredResult,
            $data
        );
    }

    public function testEmailWooCommerce(){
        $data = $this->specificationServices->getSpecificationFactory()->buildSqlQuery(
            array( 
                array( 
                    array( 
                        'meta_id'  => 'billing_email', 
                        'operator' => 'contains', 
                        'value'    => 'thomas'
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
            AND ( um0.meta_key = 'billing_email' AND um0.meta_value LIKE '%thomas%' ) 
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
