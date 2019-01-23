<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ActionHelper;

$templatesSave = $this->templateServices->getTemplates();

?>


<?php if(!empty($templatesSave)): ?>
<div class="delipress__list">
    <ul class="delipress__list__content">
    <?php foreach ($templatesSave as $key => $template): ?>
        <li class="delipress__list__item delipress__flex delipress__flex--center">
            <div class="delipress__fauto">
                <a
                    href="#"
                    class="delipress__list__item__title js-delipress-choose-template"
                    data-action="<?php echo $this->campaignServices->getCreateUrlFormAdminPost(
                        (int) $this->currentStep,
                        $this->campaign->getId(),
                        array(
                            "from"        => "template",
                            "id_template" => $template->getId()
                        )
                    ); ?>"
                    data-title="<?php _e("Warning","delipress") ?>"
                    data-message="<?php _e("You already have a template, if you select another one all your changes will be lost.", "delipress") ?>"
                >
                    <?php echo $template->getName(); ?>
                </a>
            </div>
            <div class="">
                <a
                    href="#"
                    class="delipress__button delipress__button--soft delipress-modal-trigger-template-saved" data-modal-id="delipress-template-saved"
                    data-template-id="<?php echo $template->getId(); ?>">
                        <span class="dashicons dashicons-search"></span>
                        <?php _e('Preview', 'delipress'); ?>
                </a>
            </div>
            <div class="">
                <nav class="delipress__more">
                    <a href="#" class="delipress__button delipress__button--soft">
                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                    </a>
                    <ul class="delipress__more__sub">
                        <li>
                            <a
                                href="<?php echo $this->templateUrlServices->getUrlDeleteFromCampaign($this->campaign->getId(), $template->getId()); ?>"
                                class="js-prevent-delete-action"
                                data-title="<?php echo sprintf(esc_html__("Delete %s", 'delipress'), esc_attr($template->getName())); ?>"
                                data-message="<?php esc_html_e("Do you really want to delete this template?", 'delipress'); ?>"
                            >
                                <?php esc_html_e("Delete", "delipress"); ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</div>

<?php else: ?>
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <li class="delipress__list__nothing">
                <span class="dashicons dashicons-warning"></span> <?php esc_html_e('Sadly there is nothing to show here yet.', 'delipress'); ?>
            </li>
        </ul>
    </div>
<?php endif; ?>


<div class="delipress__modal" id="delipress-template-saved">
    <div class="delipress__modal__overlay"></div>
    <div class="delipress__modal__content">
        <a href="#" class="delipress__modal__close">
            <span class="dashicons dashicons-no-alt"></span>
        </a>
        <div id="delipress-react-selector"></div>
    </div>
</div>


<script>
    DELIPRESS_ENV             = "<?php echo esc_js( (DELIPRESS_LOGS) ? "DEV" : "PROD") ?>";
    DELIPRESS_API_BASE_URL    = "<?php echo esc_js( admin_url()                 ) ?>"
    WPNONCE_AJAX              = "<?php echo esc_js( wp_create_nonce(ActionHelper::REACT_AJAX)   ) ?>"
    DELIPRESS_PATH_PUBLIC_IMG = "<?php echo esc_js( DELIPRESS_PATH_PUBLIC_IMG ) ?>"
    require('javascripts/react/modules/preview/initializeTemplate');
</script>
