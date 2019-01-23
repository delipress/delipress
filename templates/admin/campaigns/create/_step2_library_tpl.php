<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\TemplateLibraryHelper;

$templates = TemplateLibraryHelper::getTemplatesLibrary();

?>

<div class="delipress__collection delipress__collection--small">
    <?php foreach ($templates as $key => $template): ?>
        <div class="delipress__collection__item">
            <a class="delipress__collection__item__thumb delipress-modal-trigger" style="background-image:url(<?php echo $template["image"]; ?>);" data-iframe-src="<?php echo $template["image"]; ?>" data-modal-id="delipress-template-preview" href="#" target="_blank">
                <span class="delipress__collection__item__thumb__hover">
                    <span class="dashicons dashicons-search"></span>
                    <?php _e('Preview', 'delipress'); ?>
                </span>
            </a>
            <div class="delipress__collection__item__content">
                <h3 class="delipress__collection__item__title">
                    <?php echo $template["name"]; ?>
                </h3>

                <p class="delipress__center">
                    <a
                        data-action="<?php echo $this->campaignServices->getCreateUrlFormAdminPost(
                            (int) $this->currentStep,
                            $this->campaign->getId(),
                            array(
                                "from"             => "library",
                                "library_template" => $key
                            )
                        ); ?>"
                        data-title="<?php _e("Warning","delipress") ?>"
                        data-message="<?php _e("You already have a template, if you select another one all your changes will be lost.", "delipress") ?>"
                        class="js-delipress-choose-template delipress__button delipress__button--soft delipress__button--small"
                    >
                        <?php _e('Choose template', "delipress"); ?>
                    </a>
                </p>
            </div>
        </div>
    <?php endforeach; ?>

</div> <!-- delipress__collection -->

<div class="delipress__modal" id="delipress-template-preview">
    <div class="delipress__modal__overlay"></div>
    <div class="delipress__modal__content">
        <a href="#" class="delipress__modal__close">
            <span class="dashicons dashicons-no-alt"></span>
        </a>
        <iframe src="" frameborder="0"></iframe>
    </div>
</div>

