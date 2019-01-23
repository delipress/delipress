<?php

namespace Delipress\WordPress\Models\InterfaceModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


interface ListInterface {
    public function getId();
    public function getName();
}