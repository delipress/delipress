<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Optin\BaseOptin;

use Delipress\WordPress\Helpers\OptinHelper;

/**
 * FlyIn
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class FlyIn extends BaseOptin {

    protected $typeOptin = OptinHelper::FLY;

    /**
     * @see BaseOptin
     *
     * @return array
     */
    public function getOptins(){
        return  $this->optinServices->getFlyOptins();
    }
    

}
