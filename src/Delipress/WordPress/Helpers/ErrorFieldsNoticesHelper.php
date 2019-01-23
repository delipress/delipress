<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractAdminNotices;

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class ErrorFieldsNoticesHelper extends AbstractAdminNotices {

    const ERROR_NOTICES   = "_delipress_error_fields_notices";
    const SUCCESS_NOTICES = "_delipress_success_fields_notices";
    const INFO_NOTICES    = "_delipress_info_fields_notices";

    /**
     * 
     * @param string $const
     * @return void
     */
    public static function displayError($const){
        $errors = self::getErrorNotices();
        if(array_key_exists($const, $errors) ){
            ?>
            <div class="delipress__settings__item__error">
                <span class="dashicons dashicons-warning"></span>
                <span>
                    <?php echo esc_html($errors[$const]["message"]); ?>
                </span>
            </div>
            <?php
        }   
    }

    public static function hasError($const){
        $errors = self::getErrorNotices();
        if(!array_key_exists($const, $errors) ){
            return false;
        }

        return true;
    }

}









