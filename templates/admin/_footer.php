<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>
<?php

$licenseStatusValid  = $this->optionServices->isValidLicense();
$supportUrl = "https://wordpress.org/support/plugin/delipress";
if($licenseStatusValid){
    $supportUrl = "https://delipress.io/support";
}

$allowTracking = get_option('__delipress__allow_tracking');

$disabledBeacon = apply_filters(DELIPRESS_SLUG . "_disabled_beacon", false);

?>


<footer class="delipress__footer">
    <p class="delipress__footer__block">
        <?php esc_html_e('Thanks for using DeliPress.', 'delipress'); ?>
        <?php esc_html_e('Need help?', 'delipress'); ?>
        <a href="<?php echo delipress_get_documentation_url(); ?>" target="_blank"><?php esc_html_e('Read the documentation', 'delipress'); ?></a>
        <?php esc_html_e('or', 'delipress'); ?>
        <a href="<?php echo $supportUrl; ?>" target="_blank"><?php esc_html_e('get support', 'delipress'); ?> </a>
    </p>
    <p class="delipress__footer__block">
        <?php esc_html_e('Do you like DeliPress?', 'delipress'); ?>
        <a href="https://wordpress.org/support/plugin/delipress/reviews/#new-post" target="_blank"><?php esc_html_e('Give us a 5-star review !', 'delipress'); ?>
            <span class="delipress__stars">
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
            </span>
        </a>
    </p>
</footer>

<?php if($allowTracking): ?>

<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.delipress.io/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->

<?php endif; ?>

<?php if($licenseStatusValid && !$disabledBeacon): ?>

<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={docs:{enabled:!1,baseUrl:""},contact:{enabled:!0,formId:"1fd9052d-8e50-11e7-b5b5-0ec85169275a"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>

<?php endif; ?>
