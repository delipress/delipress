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
abstract class AdminNoticesHelper extends AbstractAdminNotices {

    const ERROR_NOTICES   = "_delipress_error_notices";
    const SUCCESS_NOTICES = "_delipress_success_notices";
    const INFO_NOTICES    = "_delipress_info_notices";


    public static function hasError($const){
        $errors = self::getErrorNotices();
        if(!array_key_exists($const, $errors) ){
            return false;
        }

        return true;
    }

}









