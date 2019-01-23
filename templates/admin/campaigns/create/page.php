<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminFormValues;

use Delipress\WordPress\Models\CampaignModel;


$options            = $this->optionServices->getOptions();
$provider           = $this->optionServices->getProvider();
$listProviders      = ProviderHelper::getListProviders();
$objProvider        = null;

if($provider){
    foreach($listProviders as $key => $value){
        if($value["key"] === $provider["key"]){
            $objProvider = $value;
            break;
        }
    }
}

$lists       = $this->listServices->getLists(array(
    "offset" => 0,
    "limit"  => 1000
));

$listCampaign    = $this->campaign->getLists();
$locale          = get_locale();

$errors  = ErrorFieldsNoticesHelper::getErrorNotices();

$providerApi = $this->providerServices->getProviderApi($provider["key"]);
$timezone    = get_option('timezone_string');
if(empty($timezone)){
    $timezoneEmpty = new \DateTimeZone("UTC");
    $timezone = $timezoneEmpty->getName();
}

$isSend             = $this->campaign->getIsSend();
$send               = $this->campaign->getSend();
$campaignProviderId = $this->campaign->getCampaignProviderId();
?>

<h1><?php esc_html_e("Step 1", "delipress"); ?> <small><?php esc_html_e("Configure this campaign", "delipress") ?></small></h1>

<h2><?php esc_html_e('About this campaign', "delipress"); ?> </h2>

<p><?php esc_html_e("Define the campaign details", "delipress"); ?></p>

<div class="delipress__settings">

    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="campaign_name"><?php esc_html_e("Name this campaign", "delipress"); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input
                id="campaign_name"
                name="<?php echo PostTypeHelper::CAMPAIGN_NAME; ?>"
                type="text"
                class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::NOT_EMPTY_CAMPAIGN_NAME) ): ?> delipress__input--error<?php endif; ?>"
                placeholder="<?php esc_html_e('Newsletter #1 - My newsletter title','delipress') ?>"
                value="<?php echo esc_attr(AdminFormValues::displayOldValues(PostTypeHelper::CAMPAIGN_NAME, $this->campaign->getTitle()) ); ?>"
            />
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
            <?php esc_html_e("The campaign name is shown in your WordPress admin only.", "delipress"); ?>
            <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::NOT_EMPTY_CAMPAIGN_NAME) ?>
        </div>
    </div>

    <div class="delipress__settings__item delipress__settings__item--jump delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="subject"><?php esc_html_e('Write a subject line', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input
                id="subject"
                name="<?php echo PostTypeHelper::META_CAMPAIGN_SUBJECT; ?>"
                type="text"
                class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SUBJECT) ): ?> delipress__input--error<?php endif; ?>"
                placeholder="<?php esc_attr_e("Subject", "delipress"); ?>"
                value="<?php echo esc_attr( AdminFormValues::displayOldValues(PostTypeHelper::META_CAMPAIGN_SUBJECT, $this->campaign->getSubject()) ); ?>"
            />
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
            <?php esc_html_e("This text will display in the Subject field in your recipient's email client.", "delipress"); ?>
            <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SUBJECT, $errors) ?>
        </div>
    </div>

</div> <!-- delipress__settings -->


<h2><?php esc_html_e('Who will receive this campaign?', "delipress"); ?> </h2>

<p><?php esc_html_e("Select the recipients", "delipress"); ?></p>

