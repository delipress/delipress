<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;

$provider = $this->optionServices->getProvider();

$pagedType  = $typeCampaign = "send";
$paged      = (isset($_GET["paged-" . $pagedType])) ?
                (int) $_GET["paged-" . $pagedType] :
                (
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );

$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_campaigns_send_per_page", 10);

$timezoneWP = get_option('timezone_string');
if(empty($timezoneWP)){
    $timezoneWP = new \DateTimeZone("UTC");
}
else{
    $timezoneWP = new \DateTimeZone($timezoneWP);
}
$date = new DateTime("now", $timezoneWP);

$campaigns = $this->campaignServices->getCampaignsWithProvider(
    $provider["key"],
    array(
        "post_status" => "publish",
        "posts_per_page" => $numberPerPage,
        "offset"         => ($paged * $numberPerPage) - $numberPerPage,
        "meta_query"  => array(
            array(
                "relation" => "OR",
                array(
                    'key'     => PostTypeHelper::META_CAMPAIGN_DATE_SEND,
                    'value'   => $date->format("Y-m-d H:i:s"),
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


$classList    = "delipress__checkbox__input__send";
$nameCheckbox = "campaignsSend[]";

if(!empty($campaigns)):
?>

<h2 class="delipress__opener delipress__opener--is-open js-delipress-opener"><?php esc_html_e('Sent campaigns', "delipress"); ?>  <span class="dashicons dashicons-arrow-down-alt2"></span></h2>

<section class="delipress__section">
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <?php foreach ($campaigns as $key => $campaign): ?>
                <?php include __DIR__ . "/_template_list_campaign.php"; ?>
            <?php endforeach; ?>
        </ul>
        <footer class="delipress__list__footer">
            <div class="delipress__list__footer__item">
                <input type="checkbox" id="select-all-send" class="delipress__checkbox__input" />
                <label for="select-all-send" class="delipress__checkbox"></label>
            </div>
            <div class="delipress__list__footer__item">
                <button 
                    type="submit" 
                    data-action="<?php echo $this->campaignServices->getDeleteCampaignsUrl(); ?>" 
                    data-title="<?php _e("Do you really want to delete these campaigns?", "delipress"); ?>",
                    data-message=""
                    class="delipress__button delipress__button--soft delipress__button--small delipress__button--action_choose"
                >
                    <?php esc_html_e('Delete', "delipress"); ?>
                </button>
            </div>
        </footer>
    </div>

    <?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>

</section>

<script>
    jQuery(document).ready(function(){

        var DelipressSelectAll = require("javascripts/backend/SelectAll")
        var selectAllClass = new DelipressSelectAll(
            "#select-all-send",
            ".<?php echo $classList ?>",
            "#form_page"
        )
        selectAllClass.init()
    })
</script>

<?php endif; ?>
