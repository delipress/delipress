<?php

namespace DeliSkypress\WordPress\Actions;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerServiceInterface;
use DeliSkypress\Models\ContainerInterface;

abstract class AbstractHook implements ContainerServiceInterface
{
    public function setContainerServices(ContainerInterface $containerServices){}
    public function preHooks(){}

}