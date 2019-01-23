<?php

namespace DeliSkypress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

interface TableInterface {
    public function getSelects($args);
    public function createTable();
    public function getQuery($queries);
    public function getFrom();
    public function getAlias();
    public function getTableName();
    public function getFullTableName();
    public function update($fields, $where);
    public function getFormatFields($args);
    public function getOffsetLimitByPage($page, $limit = 20);
}