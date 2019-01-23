<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$provider       = ProviderHelper::getProviderByKey(ProviderHelper::SENDGRID);
$providerOption = $this->optionServices->getProvider();

?>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost("providers"); ?>" method="post" id="form_page">
    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <div class="delipress__provider">
        <div class="delipress__provider__form">
            <p class="delipress__provider__form__letsconnect">
                <?php if($providerOption["key"] != ProviderHelper::SENDGRID){ esc_html_e("Let's connect to ", 'delipress'); }?>
                <img src="<?php echo esc_html($provider["img_src"]) ?>" alt="<?php echo $provider["label"] ?>"> (<a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="js-delipress-wizard-change-provider"><?php esc_html_e("or change", 'delipress'); ?></a>)
            </p>
            <?php if($providerOption["key"] == ProviderHelper::SENDGRID && $providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Successfully connected", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <?php if($providerOption["key"] == ProviderHelper::SENDGRID && !$providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--fail"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Invalid key", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <div class="delipress__settings">

                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="api_key_sendgrid"><?php esc_html_e( 'API Key' , 'delipress' ); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <?php
                            $readOnly = false;
                            $clientId = (
                                $this->options["provider"] &&
                                array_key_exists("api_key_sendgrid", $this->options["provider"])
                            ) ? $this->options["provider"]["api_key_sendgrid"] : "";

                            if(
                                $this->options["provider"]["is_connect"] &&
                                !empty($clientId) &&
                                $this->options["provider"]["key"] === $provider["key"]
                            ){
                                $clientId = "****************";
                                $readOnly = true;
                            }

                            if(
                                !empty($clientId) &&
                                $this->options["provider"]["key"] !== $provider["key"]
                            ){
                                $clientId = "";
                            }
                        ?>
                        <input
                            id="api_key_sendgrid"
                            name="<?php echo sprintf("%s[provider][api_key_sendgrid]", OptionHelper::OPTIONS_NAME); ?>"
                            type="text"
                            class="delipress__input <?php if(
                                array_key_exists("_" . CodeErrorHelper::SENDGRID_API_KEY, $errors) ||
                                ($providerOption["key"] == ProviderHelper::SENDGRID && !$providerOption["is_connect"])
                            ): ?> delipress__input--error<?php endif; ?>"
                            value="<?php esc_attr_e( $clientId ); ?>"
                            placeholder="<?php esc_html_e('Enter your API KEY', 'delipress'); ?>"
                            <?php echo ($readOnly) ? "readonly=true"  : "" ?>
                            autocomplete="on"
                        />
                    </div>

                </div>
            </div>
            <p class="delipress__center">
                <?php if($providerOption["key"] != ProviderHelper::SENDGRID || $providerOption["key"] == ProviderHelper::SENDGRID && !$providerOption["is_connect"]): ?>
                    <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e("Save and connect", 'delipress'); ?></button>
                <?php endif; ?>
                <?php if($providerOption["key"] == ProviderHelper::SENDGRID && $providerOption["is_connect"]): ?>
                    <a href="<?php echo $this->providerServices->getUrlDisconnectProvider(ProviderHelper::SENDGRID); ?>" class="delipress__button delipress__button--soft"><?php esc_html_e("Disconnect", 'delipress'); ?></a>
                <?php endif; ?>
            </p>
        </div>
        <div class="delipress__provider__help">

            <h3><?php esc_html_e("About SendGrid", 'delipress'); ?></h3>

            <p><?php esc_html_e("Delivering your transactional and marketing emails through the world's largest cloud-based email delivery platform. Send with confidence.", 'delipress'); ?></p>

            <p><?php esc_html_e("Prices", 'delipress'); ?> : <?php esc_html_e("Free plan the first month. Pro plans starts at $9.95 per month.",'delipress'); ?></p>

            <h3><?php esc_html_e("Help", 'delipress'); ?></h3>

            <ol>
                <li><?php echo sprintf(__("First, you need to create an account on <a href='%s' target='_blank'>SendGrid website</a>",'delipress'), $provider["url_register"]); ?></li>
                <li><?php echo sprintf(__('Then, go in your <a href="%s" target="_blank">SendGrid Dashboard > Settings > API Keys</a> and grab the keys', 'delipress'), $provider["url_api_key"]); ?></a></li>
                <li><?php _e("Create a full access API, simply name it delipress", 'delipress'); ?></li>
                <li><?php _e("You will then be given an API KEY, you need to copy this API Key and save it somewhere safe. That's the key you'll need to link DeliPress and Sendgrid", 'delipress'); ?></li>
                <li><?php esc_html_e("We will connect and sync your subscribers", 'delipress'); ?></li>
            </ol>

            <h3><?php _e("Why do I need an email provider?", 'delipress'); ?></h3>

            <p><?php _e("Your webhost is not designed to send emails to massive lists. They will eventually end up as spams. Email providers have servers dedicated to sending massive email campaigns to huge lists.",'delipress'); ?></p>

        </div>
    </div>
    <input type="hidden" name="provider" value="<?php echo esc_attr($provider["key"]); ?>" />
</form>
