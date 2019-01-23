<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

$provider = ProviderHelper::getProviderFromUrl();

$errors  = ErrorFieldsNoticesHelper::getErrorNotices();

?>

<div class="delipress__wizard__modal__content" id="providerConnect">

    <h1><?php esc_html_e("Email provider", "delipress"); ?></h1>

    <p class="delipress__wizard__modal__letsconnect">
        <?php esc_html_e("Let's connect to", 'delipress'); ?>
        <img src="<?php echo $provider["img_src"]; ?>" alt="<?php echo esc_attr($provider["label"]) ?>">
        <small>(<a href="<?php echo $this->wizardServices->getPageWizard(1); ?>"><?php esc_html_e("or change", "delipress") ?></a>)</small>
    </p>


    <?php do_action(DELIPRESS_SLUG . "_admin_notices_provider_error"); ?>

    <form action="<?php echo $this->wizardServices->getConnectProviderFormUrl(); ?>" method="post">

        <?php include_once __DIR__ . "/_connect_" . $provider["key"] . ".php"; ?>

        <input type="hidden" name="provider" value="<?php echo esc_attr($provider["key"]); ?>" />
    </form>


</div> <!-- delipress__wizard__modal__content -->
