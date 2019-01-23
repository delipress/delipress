<?php

    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

    use Delipress\WordPress\Helpers\ActionHelper;
?>

<div id="delipress-react-selector"></div>

<script type="text/javascript">
    DELIPRESS_ENV             = "<?php echo esc_js( (DELIPRESS_LOGS) ? "DEV" : "PROD") ?>";
    DELIPRESS_API_BASE_URL    = "<?php echo esc_js(admin_url() );                ?>"
    DELIPRESS_CAMPAIGN_ID     = "<?php echo esc_js($this->campaign->getId() ); ?>"
    WPNONCE_AJAX              = "<?php echo esc_js(wp_create_nonce(ActionHelper::REACT_AJAX) );  ?>"
    DELIPRESS_PATH_PUBLIC_IMG = "<?php echo esc_js(DELIPRESS_PATH_PUBLIC_IMG) ?>"
    require('javascripts/react/modules/preview/initialize');
</script>


