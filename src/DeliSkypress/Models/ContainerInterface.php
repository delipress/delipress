<?php

namespace DeliSkypress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerServiceInterface;

interface ContainerInterface {
    public function setServices($array = array());
    public function setService(ContainerServiceInterface $service);
    public function getService($key);
    public function getServices();
}