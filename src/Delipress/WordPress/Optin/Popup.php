<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Optin\BaseOptin;

use Delipress\WordPress\Helpers\OptinHelper;

/**
 * Popup
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Popup extends BaseOptin {

    protected $typeOptin = OptinHelper::POPUP;

    /**
     * @see BaseOptin
     *
     * @return array
     */
    public function getOptins(){
        return $this->optinServices->getPopups();
    }
    

}
