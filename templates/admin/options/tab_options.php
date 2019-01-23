<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;

$providerOption = $this->optionServices->getProvider();

$transitKey = null;
if (array_key_exists("_" . CodeErrorHelper::TRANSIT_KEY_PROVIDER, $errors)) {
    $transitKey = $errors["_" . CodeErrorHelper::TRANSIT_KEY_PROVIDER];
}
$providerHelper = ProviderHelper::getProviderByKey($providerOption["key"]);

$disabledForm = apply_filters(DELIPRESS_SLUG . "_disabled_form_options", false);

$licenseStatus = $this->optionServices->isValidLicense();
$licenseKey    = (isset($this->options[$this->currentTab]["license_key"])) ? $this->options[$this->currentTab]["license_key"] : "";

$senders = array();
if($providerOption["is_connect"]){

    $providerApi = $this->providerServices->getProviderApi($providerOption["key"]);
    $response    = $providerApi->getSenders();

    if($response["success"]){
        switch($providerOption["key"]){
            case ProviderHelper::SENDGRID:
                foreach($response["results"] as $sender){
                    if($sender["locked"] || !$sender["verified"]["status"]){
                        continue;
                    }

                    $senders[] = $sender["from"]["email"];
                }
                break;
            case ProviderHelper::MAILCHIMP:
                $senders[] = $response["results"]["email"];
                break;
            case ProviderHelper::MAILJET:
                foreach($response["results"] as $sender){

                    if($sender["Status"] !== "Active" || strpos( $sender["Email"], "*@") !== false ){
                        continue;
                    }

                    $senders[] = $sender["Email"];
                }
                break;
            case ProviderHelper::SENDINBLUE:
                foreach($response["results"] as $sender){
                    if(!$sender["active"]){
                        continue;
                    }

                    $senders[] = $sender["from_email"];
                }
                break;
        }
    }
}
?>

<h1><?php echo esc_html__(get_admin_page_title(), "delipress"); ?></h1>

<p class="delipress__intro"><?php _e("Pimp your delipress experience.", "delipress"); ?></p>


