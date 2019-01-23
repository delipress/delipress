<?php

namespace DeliSkypress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerInterface;

interface HooksInterface extends ContainerServiceInterface {
    public function hooks();
}