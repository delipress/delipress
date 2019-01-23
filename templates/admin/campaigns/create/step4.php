<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$list    = $this->campaign->getLists();
$resultVerifyList= false;
if(!empty($list->getId())){
    $resultVerifyList = true;
}


$subject            = $this->campaign->getSubject();
$isSend             = $this->campaign->getIsSend();
$send               = $this->campaign->getSend();
$campaignProviderId = $this->campaign->getCampaignProviderId();
$options            = $this->optionServices->getOptions();
$provider = ProviderHelper::getProviderByKey($options["provider"]["key"]);

$btnSendDisabled    = apply_filters(DELIPRESS_SLUG . "_button_send_campaign_is_disabled", false);

$providerApi = $this->providerServices->getProviderApi($provider["key"]);
$authorizeFrom = true;
switch($provider["key"]){
    case ProviderHelper::SENDGRID:
        $response = $providerApi->getSenderId();
        if(!$response["success"]){
            $authorizeFrom = false;
            $msgAuthorizeForm = sprintf(__("SendGrid needs to send this campaign with : %s", "delipress"), implode(", ", $response["results"]));
        }
        break;
}

$errors   = 0;

?>

<h1><?php esc_html_e("Step 4", "delipress"); ?> <small><?php esc_html_e("Check and send", "delipress"); ?></small></h1>

