<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\SourceSubscriberHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\PrepareModelHelper;
use Delipress\WordPress\Helpers\AdminFormValues;

use Delipress\WordPress\Models\ListModel;

if(!isset( $_GET["list_id"]) ){
    wp_redirect(admin_url());
    exit;
}

$errors  = ErrorFieldsNoticesHelper::getErrorNotices();

$listModel    = PrepareModelHelper::getListFromUrl();
$subscriber   = PrepareModelHelper::getSubscriberFromUrl();

$idSubscriber = null;
if($subscriber){
    $idSubscriber = $subscriber->getId();
}

$metasDataProvider = $this->metaServices->getMetas($listModel->getId());
$disabledManageSubscribers = apply_filters(DELIPRESS_SLUG . "_disabled_manage_subscribers", false);

?>

<h1>
    <?php echo $listModel->getName(); ?> 
    <small>
        <?php if(empty($idSubscriber)): ?>
            <?php esc_html_e('Add subscriber', 'delipress'); ?>
        <?php else: ?>
            <?php esc_html_e('Edit subscriber', 'delipress'); ?>
        <?php endif; ?>
    </small>
</h1>


<div class="delipress__settings">

    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="email"><?php esc_html_e('Email', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
                <input
                id="email"
                name="email"
                type="email"
                class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::META_SUBSCRIBER_EMAIL) ): ?> delipress__input--error<?php endif; ?>"
                value="<?php echo esc_attr(AdminFormValues::displayOldValues("email", $subscriber->getEmail()) ); ?>"
                placeholder=""
                <?php if(!empty($idSubscriber)): ?>
                    readonly="readonly"
                <?php endif; ?>
            />
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
            <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::META_SUBSCRIBER_EMAIL, $errors) ?>
        </div>
    </div>
    <?php if(empty($idSubscriber)): ?>
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="upload"><?php esc_html_e('Confirmation', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input 
                    type="checkbox" 
                    id="delipress_confirm" 
                    class="delipress__checkbox__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::META_SUBSCRIBER_CONFIRM) ): ?> delipress__input--error<?php endif; ?>" 
                    name="confirm" 
                    value="1" 
                    <?php if(!ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::META_SUBSCRIBER_CONFIRM) ): ?>
                        checked="checked"
                    <?php endif; ?>
                >
                <label for="delipress_confirm" class="delipress__checkbox"><?php esc_html_e('I legally got that list', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <span class="delipress__mandatory"><?php _e("required", "delipress"); ?></span>
                <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::META_SUBSCRIBER_CONFIRM, $errors) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(!empty($metasDataProvider)): ?>
        <h2>
            <?php _e("Subscriber details", "delipress"); ?>
        </h2>
        <?php foreach($metasDataProvider["results"] as $meta): ?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="<?php echo $meta->getId(); ?>"><?php echo $meta->getTitle(); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f4">
                    <input
                        id="<?php echo $meta->getId(); ?>"
                        name="metas[<?php echo $meta->getTag(); ?>]"
                        type="text"
                        class="delipress__input"
                        value="<?php echo esc_attr(
                            AdminFormValues::displayOldValues( $meta->getTag(), $subscriber->getMetaValue($meta) ) 
                        ); ?>"
                        placeholder="" />
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <input type="hidden" name="list_id" value="<?php echo $listModel->getId(); ?>" />
    <?php if(!$disabledManageSubscribers): ?>
        <?php if(!empty($idSubscriber)): ?>
            <input type="hidden" name="subscriber_id" value="<?php echo $idSubscriber; ?>" />
            <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Edit subscriber', 'delipress'); ?></button>
        <?php else: ?>
            <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Add subscriber', 'delipress'); ?></button>
        <?php endif; ?>
    <?php else: ?>
         <div type="submit" class="delipress__button delipress__button--save delipress__button--demo-disabled"><?php _e('Edit subscriber', 'delipress'); ?> <?php _e("(Disabled)", "delirpess") ?></div>
    <?php endif; ?>


</div>
