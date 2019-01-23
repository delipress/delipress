<?php

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Services\Listing\ListServices;


$provider = ProviderHelper::getProviderFromUrl();

$lists = $this->listServices->getLists(array("limit" => -1));
?>

 <div class="delipress__wizard__modal__content" id="providerSend">

    <h1><?php esc_html_e("Email provider connected", "delipress"); ?></h1>

    <div>
        <div class="delipress__task delipress__task--done">
            <span class="dashicons dashicons-yes"></span>
        </div>
        <?php printf(
            __("You successfully connected to your <strong>%s</strong> account.", "delipress"),
            $provider["label"]
        ); ?>
        <?php if(!empty($lists)): ?>
            <h3><?php _e('Synchronized lists', 'delipress'); ?></h3>
            <ul class="delipress__task-list">
            <?php foreach($lists as $key => $list): ?>

                <li>
                    <span><?php echo esc_html($list->getName()); ?></span>
                </li>

            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <p class="delipress__center">
        <a href="<?php echo $this->wizardServices->getPageWizard((int)$this->currentStep+1); ?>" class="delipress__button delipress__button--save">
            <?php esc_html_e("Next step", "delipress") ?>
            <span class="dashicons dashicons-arrow-right-alt"></span>
        </a>
    </p>


</div> <!-- delipress__wizard__modal__content -->
