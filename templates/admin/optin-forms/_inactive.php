<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;

$pagedType  = "inactive";
$paged      = (isset($_GET["paged-" . $pagedType])) ?  
                (int) $_GET["paged-" . $pagedType] : 
                ( 
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );
$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_optins_inactive_per_page", 10);

$optins = $this->optinServices->getOptins(
     array(
        'meta_key'       => PostTypeHelper::META_OPTIN_IS_ACTIVE,
        'meta_value'     => 0,
        "offset"         => ($paged * $numberPerPage) - $numberPerPage,
        'posts_per_page' => $numberPerPage
    )
);

$countTotal     =  $this->optinServices->getCountLastGetOptins();
$active         =  false;
$nameInput      =  "optinsInactive[]";
$class          =  "js-optin-inactive";


if(!empty($optins)):
?>
<h2 class="delipress__opener js-delipress-opener"><?php esc_html_e('Inactive Opt-In forms', 'delipress'); ?> <span class="dashicons dashicons-arrow-down-alt2"></span></h2>

<section class="delipress__section">
    <div class="delipress__list">
        <ul class="delipress__list__content">
            <?php
                foreach($optins as $key => $optin):
                    include __DIR__ . "/_item_optin.php";
                endforeach; 
            ?>
        </ul> 
    </div>

    <footer class="delipress__list__footer">
        <div class="delipress__list__footer__item">
            <input type="checkbox" id="select-all-inactive" class="delipress__checkbox__input" />
            <label for="select-all-inactive" class="delipress__checkbox"></label>
        </div>
        <div class="delipress__list__footer__item">
            <button 
                type="submit" 
                data-action="<?php echo $this->optinServices->getDeleteOptinsUrl("inactive"); ?>" 
                class="delipress__button delipress__button--soft delipress__button--small js-delipress-action-choose"
                data-title="<?php _e("Do you really want to delete these Opt-Ins?", "delipress"); ?>",
                data-message=""
            >
                <?php esc_html_e('Delete', "delipress"); ?>
            </button>
        </div>
    </footer>
    
    <br />
    <?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>

</section>

<script>
    jQuery(document).ready(function(){

        var DelipressSelectAll = require("javascripts/backend/SelectAll")
        var selectAllClass = new DelipressSelectAll(
            "#select-all-inactive",
            ".<?php echo esc_js($class) ?>",
            "#form_page"
        )
        selectAllClass.init()
    })
</script>

<?php endif; ?>
