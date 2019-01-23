<?php

namespace DeliSkypress\Models\Strategy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

interface CsvStrategyInterface extends StrategyInterface {
    public function setDelimiter($delimiter);
}