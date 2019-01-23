<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\MarkupIncentiveHelper;

$licenseStatusValid  = $this->optionServices->isValidLicense();

$pagedType  = "active";
$paged      = (isset($_GET["paged-" . $pagedType])) ?
                (int) $_GET["paged-" . $pagedType] :
                (
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );
$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_optins_active_per_page", 10);

$optins = $this->optinServices->getOptins(
     array(
        'meta_key'       => PostTypeHelper::META_OPTIN_IS_ACTIVE,
        'meta_value'     => 1,
        "offset"         => ($paged * $numberPerPage) - $numberPerPage,
        'posts_per_page' => $numberPerPage
    )
);

$countTotal     = $this->optinServices->getCountLastGetOptins();
$active    =  true;
$nameInput =  "optinsActive[]";
$class     =  "js-optin-active";
?>

<?php
    if(!$licenseStatusValid){
        MarkupIncentiveHelper::printMarkup('line', array(
            'title' => esc_html__('Do you want to see how well your Opt-In form performs?', 'delipress'),
            'content' => __('Get more subscribers by discovering which Opt-In form <strong>is performing best</strong> with impression and conversion statistics.', 'delipress')
        ));
    }
?>

<h2 class="delipress__opener delipress__opener--is-open js-delipress-opener"><?php esc_html_e('Active Opt-In forms', 'delipress'); ?> <span class="dashicons dashicons-arrow-down-alt2"></span></h2>

<?php if(!empty($optins)): ?>
<section class="delipress__section">
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <?php
                foreach($optins as $key => $optin):
                    $type      =  $optin->getType();
            ?>
                <?php include __DIR__ . "/_item_optin.php"; ?>
            <?php endforeach; ?>
        </ul>

        <footer class="delipress__list__footer">
            <div class="delipress__list__footer__item">
                <input type="checkbox" id="select-all-active" class="delipress__checkbox__input" />
                <label for="select-all-active" class="delipress__checkbox"></label>
            </div>
            <div class="delipress__list__footer__item">
                <button
                    type="submit"
                    data-action="<?php echo $this->optinServices->getDeleteOptinsUrl("active"); ?>"
                    class="delipress__button delipress__button--soft delipress__button--small js-delipress-action-choose"
                    data-title="<?php _e("Do you really want to delete these Opt-Ins?", "delipress"); ?>",
                    data-message=""
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
            "#select-all-active",
            ".<?php echo esc_js($class) ?>",
            "#form_page"
        )
        selectAllClass.init()
    })
</script>

<?php else: ?>
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <li class="delipress__list__nothing">
                <span class="dashicons dashicons-warning"></span> <?php esc_html_e('Sadly there is nothing to show here yet.', 'delipress'); ?> <a href="<?php echo $this->optinServices->getCreateUrl(); ?>"><?php esc_html_e('Create your first Opt-In form', 'delipress'); ?></a>
            </li>
        </ul>
    </div>

<?php endif; ?>
