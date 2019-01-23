<?php
    
    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
    
    use Delipress\WordPress\Helpers\ActionHelper;
?>

    <div id="delipress-react-selector"></div>

    <script>
        DELIPRESS_ENV          = "<?php echo esc_js((DELIPRESS_LOGS) ? "DEV" : "PROD") ?>";
        DELIPRESS_API_BASE_URL = "<?php echo esc_js(admin_url() );                ?>"
        DELIPRESS_OPTIN_ID     = "<?php echo esc_js($this->optin->getId() );                   ?>"
        WPNONCE_AJAX           = "<?php echo esc_js(wp_create_nonce(ActionHelper::REACT_AJAX) );  ?>"
        require('javascripts/react/modules/optin/initialize');
    </script>

</main> <!-- delipress__content -->
