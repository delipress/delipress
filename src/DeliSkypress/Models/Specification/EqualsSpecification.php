<?php

namespace DeliSkypress\Models\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\Specification\AbstractSpecification;

class EqualsSpecification extends AbstractSpecification
{
     public function __construct($string){
          $this->string = $string;
     }

    public function isSatisfedBy($item){     
        return $this->string === $item;
    }
}