<div class="delipress__settings">

    <?php if(count($lists) === 0): ?>

        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label><?php _e('No list found', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <?php if(!$provider["is_connect"]): ?>
                    <div class="delipress__button delipress__button--soft">
                        <?php _e('Create a list', "delipress"); ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $this->listServices->getChooseCreateUrl(); ?>" class="delipress__button delipress__button--save">
                        <?php _e('Create a list', "delipress"); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <span class="delipress__mandatory"><?php _e("Action Required", "delipress"); ?></span>
                <?php esc_html_e("You must create a list with at least a subscriber prior to send this campaign.", "delipress"); ?>
                <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS, $errors) ?>
            </div>
        </div>


    <?php else: ?>
        <?php
        $minimumOneList = false;
        foreach($lists as $key => $list){
            $total = $list->countSubscribers();
            if($total > 0){
                $minimumOneList = true;
            }
        }

        if(!$minimumOneList):
        ?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label><?php esc_html_e('No subscribers found', 'delipress'); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f4">
                    <a href="<?php echo $this->listServices->getPageListUrl(); ?>" class="delipress__button delipress__button--save">
                        <?php esc_html_e('Add a subscriber to a list', "delipress"); ?>
                    </a>
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                    <span class="delipress__mandatory"><?php esc_html_e("Action Required", "delipress"); ?></span>
                    <?php esc_html_e("You need at least one subscriber in a list to send a campaign.", "delipress"); ?>
                    <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS, $errors) ?>
                </div>
            </div>

        <?php else:?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="from_to"><?php esc_html_e('Recipients', 'delipress'); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f4">
                    <div class="delipress__multiselect js-delipress-multiselect <?php if(ErrorFieldsNoticesHelper::hasError( CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS) ): ?> delipress__input--error<?php endif; ?>" data-limit="1">
                        <?php $listSelected = ""; ?>
                        <select name="<?php echo PostTypeHelper::CAMPAIGN_TAXO_LISTS; ?>">
                            <option value="-1"></option>
                            <?php foreach($lists as $key => $list): ?>
                                <?php
                                    $totalSubscribers = $list->countSubscribers();
                                    if((int) $totalSubscribers === 0){
                                        continue;
                                    }
                                    if ( $list->getId() == $listCampaign->getId() ) {
                                        $listSelected = $list;
                                    }
                                ?>
                                <option value="<?php echo $list->getId();?>" <?php if ( $list->getId() == $listCampaign->getId() ) { echo 'selected="true"';  }  ?>>
                                    <?php echo $list->getName(); ?> (<?php echo $totalSubscribers; ?> <?php
                                            echo _n(
                                                "subscriber",
                                                "subscribers",
                                                ((int) $totalSubscribers === 0) ? 1 : $totalSubscribers,
                                                "delipress"
                                            );

                                        ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <template>
                            <span class="delipress__multiselect__item" data-value="">
                                <span class="delipress__multiselect__item__name"></span>
                                <a href="" class="delipress__multiselect__item__delete"><span class="dashicons dashicons-no-alt"></span></a>
                            </span>
                        </template>

                        <?php if(!empty($listSelected)):
                            $totalSubscribers = $listSelected->countSubscribers();
                            ?>
                            <span class="delipress__multiselect__item" data-value="<?php echo $listSelected->getId(); ?>">
                                <span class="delipress__multiselect__item__name"><?php echo $listSelected->getName(); ?> (<?php echo $listSelected->countSubscribers(); ?> <?php
                                        echo _n(
                                            "subscriber",
                                            "subscribers",
                                            ((int) $totalSubscribers === 0) ? 1 : $totalSubscribers,
                                            "delipress"
                                        );
                                    ?>)</span>
                                <a href="" class="delipress__multiselect__item__delete"><span class="dashicons dashicons-no-alt"></span></a>
                            </span>
                            <span class="delipress__multiselect__add" style="display:none;"><?php esc_html_e('Add list', 'delipress'); ?></span>
                        <?php else: ?>
                            <span tabindex=0 class="delipress__multiselect__add"><?php esc_html_e('Add list', 'delipress'); ?></span>
                        <?php endif; ?>

                        <ul class="delipress__multiselect__list">
                            <?php foreach($lists as $key => $list): ?>
                                <?php
                                    $totalSubscribers = $list->countSubscribers();
                                    if((int) $totalSubscribers === 0){
                                        continue;
                                    }
                                ?>
                                <li data-value="<?php echo $list->getId();?>" class="delipress__multiselect__list__item">
                                    <span class="delipress__multiselect__list__name"><?php echo $list->getName(); ?></span>
                                    <span class="delipress__multiselect__list__subscribers">(<?php echo $totalSubscribers; ?> <?php
                                        echo _n(
                                            "subscriber",
                                            "subscribers",
                                            ((int) $totalSubscribers === 0) ? 1 : $totalSubscribers,
                                            "delipress"
                                        );
                                    ?>)</span>
                                </li>
                            <?php endforeach; ?>
                            <li class="delipress__multiselect__list__placeholder"><?php _e('No more list available', "delipress"); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                    <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
                    <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::MISSING_CAMPAIGN_TAXO_LISTS, $errors) ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div> <!-- delipress__settings -->


<h2><?php esc_html_e('When should we send this campaign?', "delipress"); ?> </h2>

<p><?php esc_html_e("Don't worry. Nothing will be sent until you confirm in step 4.", "delipress"); ?></p>

