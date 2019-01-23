<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PrepareModelHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;

use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Models\SubscriberModel;

if (!isset($_GET["list_id"])) {
    wp_safe_redirect(admin_url());
}

$provider       = $this->optionServices->getProvider();
$paged          = (isset($_GET["paged"])) ? (int) $_GET["paged"] :  1;
$numberPerPage  = apply_filters(DELIPRESS_SLUG . "_list_subscribers_per_page", 20);

$listModel      = PrepareModelHelper::getListFromUrl();

$nbSubscribers  = $countTotal = $listModel->countSubscribers();

$listSubscribers = $listModel->getSubscribers($paged, $numberPerPage);

$successNotices = AdminNoticesHelper::getSuccessNotices();
$disabledManageSubscribers = apply_filters(DELIPRESS_SLUG . "_disabled_manage_subscribers", false);
?>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php"); ?>


<?php
    if(
        !empty($successNotices) &&
        isset($successNotices["admin_notice"]) &&
        isset($successNotices["admin_notice"]["from"]) &&
        $successNotices["admin_notice"]["from"] == "add_subscriber_admin"
    ){
        switch($provider["key"]){
            case ProviderHelper::MAILCHIMP:
                $msg = __("The new subscriber may take a few minutes to appear.", "delipress");
                break;
            default:
                $msg = __("The new subscriber may take a few seconds to appear.", "delipress");
                break;
        }
        ?>
        <div class="delipress__resave">
            <p><?php echo $msg; ?></p>
        </div>
        <?php
    }
?>


<h1><?php echo $listModel->getName(); ?> <small><?php esc_html_e("Subscribers", "delipress"); ?></small></h1>


<p class="delipress__intro">
    <?php
        echo sprintf(
            _n(
                "You have <strong>%s</strong> subscriber on this list.",
                "You have <strong>%s</strong> subscribers on this list.",
                ($nbSubscribers = $countTotal === 0) ? 1 : $nbSubscribers = $countTotal,
                "delipress"
            ),
            $nbSubscribers = $countTotal
        );
    ?>
</p>

<section class="delipress__section">
    <div class="delipress__list">
        <form action="<?php echo $this->listServices->getDeleteListsUrl(); ?>" method="post" id="form_list_subscribers">
            <ul class="delipress__list__content">
                <?php if (!empty($listSubscribers)) : ?>
                    <?php foreach ($listSubscribers as $key => $subscriber) : ?>
                        <li class="delipress__list__item delipress__flex delipress__flex--center">
                            <div class="delipress__list__item__col-box">
                                <?php
                                    $inputValue = $subscriber->getId();
                                    switch($provider["key"]){
                                        case ProviderHelper::MAILJET:
                                            $inputValue = $subscriber->getEmail();
                                            break;
                                    }
                                ?>
                                <input type="checkbox" id="<?php echo $subscriber->getId() ?>" class="delipress__checkbox__input " name="subscribers[]" value="<?php echo $inputValue; ?>" />
                                <label for="<?php echo $subscriber->getId() ?>" class="delipress__checkbox"></label>
                            </div>
                            <div class="delipress__f4">
                                <?php
                                    $idValue = $subscriber->getId();
                                    switch($provider["key"]){
                                        case ProviderHelper::SENDINBLUE:
                                            $idValue = $subscriber->getEmail();
                                            break;
                                    }
                                ?>
                                <a href="<?php echo $this->subscriberServices->getCreateUrl($listModel->getId(), $idValue); ?>" class="delipress__list__item__title <?php if($subscriber->getStatus() != StatusHelper::SUBSCRIBE) { echo "delipress__list__item__no-subscriber"; } ?>">
                                    <?php echo esc_html( $subscriber->getEmail() ); ?>
                                </a>
                            </div>
                            <div class="delipress__list__item__standard delipress__f3">
                                <?php esc_html_e('Status', 'delipress'); ?>
                                <strong>
                                    <?php echo $subscriber->getStatus(true); ?>
                                </strong>
                            </div>
                            <div class="delipress__list__item__standard delipress__f3">
                                <?php esc_html_e('Added on', 'delipress'); ?>
                                <strong><?php echo $subscriber->getCreatedAt(); ?></strong>
                            </div>
                            <div class="delipress__list__item__col-actions delipress__f1">
                                <?php if(!$disabledManageSubscribers): ?>
                                <nav class="delipress__more">
                                    <a href="" class="delipress__button delipress__button--soft">
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </a>
                                    <ul class="delipress__more__sub">
                                        <li>
                                            <a
                                                href="<?php echo $this->subscriberServices->getDeleteSubscriberFromList($subscriber->getId(), $listModel->getId() );?>"
                                                class="js-prevent-delete-action"
                                                data-title="<?php esc_html_e("Do you really want to delete this subscriber?", "delipress"); ?>",
                                                data-message="<?php echo esc_attr($subscriber->getEmail()); ?>"
                                            >
                                                <?php esc_html_e("Delete", "delipress"); ?>
                                            </a>
                                        </li>
                                    </ul>
                                <nav>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="delipress__list__nothing">
                        <span class="dashicons dashicons-warning"></span> <?php _e('Sadly there is nothing to show here yet.', 'delipress'); ?> <a href="<?php echo $this->subscriberServices->getCreateUrl($listModel->getId()); ?>"><?php _e('Add your first subscriber', 'delipress'); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (!empty($listSubscribers)) : ?>
                <footer class="delipress__list__footer">
                    <div class="delipress__list__footer__item">
                        <input type="checkbox" id="select-all-subscribers" class="delipress__checkbox__input">
                        <label for="select-all-subscribers" class="delipress__checkbox"></label>
                    </div>
                    <div class="delipress__list__footer__item">
                        <?php if($disabledManageSubscribers): ?>
                            <div
                                type="submit"
                                class="delipress__button delipress__button--soft delipress__button--small delipress__button--demo-disabled"
                            >
                                <?php _e('Delete'); ?> <?php _e("(Disabled)", "delipress"); ?>
                            </div>
                        <?php else: ?>
                            <button
                                type="submit"
                                class="delipress__button delipress__button--action_choose_list_subscriber delipress__button--soft delipress__button--small" data-action="<?php echo $this->subscriberServices->getDeleteSubscribersFromList($listModel->getId() ); ?>"
                                data-title="<?php _e("Do you really want to delete these subscribers?", "delipress"); ?>",
                                data-message=""
                            >
                                <?php _e('Delete'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </footer>
            <?php endif; ?>
            <input
                type="hidden"
                name="list_id"
                value="<?php echo $listModel->getId(); ?>"
            />

        </form>
    </div>

    <?php include DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/_pagination.php"; ?>
</section>


<script>
    jQuery(document).ready(function(){
        var DelipressSelectAll = require("javascripts/backend/SelectAll")
        var DelipressPreventChooseAction = require("javascripts/backend/PreventChooseAction")

        var selectAllClass = new DelipressSelectAll(
            "#select-all-subscribers",
            ".delipress__checkbox__input",
            "#form_list_subscribers"
        )
        selectAllClass.init()

        var preventChooseActionClass = new DelipressPreventChooseAction(
            "#form_list_subscribers",
            ".delipress__button--action_choose_list_subscriber"
        )

        preventChooseActionClass.init()
    })
</script>


<?php

include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php");

?>
