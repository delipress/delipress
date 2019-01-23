<?php

namespace Delipress\WordPress\Traits\Optin;

use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Models\OptinModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait OptinTrait {

    /**
     *
     * @param string $value
     * @return array
     */
    public function checkOptinType($value){
        $optins = OptinHelper::getListOptins();
        
        if(empty($value)){
            $this->missingParameters["check_optin_type"] = 1;
        }

        if(!array_key_exists($value, $optins)){
            $this->missingParameters["check_optin_type"] = 1;
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        return $value;
    }

    /**
     * 
     * @param string $value
     * @return void
     */
    public function checkOptinExist($value){

        $optin = get_post( $value );

        if($optin && $optin->post_type === PostTypeHelper::CPT_OPTINFORMS){
            $model = new OptinModel();
            $model->setOptin($optin);
            return $model;
        }

        $this->missingParameters["check_optin_exist"] = 1;
        
        return null;
        
    }

    /**
     *
     * @param array $values
     * @return array
     */
    public function checkOptinsExist($values){
        foreach($values as $key => $value){
            $values[$key] = $this->checkOptinExist($value);
        }

        return $values;
    }
 

}

