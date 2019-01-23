<?php

namespace Delipress\WordPress\Traits\Provider;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;

trait ProviderTrait {

    /**
     * 
     * @param string $value
     * @return string
     */
    public function checkMetaProvider($value){

        $providers = ProviderHelper::getListProviders();

        if(!array_key_exists($value, $providers)){
            $this->missingParameters["check_meta_provider"] = 1;
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        return $value;
    }
 

}

