<?php

namespace DeliSkypress\Models\Strategy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

interface StrategyInterface {
    public function execute();
}