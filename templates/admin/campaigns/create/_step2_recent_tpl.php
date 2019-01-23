<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;

$provider   = $this->optionServices->getProvider();
$pagedType  = $typeCampaign = "send";
$paged      = (isset($_GET["paged-" . $pagedType])) ?
                (int) $_GET["paged-" . $pagedType] :
                (
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );

$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_campaigns_send_per_page", 10);

$campaigns = $this->campaignServices->getCampaigns(
    array(
        "post_status" => "publish",
        "posts_per_page" => $numberPerPage,
        "offset"         => ($paged * $numberPerPage) - $numberPerPage,
        "meta_query"  => array(
            array(
                "relation" => "OR",
                array(
                    'key'     => PostTypeHelper::META_CAMPAIGN_DATE_SEND,
                    'value'   => date("Y-m-d H:i:s"),
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'     => PostTypeHelper::META_CAMPAIGN_SEND,
                    'value'   => "now",
                ),
            )
        )
    )
);

$countTotal     = $this->campaignServices->getCountLastGetCampaigns();

?>


<?php if (empty($campaigns)): ?>
    <p><?php esc_html_e('You didn’t send any campaign yet. Use one of our free template to kickstart your campaign or start from scratch.', 'delipress'); ?></p>
<?php endif; ?>

<div class="delipress__collection delipress__collection--small">
    <?php foreach ($campaigns as $key => $campaign):
            $resultPreview = $this->templateUrlServices->getUrlPreviewTemplateFromCampaign($campaign);
    ?>
        <div class="delipress__collection__item">
            <a class="delipress__collection__item__thumb delipress-modal-trigger" style="background-image:url(<?php echo $resultPreview["src"]; ?>);" data-iframe-src="<?php echo $this->campaignServices->getUrlViewCampaignOnline($campaign); ?>" data-modal-id="delipress-template-recent" href="#" target="_blank">
                <span class="delipress__collection__item__thumb__hover">
                    <span class="dashicons dashicons-search"></span>
                    <?php esc_html_e('Preview', 'delipress'); ?>
                </span>
            </a>
            <div class="delipress__collection__item__content">
                <h3 class="delipress__collection__item__title">
                    <?php echo $campaign->getTitle(); ?>
                </h3>

                <span class="delipress__collection__item__date">
                   <?php echo $campaign->getCreatedAt(); ?>
                </span>

                <p class="delipress__collection__item__subject">
                    <?php echo $campaign->getSubject(); ?>
                </p>

                <p class="delipress__center">
                    <a
                    data-action="<?php echo $this->campaignServices->getCreateUrlFormAdminPost(
                        (int) $this->currentStep,
                        $this->campaign->getId(),
                        array(
                            "from"  => "recent",
                            "recent_campaign_id" => $campaign->getId()
                        )
                    ); ?>"
                    data-title="<?php esc_html_e("Warning","delipress") ?>"
                    data-message="<?php esc_html_e("You already have a template, if you select another one all your changes will be lost.", "delipress") ?>"
                    class="js-delipress-choose-template delipress__button delipress__button--soft delipress__button--small"
                >
                    <?php esc_html_e('Choose template', "delipress"); ?>
                </a>
                </p>
            </div>
        </div>
    <?php endforeach; ?>

</div> <!-- delipress__collection -->

<div class="delipress__modal" id="delipress-template-recent">
    <div class="delipress__modal__overlay"></div>
    <div class="delipress__modal__content">
        <a href="#" class="delipress__modal__close">
            <span class="dashicons dashicons-no-alt"></span>
        </a>
        <iframe src="" frameborder="0"></iframe>
    </div>
</div>

<?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>
