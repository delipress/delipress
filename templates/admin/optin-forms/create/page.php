<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\TaxonomyHelper;

$provider    = $this->optionServices->getProvider();

$lists = $this->listServices->getLists(array(
    "limit" =>  500
));

$listsOptinIds = $this->optin->getLists("IDS");
$isActive      = $this->optin->getIsActive();
$optins        = OptinHelper::getListOptins();

$optinsCore         = array();
$optinsThirdParty   = array();

$licenseStatusValid  = $this->optionServices->isValidLicense();
$fullLicense         = $this->optionServices->isFullLicense();


foreach($optins as $key => $value){
    if(!$value['third_party']){
        $optinsCore[] = $value;
    } else {
        $optinsThirdParty[] = $value;
    }
}

$errors        = ErrorFieldsNoticesHelper::getErrorNotices();

?>

<h1><?php echo esc_html__(get_admin_page_title(),"delipress"); ?> <small><?php _e("Add Opt-In form", "delipress"); ?></small></h1>


<h2><?php _e("1. Name", "delipress"); ?></h2>

<div class="delipress__settings delipress__flex">

    <div class="delipress__f1">
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="name"><?php _e('Name your Opt-In form', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    id="name"
                    name="<?php echo PostTypeHelper::OPTIN_NAME ?>"
                    type="text"
                    class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::MISSING_OPTIN_NAME) ): ?> delipress__input--error<?php endif; ?>"
                    value="<?php echo esc_attr( AdminFormValues::displayOldValues(PostTypeHelper::OPTIN_NAME, $this->optin->getTitle()) ); ?>"
                    placeholder="" >
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <span class="delipress__mandatory"><?php _e("Required", "delipress"); ?></span>
                <?php _e("The Opt-In name is shown in your WordPress admin only.", "delipress"); ?>
                <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_OPTIN_NAME, $errors) ?>
            </div>
        </div>


        <?php if(count($lists) === 0): ?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="from_to"><?php _e('In which list?', 'delipress'); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f4">
                    <?php
                    if($provider["is_connect"]):
                    ?>
                        <a href="<?php echo $this->listServices->getChooseCreateUrl(); ?>" class="delipress__button delipress__button--second">
                            <?php _e('Create a list', "delipress"); ?>
                        </a>
                    <?php
                    else:
                    ?>
                        <a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="delipress__button delipress__button--soft">
                            <?php _e('Connect a provider', "delipress"); ?>
                        </a>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                    <?php if(!$provider["is_connect"]): ?>
                        <span class="delipress__mandatory"><?php _e("Warning", "delipress"); ?></span>
                        <?php _e("You can't choose a list because there is no configured ESP", "delipress"); ?>
                    <?php else: ?>
                        <span class="delipress__mandatory"><?php _e("Required", "delipress"); ?></span>
                        <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_OPTIN_TAXO_LISTS, $errors) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="from_to"><?php _e('Choose list to add subscriber to', 'delipress'); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f4">
                    <input type="hidden" name="<?php echo PostTypeHelper::OPTIN_TAXO_LISTS; ?>">
                    <div class="delipress__multiselect js-delipress-multiselect <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::MISSING_OPTIN_TAXO_LISTS) ): ?> delipress__input--error<?php endif; ?>">

                        <?php $listSelected = array(); $listsUnselected = array();  ?>

                        <select name="<?php echo PostTypeHelper::OPTIN_TAXO_LISTS; ?>[]" multiple>
                            <?php foreach($lists as $key => $list): ?>
                                <?php
                                if ( in_array($list->getId(), $listsOptinIds) ) {
                                    $listSelected[] = $list;
                                }
                                else{
                                    $listsUnselected[] = $list->getId();
                                }

                                ?>
                                <option value="<?php echo $list->getId();?>" <?php if ( in_array($list->getId(), $listsOptinIds) ) { echo 'selected="true"';  }  ?>>
                                    <?php echo esc_html( $list->getName() ); ?> (<?php echo $list->countSubscribers(); ?> <?php _e("subscribers", "delipress"); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <template>
                            <span class="delipress__multiselect__item" data-value="">
                                <span class="delipress__multiselect__item__name"></span>
                                <a href="" class="delipress__multiselect__item__delete"><span class="dashicons dashicons-no-alt"></span></a>
                            </span>
                        </template>

                        <?php if(!empty($listSelected)): ?>
                            <?php foreach($listSelected as $key => $list):
                                $totalSubscribers = $list->countSubscribers();
                            ?>
                                <span class="delipress__multiselect__item" data-value="<?php echo $list->getId(); ?>">
                                    <span class="delipress__multiselect__item__name"><?php echo esc_html($list->getName() ); ?> (<?php echo $list->countSubscribers(); ?> <?php
                                        echo esc_html(
                                            _n(
                                                "subscriber",
                                                "subscribers",
                                                ((int) $totalSubscribers === 0) ? 1 : $totalSubscribers,
                                                "delipress"
                                            )
                                        );
                                    ?>)</span>
                                    <a href="" class="delipress__multiselect__item__delete"><span class="dashicons dashicons-no-alt"></span></a>
                                </span>
                            <?php endforeach; ?>
                            <?php if(empty($listsUnselected)): ?>
                                <span tabindex="0" class="delipress__multiselect__add" style="display:none;"><?php _e('Add list', 'delipress'); ?></span>
                            <?php else: ?>
                                <span tabindex="0" class="delipress__multiselect__add"><?php _e('Add list', 'delipress'); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span tabindex="0" class="delipress__multiselect__add"><?php _e('Add list', 'delipress'); ?></span>
                        <?php endif; ?>

                        <ul class="delipress__multiselect__list">
                            <?php foreach($lists as $key => $list):
                                $totalSubscribers = $list->countSubscribers();
                            ?>
                                <li
                                    data-value="<?php echo $list->getId();?>" class="delipress__multiselect__list__item"
                                    <?php if(!in_array($list->getId(), $listsUnselected)): ?> style="display:none;" <?php endif; ?>
                                    <span class="delipress__multiselect__list__name">
                                        <span class="delipress__multiselect__list__name"><?php echo $list->getName(); ?></span> (<?php echo $list->countSubscribers(); ?> <?php
                                        echo _n(
                                            "subscriber",
                                            "subscribers",
                                            ((int) $totalSubscribers === 0) ? 1 : $totalSubscribers,
                                            "delipress"
                                        );
                                    ?>)</span>
                                </li>
                            <?php endforeach; ?>
                            <li
                                class="delipress__multiselect__list__placeholder"
                                <?php if(empty($listsUnselected)): ?> style="display:list-item;" <?php endif; ?>
                            ><?php _e('No more lists available', 'delipress'); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                    <span class="delipress__mandatory"><?php _e("Required", "delipress"); ?></span>
                    <?php _e("You can select as many lists as you wish.", 'delipress'); ?>
                    <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_OPTIN_TAXO_LISTS, $errors) ?>
                </div>
            </div>
        <?php endif; ?>


        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="from_to"><?php _e('Active on website?', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">

                <input
                    type="checkbox"
                    id="optin_is_active"
                    name="<?php echo PostTypeHelper::META_OPTIN_IS_ACTIVE ?>"
                    class="delipress__switch__input"
                    value="1"
                    <?php checked( (!empty($isActive ) ) ? $this->optin->getIsActive() : 1 , 1 ); ?>
                />
                <label for="optin_is_active" class="delipress__switch">
                    <div class="delipress__switch__slider"></div>
                    <div class="delipress__switch__on">I</div>
                    <div class="delipress__switch__off">0</div>
                </label>
            </div>
            <div class="delipress__settings__item__help delipress__f5">
            </div>
        </div>

    </div> <!-- delipress__f1 -->


</div> <!-- delipress__settings -->

<h2><?php _e('2. Choose your Opt-In form type', 'delipress'); ?></h2>

<div id="delipress-js-collection-optin" >
    <div class="delipress__collection">
        <?php
        $typeOptin = $this->optin->getType();


        foreach($optinsCore as $key => $optin):
            $type = $optin["key"];

            $altText =  esc_html__('Change', 'delipress');
            $txt     =  esc_html__('Select', 'delipress');
            if(!empty($typeOptin) && $typeOptin === $type):
                $txt         =  esc_html__('Change', 'delipress');
                $altText     =  esc_html__('Select', 'delipress');
            endif;

            $show_badge = false;
            if (
                ($optin["is_premium"] && !$licenseStatusValid && !$optin["full_premium"]) or
                ($optin["full_premium"] && !$fullLicense)
                ):
                $show_badge = true;
            endif;
        ?>
            <div
                class="delipress__collection__item <?php if($show_badge): ?>delipress__premium__collection-item<?php endif; ?> js-delipress__collection__item__choice js-delipress__collection__item__choice-value
                <?php if(!$optin['is_active']): echo "delipress__soon"; endif; ?>"
                data-value="<?php echo $optin['key'] ?>"
                <?php if(!empty($typeOptin) && $typeOptin !== $type): ?> style="display:none;" <?php endif; ?>
            >
                <?php if($show_badge): ?>
                <div class="delipress__premium__collection-item__stamp">
                    <div class="delipress__center-all">
                        <a class="delipress__button delipress__button--premium" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                            <?php _e("Upgrade to premium", "delipress"); ?>
                        </a>
                        <span><?php _e('This Opt-In in only available to premium member', 'delipress'); ?></span>
                    </div>
                </div>
                <span class="delipress__premium-only">
                    <span class="dashicons dashicons-awards"></span>
                </span>
                <?php endif; ?>
                <?php include __DIR__ . "/../_design_optin.php"; ?>
                <div class="delipress__collection__item__content">
                    <h3 class="delipress__collection__item__title">
                        <?php echo $optin["label"]; ?>
                    </h3>
                    <?php if($optin['is_active']): ?>
                        <p><?php echo $optin["description"]; ?></p>
                    <?php else: ?>
                        <p><?php _e('Soon', 'delipress'); ?></p>
                    <?php endif;?>

                </div>
                <?php if($optin['is_active']): ?>
                <div class="delipress__collection__item__actions">
                    <a href="#" class="delipress__button delipress__button--main delipress__button--small js-delipress__collection__item__choice js-delipress__collection__item__choice-label" data-alt-text="<?php echo $altText ?>">
                        <?php echo $txt; ?>
                    </a>
                </div>
                <?php endif;?>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 <?php if(!empty($typeOptin)): ?> style="display:none;" <?php endif; ?>>
        <?php _e('Third Party Opt-Ins', 'delipress'); ?>
    </h3>

    <div class="delipress__collection">
        <?php foreach($optinsThirdParty as $key => $optin):
            $type = $optin["key"];

            $altText =  esc_html__('Change', 'delipress');
            $txt     =  esc_html__('Select', 'delipress');
            if(!empty($typeOptin) && $typeOptin === $type):
                $txt         =  esc_html__('Change', 'delipress');
                $altText     =  esc_html__('Select', 'delipress');
            endif;

            $show_badge = false;
            if (
                ($optin["is_premium"] && !$licenseStatusValid && !$optin["full_premium"]) or
                ($optin["full_premium"] && !$fullLicense)
                ):
                $show_badge = true;
            endif;
        ?>
            <div
                class="delipress__collection__item <?php if ($show_badge): ?>delipress__premium__collection-item<?php endif; ?> js-delipress__collection__item__choice-value <?php if(!$optin['is_active']): echo "delipress__soon"; endif; ?>"
                data-value="<?php echo $optin['key'] ?>"
                <?php if(!empty($typeOptin)): ?> style="display:none;" <?php endif; ?>
            >
                <?php if ($show_badge): ?>
                <div class="delipress__premium__collection-item__stamp">
                    <div class="delipress__center-all">
                        <a class="delipress__button delipress__button--premium" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                            <?php _e("Upgrade to premium", "delipress"); ?>
                        </a>
                        <span><?php _e('This Opt-In in only available to premium member', 'delipress'); ?></span>
                    </div>
                </div>
                <span class="delipress__premium-only">
                    <span class="dashicons dashicons-awards"></span>
                </span>
                <?php endif; ?>
                <?php include __DIR__ . "/../_design_optin.php"; ?>
                <div class="delipress__collection__item__content">
                    <h3 class="delipress__collection__item__title">
                        <?php echo $optin["label"]; ?>
                    </h3>
                    <?php if($optin['is_active']): ?>
                    <p><?php echo $optin["description"]; ?></p>
                    <?php else: ?>
                    <p><?php _e('Soon', 'delipress'); ?></p>
                    <?php endif;?>
                </div>
                <?php if($optin['is_active']): ?>
                <div class="delipress__collection__item__actions">
                    <a href="#" class="delipress__button delipress__button--main delipress__button--small js-delipress__collection__item__choice js-delipress__collection__item__choice-label" data-alt-text="<?php echo $altText ?>">
                        <?php echo $txt; ?>
                    </a>
                </div>
                <?php endif;?>
            </div>
        <?php endforeach; ?>

        <input
            type="hidden"
            id="<?php echo PostTypeHelper::META_OPTIN_TYPE ?>"
            name="<?php echo PostTypeHelper::META_OPTIN_TYPE ?>"
            value="<?php echo $typeOptin ?>" />

    </div> <!-- delipress__collection -->
</div>

<script>
    jQuery(document).on("ready",function(){
        var DelipressChooseCollection = require('javascripts/backend/ChooseCollection');
        var objChooseCollection = new DelipressChooseCollection(
            "#delipress-js-collection-optin",
            ".js-delipress__collection__item__choice",
            "#<?php echo PostTypeHelper::META_OPTIN_TYPE ?>"
        )
    })
</script>

<footer class="delipress__content__bottom">
    <button type="submit" class="delipress__button delipress__button--soft delipress-js-step-submit-prevent" data-next-step="<?php echo $currentStep; ?>"><?php _e('Save changes', 'delipress'); ?></button>
    <button type="submit" class="delipress__button delipress__button--main delipress-js-step-submit-prevent" data-next-step="2"><?php _e('Customization', 'delipress'); ?> <span class="dashicons dashicons-arrow-right-alt2"></span></button>
</footer>
