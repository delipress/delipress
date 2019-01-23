<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptinHelper;

$formAuthorize = OptinHelper::getFormPostAuthorize($this->optin->getType());

?>

<h1><?php esc_html_e("Opt-In form", "delipress"); ?> <small><?php esc_html_e("Customize its behavior", "delipress") ?></small></h1>
<p><?php esc_html_e('Last step, customize the behavior of your Opt-In form, i.e when and where to display it on your website.', 'delipress'); ?></p>


<?php if(!empty($formAuthorize)): ?>

<div class="delipress__settings delipress__settings--behavior">
    <?php
    foreach($formAuthorize as $key => $value):
        if(file_exists(sprintf(__DIR__ . "/forms/_%s.php", $key) )){
            include_once sprintf(__DIR__ . "/forms/_%s.php", $key) ;
        }
    endforeach;
    ?>

</div> <!-- delipress__settings -->

<?php
else:
    wp_redirect(admin_url());
    exit;
endif;?>

<footer class="delipress__content__bottom">
    <button type="submit" class="delipress__button delipress__button--save delipress-js-step-submit-prevent" data-next-step="3">
        <?php esc_html_e('Save Opt-In form', 'delipress'); ?>
    </button>
</footer>