<div id="delipress__settings_send" class="delipress__settings">
    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__settings__item__label--top delipress__f2">
            <label for=""><?php esc_html_e("Schedule", "delipress") ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__settings__item__field--double delipress__f4">
            <div>
                <input
                    type="radio"
                    class="delipress__radio__input"
                    checked
                    name="<?php echo PostTypeHelper::META_CAMPAIGN_SEND; ?>"
                    id="sendnow"
                    value="now" <?php checked($send, "now"); ?>
                />
                <label for="sendnow" class="delipress__radio">
                    <?php esc_html_e("Send it Now", "delipress"); ?>

                </label>
            </div>
            <div class="delipress__settings__item__field-flex">
                <input
                    type="radio"
                    class="delipress__radio__input"
                    name="<?php echo PostTypeHelper::META_CAMPAIGN_SEND; ?>"
                    id="sendlater"
                    value="later"
                    <?php checked($send, "later"); ?>
                />
                <label for="sendlater" class="delipress__radio">
                    <?php esc_html_e("Later", "delipress"); ?>
                </label>
                <span class="delipress__settings__item__field__later">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <input
                        id="<?php echo PostTypeHelper::META_CAMPAIGN_DATE_SEND; ?>"
                        name="<?php echo PostTypeHelper::META_CAMPAIGN_DATE_SEND; ?>"
                        type="text"
                        class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_DATE_SEND) ): ?> delipress__input--error<?php endif; ?>"
                        value="<?php echo $this->campaign->getDateSendFormValue(); ?>"
                        placeholder="" />
                </span>

                <span class="delipress__settings__item__field__timezone">
                    <?php esc_html_e("Current Timezone:", "delipress"); ?> <strong><?php echo $timezone; ?></strong>
                    <br>
                    <small><?php esc_html_e("You can change this in: ", "delipress"); ?> <a href="/wp-admin/options-general.php"><?php esc_html_e("Settings > General", "delipress"); ?></a></small>
                </span>
            </div>

        </div>
        <div class="delipress__settings__item__help delipress__f5 delipress__settings__item__help--double delipress-screen-large">
            <span class="delipress__settings__item__field__timezone">
                <?php esc_html_e("Current Timezone:", "delipress"); ?> <strong><?php echo $timezone; ?></strong>
                <br>
                <small><?php esc_html_e("You can change this in: ", "delipress"); ?> <a href="/wp-admin/options-general.php"><?php esc_html_e("Settings > General", "delipress"); ?></a></small>
            </span>
            <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_SEND, $errors) ?>
            <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::NOT_EMPTY_META_CAMPAIGN_DATE_SEND, $errors) ?>
        </div>
    </div>

</div> <!-- delipress__settings -->


 <script type="text/javascript">
    jQuery(document).ready(function($){
        var $sendLaterEl = $("#<?php echo esc_js(PostTypeHelper::META_CAMPAIGN_DATE_SEND); ?>");
        var oldSendLaterVal = '';
        <?php
        switch($locale){
            case "fr_FR":
            ?>
            var sendFlatePicker = flatpickr("#<?php echo esc_js(PostTypeHelper::META_CAMPAIGN_DATE_SEND); ?>", {
                enableTime: true,
                minDate: "today",
                dateFormat: "d-m-Y H:i",
                locale: "fr",
                time_24hr: true,
                onChange: delipressChangeSendLater,
                minuteIncrement: <?php echo esc_js( apply_filters(DELIPRESS_SLUG . "_minute_increment_campaign", 15) ) ?>
            });
            <?php
                break;
            default:
            ?>
            var sendFlatePicker = flatpickr("#<?php echo esc_js(PostTypeHelper::META_CAMPAIGN_DATE_SEND); ?>", {
                enableTime: true,
                minDate: "today",
                dateFormat: "m-d-Y H:i",
                onChange: delipressChangeSendLater,
                minuteIncrement: <?php echo esc_js( apply_filters(DELIPRESS_SLUG . "_minute_increment_campaign", 15) ) ?>
            });
            <?php
                break;
        }
        ?>

        $sendLaterEl.on("click", function(e){
            $("#sendlater").prop('checked', true).trigger('change')
        })

        function delipressChangeSendLater(){
            oldSendLaterVal = $sendLaterEl.val()
        }

        $('#sendlater, #sendnow').on('change', function(e){
            if($(this).attr('id') == 'sendlater'){
                $sendLaterEl.val(oldSendLaterVal)
                sendFlatePicker.setDate(oldSendLaterVal)
                $('.delipress__settings__item__field__timezone').addClass('is-visible')
                if(!sendFlatePicker.isOpen){
                    sendFlatePicker.open()
                }
            }else{
                oldSendLaterVal = $sendLaterEl.val()
                $sendLaterEl.val("")
                $('.delipress__settings__item__field__timezone').removeClass('is-visible')
            }
        })

    })
</script>



<?php if( !$objProvider ):  ?>
    <h2><?php esc_html_e('Which email provider?', "delipress"); ?> </h2>

    <p><?php esc_html_e("Choose a provider for this campaign", "delipress"); ?></p>

    <div class="delipress__settings">
        <a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="delipress__button delipress__button--main">
            <?php esc_html_e('Configure your provider', "delipress"); ?>
        </a>
    </div> <!-- delipress__settings -->

<?php endif; ?>

<footer class="delipress__content__bottom">
    <?php
        $nextStepRegister = $currentStep;
        if($send === "later" && !$isSend && !empty($campaignProviderId)){
            $nextStepRegister = 4;
        }

    ?>
    <button type="submit" class="delipress__button delipress__button--soft delipress-js-step-submit-prevent" data-next-step="<?php echo $nextStepRegister; ?>"><?php esc_html_e('Save changes', 'delipress'); ?></button>

    <?php if(empty($send) || $send === "now" || ($send === "later" && !$isSend && empty($campaignProviderId))): ?>
        <button type="submit" class="delipress__button delipress__button--main delipress-js-step-submit-prevent" data-next-step="2">
            <?php esc_html_e('Choose template', 'delipress'); ?>
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
    <?php endif; ?>
</footer>
