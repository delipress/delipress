<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$provider    = $this->optionServices->getProvider();

$pagedType  = "active";
$paged      = (isset($_GET["paged-" . $pagedType])) ?
                (int) $_GET["paged-" . $pagedType] :
                (
                    (isset($_GET["paged"])) ?  (int) $_GET["paged"] : 1
                );
$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_lists_per_page", 10);

$countTotal  = $this->listServices->getCountLists(array(
    "limit"  => -1
));


$lists       = $this->listServices->getLists(array(
    "offset" => ($paged * $numberPerPage) - $numberPerPage,
    "limit"  => $numberPerPage
));

$disabledManageList = apply_filters(DELIPRESS_SLUG . "_disabled_manage_list", false);


?>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php"); ?>

<h1><?php echo esc_html__(get_admin_page_title(), "delipress"); ?> <small><?php esc_html_e("All items", "delipress"); ?></small></h1>

<p class="delipress__intro"><?php _e("Manage your lists and subscribers", "delipress"); ?></p>

<section class="delipress__section">
    <div class="delipress__list">
        <form action="<?php echo $this->listServices->getDeleteListsUrl(); ?>" method="post" id="form_footer">
            <ul class="delipress__list__content">
                <?php if (!empty($lists)) : ?>
                    <?php foreach ($lists as $key => $list) :

                        $isConnector = $list->isConnector();
                        $syncInWork  = $list->synchronizeInWork();
                        $classLi     = ($syncInWork) ?  "delipress__list__item--syncing " : "";
                        $classLi     .= ($isConnector) ? "delipress__list__item--connector" : "";
                        $isDynamic = $list->isDynamic();
                    ?>
                        <li class="delipress__list__item delipress__flex delipress__flex--center <?php echo $classLi; ?>">

                            <div class="delipress__list__item__col-box">
                                <?php if (!$isConnector) : ?>
                                    <input type="checkbox" id="<?php echo $list->getId(); ?>" class="delipress__checkbox__input" name="lists[]" value="<?php echo $list->getId(); ?>" />
                                    <label for="<?php echo $list->getId(); ?>" class="delipress__checkbox"></label>
                                <?php else : ?>
                                    <span class="dashicons dashicons-wordpress" title="<?php _e('This list is synced with your WordPress users', 'delipress'); ?>"></span>
                                <?php endif; ?>
                            </div>
                            <div class="delipress__f3">
                                <a href="<?php echo $this->subscriberServices->getPageSubscribersUrl($list->getId()); ?>" class="delipress__list__item__title">
                                    <?php echo esc_html($list->getName()); ?>
                                </a>
                                <?php if ($isDynamic) : ?>
                                    <br>
                                    <span class="delipress__list__item__subtitle">
                                        <span class='dashicons dashicons-networking'></span>
                                        <?php _e('Dynamic list', 'delipress'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="delipress__f3">

                                <span class="delipress__list__item__num">
                                    <?php
                                        $total = $list->countSubscribers();
                                        echo $total;
                                    ?>
                                </span>
                                <?php
                                    echo esc_html(
                                            _n(
                                            "subscriber",
                                            "subscribers",
                                            ((int) $total === 0) ? 1 : $total,
                                            "delipress"
                                        )
                                    );

                                ?>
                                <?php if ($syncInWork) : ?>
                                    <span class="delipress__list__item__update dashicons dashicons-update dashicons--roll"></span>
                                <?php endif; ?>

                            </div>
                            <div class="delipress__f3">
                                <span class="delipress__list__item__num">
                                    <?php
                                        $optins = $list->getOptins();
                                        $totalOptin = 0;
                                    if ($optins) {
                                        $totalOptin = count($optins);
                                    }

                                        echo $totalOptin;
                                    ?>
                                </span>
                                <?php
                                    echo esc_html(
                                            _n(
                                            "Opt-In linked",
                                            "Opt-Ins linked",
                                            ((int) $totalOptin === 0) ? 1 : $totalOptin,
                                            "delipress"
                                        )
                                    );

                                ?>
                            </div>
                            <div class="delipress__list__item__col-actions delipress__f5">
                                <a href="<?php echo $this->subscriberServices->getPageSubscribersUrl($list->getId()); ?>" class="delipress__button delipress__button--soft"><?php _e('Subscribers', 'delipress'); ?></a>
                                    
                                <?php if(!$disabledManageList): ?>
                                    <a href="<?php echo $this->listServices->getEditUrl($list->getId()); ?>" class="delipress__button delipress__button--soft"><?php _e('Settings', 'delipress'); ?></a>
                                <?php else: ?>
                                    <div class="delipress__button delipress__button--soft delipress__button--demo-disabled"><?php _e('Settings', 'delipress'); ?> <?php _e("(Disabled)", "delipress") ?></div>
                                <?php endif; ?>
                                <nav class="delipress__more">
                                    <a href="" class="delipress__button delipress__button--soft <?php if($disabledManageList): ?>delipress__button--demo-disabled <?php endif;?>">
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </a>
                                    <?php if(!$disabledManageList): ?>
                                        <ul class="delipress__more__sub">
                                            <li>
                                                <a href="<?php echo $this->subscriberServices->getCreateUrl($list->getId()); ?>">
                                                    <?php _e("Add a subscriber", "delipress"); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                if ($isConnector) {
                                                    $url = $this->optionServices->getPageUrl("connectors");
                                                    ?>
                                                    <a
                                                        href="<?php echo $url; ?>"
                                                    >
                                                        <?php _e("Delete", "delipress"); ?>
                                                    </a>
                                                    <?php
                                                } else {
                                                    $url = $this->listServices->getDeleteListUrl($list->getId());
                                                    $preventOptinDeactivate = false;
                                                    foreach ($optins as $key => $optin) {

                                                        $totalListForOptin = $optin->getCountLists();

                                                        if ($totalListForOptin == 1) {
                                                            $preventOptinDeactivate = true;
                                                            break;
                                                        }
                                                    }

                                                    $title = sprintf(esc_html__("Delete %s", 'delipress'), $list->getName());
                                                    $msg = __("Do you really want to delete this list?", "delipress");

                                                    if ($preventOptinDeactivate) {
                                                        $msg .= "<br>" . __("<strong>Warning</strong> : This list has Opt-In(s) linked to it. The Opt-In will be deactivated for you until you linked it to another list.", "delipress");
                                                    }
                                                    ?>

                                                    <a
                                                        href="<?php echo $url; ?>"
                                                        class="js-prevent-delete-action"
                                                        data-title="<?php echo $title ?>",
                                                        data-message="<?php echo $msg; ?>"
                                                    >
                                                        <?php _e("Delete", "delipress"); ?>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </nav>
                            </div>

                            <?php if ($syncInWork) : ?>
                                <div class="delipress__list__item__progress">
                                    <span class="dashicons dashicons-arrow-up-alt"></span> <span><?php _e('This list is currently being synchronized in the background. You can reload the page to check the progress', 'delipress') ?></span>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="delipress__list__nothing">
                        <span class="dashicons dashicons-warning"></span>
                        <?php _e('Sadly there is nothing to show here yet.', 'delipress'); ?>
                        <?php if($provider["is_connect"]): ?>
                            <a href="<?php echo $this->listServices->getChooseCreateUrl(); ?>">
                                <?php _e('Create your first list', 'delipress'); ?>
                            </a>
                        <?php else: ?>
                            <?php _e('You first need to set up an Email service Provider in the settings prior to create a list.', 'delipress'); ?>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (!empty($lists)) : ?>
                <footer class="delipress__list__footer">
                    <div class="delipress__list__footer__item">
                        <input type="checkbox" id="select-all" class="delipress__checkbox__input" />
                        <label for="select-all" class="delipress__checkbox"></label>
                    </div>
                    <div class="delipress__list__footer__item">
                        <?php
                            $objProvider = ProviderHelper::getProviderByKey($provider["key"]);
                            $msg = __("Warning, this action permanently deletes your lists on %s", "delipress");
                            $msg = sprintf($msg, $objProvider["label"]);
                        ?>
                        <?php if($disabledManageList): ?>
                            <div
                                class="delipress__button delipress__button--soft delipress__button--small delipress__button--demo-disabled"
                            >
                                <?php _e('Delete', "delipress"); ?> <?php _e("(Disabled)", "delipress") ?>
                            </div>
                        <?php else: ?>
                            <button
                                class="delipress__button delipress__button--action_choose_list delipress__button--soft delipress__button--small"
                                data-action="<?php echo $this->listServices->getDeleteListsUrl(); ?>"
                                data-title="<?php _e("Do you really want to delete these lists?", "delipress"); ?>",
                                data-message="<?php echo $msg ?>"
                            >
                                <?php _e('Delete', "delipress"); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </footer>
            <?php endif; ?>
        </form>

        <script>
            jQuery(document).ready(function(){
                var DelipressSelectAll           = require("javascripts/backend/SelectAll")
                var DelipressPreventChooseAction = require("javascripts/backend/PreventChooseAction")

                var selectAllClass = new DelipressSelectAll(
                    "#select-all",
                    ".delipress__checkbox__input",
                    "#form_footer"
                )
                selectAllClass.init()

                var preventChooseActionClass = new DelipressPreventChooseAction(
                    "#form_footer",
                    ".delipress__button--action_choose_list"
                )

                preventChooseActionClass.init()
            })
        </script>
    </div>
    <?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>
</section>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php"); ?>