<h2><?php _e("Contact informations", "delipress"); ?></h2>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost($this->currentTab); ?>" method="post" id="form_page" enctype="multipart/form-data">

    <div class="delipress__settings">
        <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="from_to"><?php _e('From email', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <?php if(!empty($senders)): ?>
                    <select
                        name="<?php echo sprintf("%s[options][from_to]", OptionHelper::OPTIONS_NAME); ?>"
                        id="from_to"
                        <?php if($disabledForm): ?>readonly <?php endif; ?>
                        class="delipress__input"
                    >
                        <?php if(!in_array($this->options[$this->currentTab]["from_to"], $senders)): ?>
                            <option value="-1"><?php esc_html_e("(You need to select a new sender)", "delipress"); ?></option>
                        <?php endif; ?>
                        <?php foreach($senders as $sender): ?>
                            <option value="<?php echo $sender; ?>" <?php selected($this->options[$this->currentTab]["from_to"], $sender) ?>>
                                <?php echo $sender; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input
                        <?php if($disabledForm): ?>readonly <?php endif; ?>
                        id="from_to"
                        name="<?php echo sprintf("%s[options][from_to]", OptionHelper::OPTIONS_NAME); ?>"
                        type="email"
                        class="delipress__input"
                        value="<?php echo esc_attr( $this->options[$this->currentTab]["from_to"] ); ?>"
                        placeholder="<?php echo esc_attr( $this->options[$this->currentTab]["from_to"] ); ?>"
                    />
                <?php endif; ?>
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <span class="delipress__mandatory"><?php _e("Required", "delipress"); ?></span>
                <p>
                    <?php _e("To add a new sender, you must do it from your email service provider account.", "delipress"); ?>
                    <br>
                    <a href="<?php echo $providerHelper["url_senders"] ?>" target="_blank"><?php echo sprintf(__("Add a sender with %s", "delipress"),$providerHelper["label"]); ?></a>
                </p>

            </div>
        </div>
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="from_name"><?php esc_html_e('From name', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    <?php if($disabledForm): ?>readonly <?php endif; ?>
                    id="from_name"
                    name="<?php echo sprintf("%s[options][from_name]", OptionHelper::OPTIONS_NAME); ?>"
                    type="text"
                    class="delipress__input"
                    value="<?php echo (isset($this->options[$this->currentTab]["from_name"]) ) ?  esc_attr( $this->options[$this->currentTab]["from_name"] ) : ""; ?>"
                    placeholder="<?php esc_html_e("The name people will see when they receive your email", 'delipress'); ?>">
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
                <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::OPTIONS_FROM_NAME); ?>
            </div>
        </div>

        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="reply_to"><?php esc_html_e('Reply to', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    <?php if($disabledForm): ?>readonly <?php endif; ?>
                    id="reply_to"
                    name="<?php echo sprintf("%s[options][reply_to]", OptionHelper::OPTIONS_NAME); ?>"
                    type="email"
                    class="delipress__input <?php if (AdminNoticesHelper::hasError(CodeErrorHelper::OPTIONS_REPLY_TO)) :
?> delipress__input--error<?php
endif; ?>"
                    value="<?php echo (isset($this->options[$this->currentTab]["reply_to"]) ) ? esc_attr( $this->options[$this->currentTab]["reply_to"]) : "" ; ?>"
                />
            </div>
            <div class="delipress__settings__item__help delipress__f5">
                <?php ErrorFieldsNoticesHelper::displayError(CodeErrorHelper::OPTIONS_REPLY_TO); ?>
            </div>
        </div>
        <?php if($disabledForm): ?>
            <div class="delipress__button delipress__button--save delipress__button delipress__button--demo-disabled"><?php _e('Save settings', 'delipress'); ?> <?php _e("(Disabled)", "delipress"); ?></div>
        <?php else: ?>
            <button type="submit" class="delipress__button delipress__button--save"><?php _e('Save settings',   'delipress'); ?></button>
        <?php endif; ?>

    </div> <!-- delipress__settings -->

    <h2><?php _e("DeliPress Premium", "delipress"); ?></h2>

    <div class="delipress__settings">
        <div class="delipress__flex delipress__bloc-premium">
            <div class="delipress__f1">
                <p class="delipress__center">
                    <img width="150" src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/logo.svg" alt="DeliPress Premium">
                </p>
                <p>
                    <?php _e('To unlock all the premium features, please enter your license key below. If you don’t have a licence key, please have a look at ', 'delipress'); ?>
                    <a href="<?php echo delipress_get_url_premium() ?>" target="_blank"><?php esc_html_e('the benefits', 'delipress'); ?></a>
                </p>
                <?php
                    $licenseReadOnly = apply_filters(DELIPRESS_SLUG . "_license_read_only", false);
                ?>
                <div class="delipress__settings delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="license_key"><?php esc_html_e('License Key', 'delipress'); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4 delipress__relative">
                        <?php if($licenseReadOnly): ?>
                             <input
                                id="license_key"
                                type="text"
                                class="delipress__input"
                                value="******************"
                                readonly
                            />
                        <?php else: ?>
                            <input
                                id="license_key"
                                name="<?php echo sprintf("%s[options][license_key]", OptionHelper::OPTIONS_NAME); ?>"
                                type="text"
                                class="delipress__input"
                                value="<?php echo esc_attr( $licenseKey ); ?>"
                                placeholder="<?php echo esc_attr( $licenseKey ); ?>"
                            />
                        <?php endif; ?>
                        <?php
                        if ($licenseStatus) :
                        ?>
                            <div class="delipress__settings__premium-congrats">
                                <span class="dashicons dashicons-awards"></span>
                                <p><?php echo $this->optionServices->getStatusMessageLicense();?></p>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
                <div class="delipress__center delipress__topmargin">
                    <?php if($disabledForm): ?>
                        <div class="delipress__button delipress__button--soft delipress__button--demo-disabled"><?php _e('Deactivate', 'delipress'); ?> <?php _e("(Disabled)", "delipress"); ?></div>
                    <?php else: ?>
                        <?php if ($licenseStatus): ?>
                            <button type="submit" class="delipress__button delipress__button--soft js-empty-license"><?php esc_html_e('Deactivate License', 'delipress'); ?></button>
                        <?php else: ?>
                            <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Activate License', 'delipress'); ?></button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
            <div class="delipress__f1 delipress__bloc-premium__help">
                <h3><?php esc_html_e('Free version', 'delipress'); ?></h3>
                <p>
                    <?php esc_html_e('DeliPress is a free plugin, which allows you to collect visitors\' emails, as well as build and send newsletter to your subscribers. The free version comes with a lot of features to help you start growing your audience and build beautiful campaigns.', 'delipress') ?>
                </p>
                <h3><?php esc_html_e('Why DeliPress premium?', 'delipress'); ?></h3>
                <p><?php _e('The <strong>premium version</strong> removes every limitations and help you get to the next level. It’s built on top of the free plugin and offers:', 'delipress' ); ?></p>
                <p>
                    <ul>
                        <li><span><?php _e('<strong>More and better</strong> Opt-In form options', 'delipress'); ?></span></li>
                        <li><span><?php _e('<strong>Save email templates</strong> and reuse them for your next campaign', 'delipress'); ?></span></li>
                        <li><span><?php _e('Import <strong>WooCommerce products</strong> in your campaigns', 'delipress'); ?></span></li>
                        <li><span><?php _e('<strong>Premium support</strong> to answer your questions', 'delipress'); ?></span></li>
                        <li><span><?php _e('<strong>Complete statistics</strong> for your campaigns and Opt-Ins forms', 'delipress'); ?></span></li>
                    </ul>
                </p>

                <p class="delipress__center delipress__topmargin">
                    <a class="delipress__button delipress__button--main" href="<?php echo delipress_get_url_premium() ?>" target="_blank"><?php esc_html_e('View pricing', 'delipress'); ?></a>
                </p>
            </div>
        </div>


    </div> <!-- delipress__settings -->

</form>

<h2><?php esc_html_e('Email provider', 'delipress'); ?></h2>

<div class="delipress__settings">

    <p><span class="delipress__mandatory"><?php esc_html_e("required", "delipress"); ?></span><br>
    <?php esc_html_e("Choose a way to send your emails.", "delipress"); ?> <br>
    <?php esc_html_e("Many providers offers free plans if you don't send a lot of monthly emails.", "delipress"); ?></p>

    <div id="js-delipress-collection-provider" class="delipress__collection"
        <?php if ($providerOption["is_connect"] || array_key_exists("_" . CodeErrorHelper::TRANSIT_KEY_PROVIDER, $errors)) : ?>
            style="display:none"
        <?php endif; ?>
    >
        <?php foreach (ProviderHelper::getListProviders() as $key => $provider) :
            $classJs = "";
            if ($provider["active"]) {
                $classJs = "js-delipress-choose-provider";
            }

        ?>
            <div class="delipress__collection__item">
                <div class="delipress__collection__item__picture <?php echo $classJs; ?>" data-value="<?php echo $provider["key"]; ?>">
                    <img src="<?php echo esc_attr($provider["img_src"] ); ?>" alt="<?php echo esc_html($provider["label"]) ?>">
                </div>
                <div class="delipress__collection__item__content">
                    <h3 class="delipress__collection__item__title"><?php echo $provider["label"]?></h3>
                    <p><?php echo esc_html($provider["description"] ); ?></p>
                </div>
                <div class="delipress__collection__item__actions">
                    <?php if ($provider["active"]) : ?>
                        <a href="#" class="delipress__button delipress__button--main delipress__button--small js-delipress-choose-provider" data-value="<?php echo esc_attr($provider["key"]); ?>"><?php esc_html_e('Configure', "delipress"); ?></a>
                    <?php else : ?>
                        <strong><?php esc_html_e("Soon", "delipress"); ?></strong>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div> <!-- delipress__collection -->

    <?php foreach (ProviderHelper::getListProviders() as $key => $provider) :
        $file = sprintf(__DIR__ . "/providers/_%s.php", $provider["key"]);
    ?>
        <?php if (file_exists($file)) : ?>
            <div
                id="container-<?php echo $provider["key"]; ?>"
                class="js-container-provider"
                <?php if (($providerOption["is_connect"] && $provider["key"] === $providerOption["key"] )  || $transitKey === $provider["key"]) : ?>
                    style="display:block;"
                <?php else : ?>
                    style="display:none;"
                <?php endif; ?>
            >
                <?php include_once $file; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>


    <script type="text/javascript">
        var $ = jQuery
        jQuery(document).on("ready", function(){
            $(".js-delipress-choose-provider", $("#js-delipress-collection-provider")).on("click", function(e){
                e.preventDefault();

                $("#js-delipress-collection-provider").hide()

                var value = $(this).data("value")
                $("#container-" + value).show();
            })


            $(".js-delipress-wizard-change-provider").on("click", function(e){
                e.preventDefault();
                $(".js-container-provider").hide();
                $("#js-delipress-collection-provider").show()
            })

            $('.js-empty-license').on('click', function(e){
                $('#license_key').val('');
            })
        })
    </script>

</div> <!-- delipress__settings -->
