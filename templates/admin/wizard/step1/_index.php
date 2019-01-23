<?php

use Delipress\WordPress\Services\Wizard\WizardServices;

set_transient(WizardServices::STEP_WIZARD, 1);

?>
<div class="delipress__wizard__modal__content" id="providerChoose">

    <h1><?php esc_html_e("Email provider", "delipress"); ?></h1>

    <p><?php esc_html_e("Standard web hosting is not meant to send massive emails. They will all end up as spams.", "delipress"); ?><br>
    <?php esc_html_e("That's why you need to setup an email provider.", "delipress"); ?> </p>
    <?php if($stickyProvider): ?>
        <p> <?php printf(esc_html__("The DeliPress Team recommends you start with a free %s plan.", "delipress"), $stickyProvider["label"]); ?></p>
    <?php endif; ?>

    <?php if($stickyProvider): ?>
        <div class="delipress__wizard__modal__partner">
            <a href="<?php echo $this->wizardServices->getPageWizard(1, array("provider" => $stickyProvider["key"]) ); ?>" class="delipress__wizard__modal__partner__logo">
                <img src="<?php echo $stickyProvider["img_src"]; ?>" title="<?php esc_html_e("Configure", "delipress"); ?>" alt="<?php echo esc_attr($stickyProvider["label"]); ?>">
            </a>
            <div class="delipress__wizard__modal__partner__content">
                <p class="delipress__wizard__modal__partner__content__title"><?php echo esc_attr($stickyProvider["label"]); ?></p>
                <p><?php echo $stickyProvider["hook"]; ?></p>
                <p class="delipress__wizard__modal__partner__content__links"><a href="<?php echo esc_attr($stickyProvider["url"]); ?>" target="_blank"><?php esc_html_e('Website', 'delipress'); ?></a> | <a href="<?php echo esc_attr($stickyProvider["url_pricing"]); ?>" target="_blank"><?php esc_html_e('Pricing', 'delipress'); ?></a> </p>
                <a href="<?php echo $this->wizardServices->getPageWizard(1, array("provider" => $stickyProvider["key"]) ); ?>" class="delipress__wizard__modal__partner__content__configure delipress__button delipress__button--small delipress__button--save"><?php esc_html_e('Configure', 'delipress'); ?></a>
            </div>
        </div>
    <?php endif; ?>

    <p><?php esc_html_e("Here are other providers supported by DeliPress:", "delipress"); ?></p>

    <div class="delipress__wizard__modal__providers">
        <?php
        foreach($providers as $key => $provider):
            $urlImg = ($provider["active"]) ? $this->wizardServices->getPageWizard(1, array("provider" => $provider["key"]) ) : $provider["url"];
        ?>
            <div class="delipress__wizard__modal__providers__item">
                <a href="<?php echo $urlImg; ?>" title="<?php esc_html_e("Configure", "delipress"); ?>" class="delipress__wizard__modal__providers__item__logo">
                    <img src="<?php echo $provider["img_src"]; ?>" alt="<?php echo esc_attr($provider["label"]); ?>">
                </a>
                <div class="delipress__wizard__modal__providers__item__links">
                    <?php if($provider["active"]): ?>
                        <div>
                            <a href="<?php echo esc_attr($provider["url"]); ?>"><?php esc_html_e("Website", "delipress"); ?></a> | <a href="<?php echo esc_attr($provider["url_pricing"]); ?>"><?php esc_html_e("Pricing", "delipress"); ?></a>
                        </div>
                        <a href="<?php echo $this->wizardServices->getPageWizard(1, array("provider" => $provider["key"]) ); ?>"><?php esc_html_e("Configure", "delipress"); ?></a>
                    <?php else: ?>
                        <div>
                            <?php esc_html_e("Coming soon", "delipress"); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php
        endforeach;
        ?>
    </div>

</div>
<!-- delipress__wizard__modal__content -->
