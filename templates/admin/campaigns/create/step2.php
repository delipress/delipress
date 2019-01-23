<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

$licenseStatusValid  = $this->optionServices->isValidLicense();

?>

<h1><?php _e("Step 2", "delipress"); ?> <small><?php _e("Choose a template", "delipress") ?></small></h1>

<p class="delipress__intro">
    <?php _e("Which template do you want to use for your campaign?", "delipress"); ?>
</p>

<div class="delipress__template-list">
    <div class="delipress__template-list__choices">
        <a
            href="#"
            data-action="<?php echo $this->campaignServices->getCreateUrlFormAdminPost((int) $this->currentStep, $this->campaign->getId(), array(
            "from"  => "scratch"
        ) ); ?>"
            class="delipress__template-list__choice js-delipress-choose-template"
            data-title="<?php _e("Warning","delipress") ?>"
            data-message="<?php _e("You already have a template, if you select another one all your changes will be lost", "delipress") ?>"
        >
            <div class="delipress__template-list__choice__picto delipress__choose-template__picto--3">
                <span class="dashicons dashicons-plus-alt"></span>
            </div>
            <h2 data-title="<?php _e("Start from scratch", 'delipress'); ?>" class="delipress__template-list__title"><?php _e("Start from scratch", 'delipress'); ?></h2>
            <p><?php _e("Create a new template from the ground up with our easy to use email builder. Create your very own masterpiece", 'delipress'); ?></p>
        </a>

        <a href="#" data-tab="delipress-library" class="delipress__template-list__choice js-template-list-choice">
            <div class="delipress__template-list__choice__picto delipress__choose-template__picto--4">
                <span class="dashicons dashicons-images-alt"></span>
            </div>
            <h2 data-title="<?php _e("Choose a design", 'delipress'); ?>" class="delipress__template-list__title"><?php _e("Choose a design", 'delipress'); ?></h2>
            <p><?php _e("Everyone needs inspiration. We have some basic layouts to get you started", 'delipress'); ?></p>
        </a>

        <?php if($licenseStatusValid): ?>
            <a href="#" data-tab="delipress-recent" class="delipress__template-list__choice js-template-list-choice">
        <?php else: ?>
            <div class="delipress__template-list__choice js-template-list-choice">
                <div class="delipress__template-list__choice__premium">
                    <span class="delipress__template-list__choice__premium-badge">
                        <i class="dashicons dashicons-awards"></i>
                        <span>
                            <?php _e('Premium', 'delipress') ?>
                        </span>
                    </span>
                    <p class="delipress__template-list__choice__premium-incentive">
                        <?php _e('Upgrade to premium to unlock this feature and a lot more. By upgrading you support the development of DeliPress.', 'delipress') ?>
                        <br />
                        <a target="_blank" class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium(); ?>">
                            <?php _e('View pricing', 'delipress') ?>
                        </a>
                    </p>
                </div>
        <?php endif; ?>
                <div class="delipress__template-list__choice__picto delipress__choose-template__picto--1">
                    <span class="dashicons dashicons-backup"></span>
                </div>
                <h2  data-title="<?php _e("Recent campaigns", 'delipress'); ?>" class="delipress__template-list__title"><?php _e("Choose from your recent campaigns", 'delipress'); ?></h2>
                <p><?php _e("Be lightning fast by reusing previous campaigns as a starting point", 'delipress'); ?></p>
        <?php if($licenseStatusValid): ?>
            </a>
        <?php else: ?>
            </div>
        <?php endif; ?>

        <?php if($licenseStatusValid): ?>
            <a href="#" data-tab="delipress-saved" class="delipress__template-list__choice js-template-list-choice">
        <?php else: ?>
            <div class="delipress__template-list__choice js-template-list-choice">
                <div class="delipress__template-list__choice__premium">
                    <span class="delipress__template-list__choice__premium-badge">
                        <i class="dashicons dashicons-awards"></i>
                        <span>
                            <?php _e('Premium', 'delipress') ?>
                        </span>
                    </span>
                    <p class="delipress__template-list__choice__premium-incentive">
                        <?php _e('Upgrade to premium to unlock this feature and a lot more. By upgrading you support the development of DeliPress.', 'delipress') ?>
                        <br />
                        <a target="_blank" class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium(); ?>">
                            <?php _e('View pricing', 'delipress') ?>
                        </a>
                    </p>
                </div>
        <?php endif; ?>
            <div class="delipress__template-list__choice__picto delipress__choose-template__picto--2">
                <span class="dashicons dashicons-portfolio"></span>
            </div>
            <h2 data-title="<?php _e("Saved Templates", 'delipress'); ?>" class="delipress__template-list__title"><?php _e("Reuse a saved template", 'delipress'); ?></h2>
            <p><?php _e("Choose among your previously saved templates to kickstart your new campaign", 'delipress'); ?></p>
        <?php if($licenseStatusValid): ?>
            </a>
        <?php else: ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="delipress__template-list__items">
        <div id="delipress-library" class="delipress__template-list__items--library">
            <h2><?php _e('Template library', "delipress"); ?></h2>
            <?php require_once __DIR__ . "/_step2_library_tpl.php"; ?>
        </div>
        <?php if($licenseStatusValid): ?>
            <div id="delipress-recent" class="delipress__template-list__items--recent">
                <h2><?php _e('Recently sent campaigns', "delipress"); ?></h2>
                <?php require_once __DIR__ . "/_step2_recent_tpl.php"; ?>
            </div>
        <?php endif; ?>
        <div id="delipress-saved" class="delipress__template-list__items--shop">
            <h2><?php _e('Saved Templates', "delipress"); ?></h2>
            <?php require_once __DIR__ . "/_step2_save_tpl.php"; ?>
        </div>

    </div>

</div>


<?php $configTpl = $this->campaign->getConfig(); ?>


<script type="text/javascript">
jQuery(document).ready(function(){
    var DelipressPreventChooseAction = require("javascripts/backend/PreventChooseAction")

    var preventChooseActionClass = new DelipressPreventChooseAction(
        "#form_page",
        ".js-delipress-choose-template"
    )

    <?php if(empty($configTpl)): ?>
    preventChooseActionClass.initEmptyForCampaign()
    <?php else: ?>
    preventChooseActionClass.initTemplateIfNotEmpty()
    <?php endif; ?>
})
</script>
