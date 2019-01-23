<?php

use Delipress\WordPress\Helpers\ProviderHelper;

$provider = ProviderHelper::getProviderFromUrl();

?>

<div class="delipress__wizard__modal__content" id="providerSent">

    <h1><?php _e("Email provider", "delipress"); ?></h1>

    <div>
        <div class="delipress__task delipress__task--done">
            <span class="dashicons dashicons-yes"></span>
        </div>
        <?php printf(
            __("You successfully connected to your <strong>%s</strong> account.", "delipress"),
            $provider["label"]
        ); ?>
    </div>

    <div>
        <div class="delipress__task delipress__task--done">
            <span class="dashicons dashicons-yes"></span>
        </div>
        <?php _e("Test email sent! Check your inbox.", "delipress"); ?>
    </div>

    <p class="delipress__center">
        <a href="<?php echo $this->wizardServices->getPageWizard((int)$this->currentStep+1); ?>" class="delipress__button delipress__button--soft">
            <?php _e($steps[1]["label"]); ?>
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </a>
    </p>

</div> <!-- delipress__wizard__modal__content -->
