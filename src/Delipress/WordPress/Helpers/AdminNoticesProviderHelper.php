<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractAdminNotices;
use Delipress\WordPress\Helpers\ProviderHelper;

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AdminNoticesProviderHelper extends AbstractAdminNotices {

    const ERROR_NOTICES   = "_delipress_error_provider_notices";
    const SUCCESS_NOTICES = "_delipress_success_provier_notices";
    const INFO_NOTICES    = "_delipress_info_provider_notices";

    /**
     *
     * @param string $const
     * @return void
     */
    public static function displayError($const){

        $errors = self::getErrorNotices();
        if(!array_key_exists($const, $errors) ){
            return "";
        }
        $logoSrc = "";
        $classBg = "";
        switch($errors[$const]["provider"]){
            case ProviderHelper::MAILCHIMP:
                $logoSrc = DELIPRESS_PATH_PUBLIC_IMG . "/providers/mailchimp-horizontal.png";
                $classBg = "delipress__notice--mailchimp";
                break;
            case ProviderHelper::MAILJET:
                $logoSrc = DELIPRESS_PATH_PUBLIC_IMG . "/providers/mailjet-black.png";
                $classBg =  "delipress__notice--mailjet";
                break;
            case ProviderHelper::SENDGRID:
                $logoSrc = DELIPRESS_PATH_PUBLIC_IMG . "/providers/sendgrid.png";
                $classBg = "delipress__notice--sendgrid";
                break;
        }

        ?>
            <div class="delipress__notice delipress__notice--provider <?php echo $classBg ?>">
                <div class="delipress__notice__esp">
                    <?php if(!empty($logoSrc) ): ?>
                        <img src="<?php echo $logoSrc ?>" />
                    <?php endif; ?>
                    <span><?php _e('Ooops! An error occured with your email provider. Here is what we received:', 'delipress') ?></span>

                    <a href="#" class="delipress__notice__dismiss js-delipress-notice-provider-close">×</a>
                </div>
                <ul>
                    <li><?php echo $errors[$const]["message"]; ?></li>
                </ul>
            </div>
        <?php
    }

}
