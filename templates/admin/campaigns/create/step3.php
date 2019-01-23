<?php

    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

    use Delipress\WordPress\Helpers\ActionHelper;

    $licenseStatusValid  = $this->optionServices->isValidLicense(); 
?>

    <div class="delipress__responsive-warning">
        <?php esc_html_e('Sorry! The builder isn’t responsive (yet). You need to be on a larger screen to use it properly.', 'delipress') ?>
        <button class="delipress__button delipress__button--soft js-toggle-responsive-warning">
            <?php esc_html_e('I still want to use it', 'delipress') ?>
        </button>
    </div>
    <div id="delipress-react-selector"></div>

    <script type="text/javascript">
        DELIPRESS_ENV          = "<?php echo esc_js( (DELIPRESS_LOGS) ? "DEV" : "PROD") ?>";
        DELIPRESS_API_BASE_URL = "<?php echo esc_js(admin_url() );                ?>"
        DELIPRESS_CAMPAIGN_ID  = "<?php echo esc_js($this->campaign->getId() );                   ?>"
        DELIPRESS_PATH_PUBLIC_IMG = "<?php echo esc_js(DELIPRESS_PATH_PUBLIC_IMG) ?>"
        WPNONCE_AJAX           = "<?php echo esc_js(wp_create_nonce(ActionHelper::REACT_AJAX) );  ?>";
        DELIPRESS_URLS         = {
            PREVIEW_CAMPAIGN : "<?php echo esc_js($this->campaignServices->getPreviewUrl($this->campaign) ); ?>"
        };

        <?php if($licenseStatusValid): ?>
            DELIPRESS_LICENSE_STATUS = true;
        <?php else: ?>
            DELIPRESS_LICENSE_STATUS = false;
        <?php endif; ?>

        DELIPRESS_PREMIUM_URL = "<?php echo delipress_get_url_premium(); ?>"

        <?php if(is_plugin_active("woocommerce/woocommerce.php")): ?>
            DELIPRESS_WOOCOMMERCE_ACTIVE = true;
        <?php else: ?>
            DELIPRESS_WOOCOMMERCE_ACTIVE = false;
        <?php endif; ?>

        (function(){
            require('javascripts/react/modules/campaign/initialize');

            jQuery('.js-toggle-responsive-warning').on('click', function(e){
                e.preventDefault()
                jQuery('.delipress__responsive-warning').fadeOut(300)
            })
        })()
    </script>

</main> <!-- delipress__content -->
