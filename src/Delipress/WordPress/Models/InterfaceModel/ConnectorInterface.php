<?php

namespace Delipress\WordPress\Models\InterfaceModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;

interface ConnectorInterface {
    public function prepareConnector();
    public function removeConnector($listId);
}