<div class="delipress__summary">

    <div class="delipress__summary__datas">
        <?php if($send === "later" && !$isSend && !empty($campaignProviderId)): ?>
            <div class="delipress__resave">
                <p><?php _e("Warning: Campaign is already scheduled. If you change anything, you'll need to reschedule this campaign.", "delipress") ?></p>
            </div>
        <?php endif; ?>


        <p class="delipress__intro"><?php esc_html_e("Almost done! Just check everything is ok, then send or schedule your campaign.", "delipress"); ?></p>

        <h2><?php esc_html_e('Campaign summary', "delipress"); ?> </h2>

        <p><?php esc_html_e("Is everything alright?", "delipress"); ?></p>

        <table class="delipress__summary__datas__table">
            <tr>
                <td>
                    <?php if(!empty($subject) ): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php
                        else:
                        $errors++
                    ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("Subject", "delipress"); ?> :</td>
                <td><?php echo $subject; ?></td>
                <td><a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1, $this->campaign->getId()); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <?php if($resultVerifyList): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php
                        else:
                        $errors++
                    ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("To", "delipress"); ?> :</td>
                <td>
                    <?php if($resultVerifyList ):
                    ?>
                        <a href="<?php echo $this->listServices->getSinglePageListUrl($list->getId()); ?>" class="delipress__tag">
                            <?php echo $list->getName(); ?> (<?php echo $list->countSubscribers(); ?> <?php esc_html_e("subscribers", "delipress"); ?>)
                        </a>
                    <?php endif;?>
                </td>
                <td>
                    <a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1, $this->campaign->getId()); ?>" class="dashicons-before dashicons-edit"></a>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        $authorize = true;
                        switch($send){
                            case "later":
                                $dateSend = $this->campaign->getDateSend();
                                $date     = new DateTime($dateSend);
                                $date = $date->getTimestamp();
                                $now      = new DateTime("now");
                                $now = $now->getTimestamp();
                                if($date < $now){
                                    $authorize = false;
                                }
                                break;
                        }
                    ?>
                    <?php if(!empty($send) && $authorize): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php
                        else:
                        $errors++
                    ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("When", "delipress"); ?> :</td>
                <td>
                    <?php
                    switch($send){
                        case "now":
                            esc_html_e("Now", "delipress");
                            break;
                        case "later":
                            echo esc_html($this->campaign->getDateSend(true) );
                            break;
                    }
                    ?>
                </td>
                <td><a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1, $this->campaign->getId()); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <?php
                    if(
                        isset($options["options"]["from_to"]) &&
                        !empty($options["options"]["from_to"]) &&
                        $authorizeFrom
                    ): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php else: ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("Sender", "delipress"); ?> :</td>
                <td>
                    <?php echo (isset($options["options"]["from_to"]) ) ? esc_html($options["options"]["from_to"]) : ""; ?>
                    <?php if(!$authorizeFrom): ?>
                        <br />
                        <em><?php echo $msgAuthorizeForm ?></em>
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <?php if(isset($options["options"]["from_name"]) && !empty($options["options"]["from_name"]) ): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php
                        else:
                        $errors++
                    ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("From name", "delipress"); ?> :</td>
                <td><?php echo (isset($options["options"]["from_name"]) ) ? esc_html($options["options"]["from_name"]) : ""; ?></td>
                <td><a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                </td>
                <td><?php esc_html_e("Reply to", "delipress"); ?> :</td>
                <td><?php echo (isset($options["options"]["reply_to"]) ) ? esc_html($options["options"]["reply_to"]) : ""; ?></td>
                <td><a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <?php if($provider ): ?>
                        <div class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></div>
                    <?php
                        else:
                        $errors++
                    ?>
                        <div class="delipress__task delipress__task--fail"><span class="dashicons dashicons-no"></span></div>
                    <?php endif; ?>
                </td>
                <td><?php esc_html_e("Via", "delipress"); ?> :</td>
                <td>
                    <?php if($provider ): ?>
                        <?php echo esc_html($provider["label"]); ?>
                    <?php
                        else:
                        $errors++
                    ?>
                        <em><?php esc_html_e("No providers defined", "delipress"); ?></em>
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="dashicons-before dashicons-edit"></a></td>
                <td></td>
            </tr>
        </table>


        <h2><?php esc_html_e('Send test email', "delipress"); ?> </h2>

        <?php
        switch($provider["key"]){
            case ProviderHelper::SENDINBLUE:
                ?>
                 <div class="delipress__resave">
                    <p><?php _e("Warning : SendinBlue wont send the test if the given email isn't registered in a list.", "delipress")  ?></p>
                </div>
                <?php
                break;
        }

        ?>

        <p><?php esc_html_e("You should always test your campaign before sending it.", "delipress"); ?></p>

        <div class="delipress__settings">

            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="send_to"><?php esc_html_e('Email', 'delipress'); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f5">
                    <input
                        id="delipress_send_to"
                        name="send_to"
                        type="email"
                        class="delipress__input"
                        value="<?php echo (isset($options["options"]["from_to"])) ? esc_attr($options["options"]["from_to"]) : ""; ?>"
                    />
                </div>
                <div class="delipress__settings__item__help delipress__f3">
                    <button
                        id="delipress-send-test"
                        data-action="<?php echo ActionHelper::CREATE_CAMPAIGN_SEND_TEST ?>"
                        data-campaign-id="<?php echo $this->campaign->getId(); ?>"
                        class="delipress__button delipress__button--soft">
                        <?php esc_html_e("Send test", "delipress"); ?>
                    </button>
                </div>
            </div>
        </div> <!-- delipress__settings -->


        <h2><?php esc_html_e('Launching Campaign', "delipress"); ?> </h2>

        <p><?php esc_html_e("Ready for take off? Tell the world how awesome you are...", "delipress"); ?></p>

        <?php 
            $sendCampaignUrl = $this->campaignServices->getSendCampaignUrl($this->campaign->getId());     
        ?>

        <div class="delipress__launch">
            <?php if($errors === 0): ?>
                <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/rocket.gif" alt="DeliPress">
                <br />
                <?php if($btnSendDisabled): ?>
                    <div class="delipress__button delipress__button--step_submit delipress__button--main delipress__button--big delipress__button--demo-disabled">
                         <?php _e("Send Campaign", "delipress"); ?> <?php _e("(Disabled)", "delipress") ?>
                    </div>
                <?php else: ?>
                    <a id="delipress-send" class="delipress__button delipress__button--step_submit delipress__button--main delipress__button--big" href="<?php echo $sendCampaignUrl; ?>" >
                        <?php if($send === "now"): ?>
                            <?php _e("Send Campaign", "delipress"); ?>
                        <?php else: ?>
                            <?php if($send === "later" && !$isSend && !empty($campaignProviderId)): ?>
                                <?php _e("Re-Schedule Campaign", "delipress"); ?>
                            <?php else: ?>
                                <?php _e("Schedule Campaign", "delipress"); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <p>
                    <?php esc_html_e("Warning : some information is missing", "delipress"); ?>
                </p>
            <?php endif; ?>
        </div>

    </div>

    <div class="delipress__summary__preview delipress__preview">
        <div id="delipress-react-selector"></div>
    </div>

</div>


<script>
    DELIPRESS_ENV             = "<?php echo esc_js( (DELIPRESS_LOGS) ? "DEV" : "PROD") ?>";
    DELIPRESS_API_BASE_URL    = "<?php echo esc_js( admin_url()                 ) ?>"
    DELIPRESS_CAMPAIGN_ID     = "<?php echo esc_js( $this->campaign->getId()                    ) ?>"
    WPNONCE_AJAX              = "<?php echo esc_js( wp_create_nonce(ActionHelper::REACT_AJAX)   ) ?>"
    DELIPRESS_PATH_PUBLIC_IMG = "<?php echo esc_js( DELIPRESS_PATH_PUBLIC_IMG ) ?>"
    require('javascripts/react/modules/preview/initializeStep3');

</script>
