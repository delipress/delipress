<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Models\CampaignModel;

$provider = $this->optionServices->getProvider();

$pagedType  = $typeCampaign = "draft";
$paged      = (isset($_GET["paged-" . $pagedType])) ?
                (int) $_GET["paged-" . $pagedType] :
                (
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );


$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_campaigns_draft_per_page", 10);

$campaigns = $this->campaignServices->getCampaigns(
    array(
        "post_status"    => "draft",
        "posts_per_page" => $numberPerPage,
        "offset"         => ($paged * $numberPerPage) - $numberPerPage
    )
);

$countTotal     = $this->campaignServices->getCountLastGetCampaigns();

$sentCampaigns = $this->campaignServices->getCampaigns(
    array(
        "post_status" => "publish"
    )
);

$classList    = "delipress__checkbox__input__draft";
$nameCheckbox = "campaignsDraft[]";

?>

<h2 class="delipress__opener delipress__opener--is-open js-delipress-opener">
    <?php esc_html_e('Draft Campaigns', "delipress"); ?> <a href="" class="dashicons dashicons-arrow-down-alt2"></a>
</h2>

<section class="delipress__section">
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <?php if(!empty($campaigns)):
                foreach ($campaigns as $key => $campaign): ?>
                <?php include __DIR__ . "/_template_list_campaign.php"; ?>
                <?php endforeach;
            elseif(count($sentCampaigns) == 0): ?>
            <li class="delipress__list__nothing">
                <span class="dashicons dashicons-warning"></span> <?php esc_html_e('Sadly there is nothing to show here yet.', 'delipress'); ?> <a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1); ?>"><?php esc_html_e('Create your first campaign.', 'delipress'); ?></a>
            </li>
            <?php else: ?>
                <li class="delipress__list__nothing">
                    <span class="dashicons dashicons-info"></span> <?php esc_html_e('You don’t have any drafts right now', 'delipress'); ?>

                </li>
            <?php endif; ?>
        </ul>
        <?php if(!empty($campaigns)): ?>
        <footer class="delipress__list__footer">
            <div class="delipress__list__footer__item">
                <input type="checkbox" id="select-all-draft" class="delipress__checkbox__input" />
                <label for="select-all-draft" class="delipress__checkbox"></label>
            </div>
            <div class="delipress__list__footer__item">
                <button 
                    type="submit" 
                    data-action="<?php echo $this->campaignServices->getDeleteCampaignsUrl("draft"); ?>" 
                    class="delipress__button delipress__button--soft delipress__button--small delipress__button--action_choose"
                    data-title="<?php _e("Do you really want to delete these campaigns?", "delipress"); ?>",
                    data-message=""
                >
                    <?php esc_html_e('Delete', "delipress"); ?>
                </button>
            </div>
        </footer>
        <?php endif; ?>
    </div>

    <?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>
</section>

<script>
    jQuery(document).ready(function(){

        var DelipressSelectAll = require("javascripts/backend/SelectAll")
        var selectAllClass = new DelipressSelectAll(
            "#select-all-draft",
            ".<?php echo $classList ?>",
            "#form_page"
        )
        selectAllClass.init()
    })
</script>
