<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$provider = ProviderHelper::getProviderByKey(ProviderHelper::MAILJET);
$providerOption = $this->optionServices->getProvider();

?>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost("providers"); ?>" method="post" id="form_page" enctype="multipart/form-data">
    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <div class="delipress__provider">
        <div class="delipress__provider__form">
            <p class="delipress__provider__form__letsconnect">
                <?php if($providerOption["key"] != ProviderHelper::MAILJET){ esc_html_e("Let's connect to", 'delipress'); }?>
                <img src="<?php echo $provider["img_src"] ?>" alt="Mailjet"> 
                <?php if(!$disabledForm): ?>
                    (<a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="js-delipress-wizard-change-provider"><?php esc_html_e("or change", 'delipress'); ?></a>)
                <?php endif; ?>

            </p>
            <?php if($providerOption["key"] == ProviderHelper::MAILJET && $providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Successfully connected", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <?php if($providerOption["key"] == ProviderHelper::MAILJET && !$providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--fail"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Invalid key", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <div class="delipress__settings">

                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="client_id"><?php esc_html_e( 'API Key' , 'delipress' ); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <?php
                            $readOnly = false;
                            $clientId = (
                                $this->options["provider"] &&
                                array_key_exists("client_id", $this->options["provider"])
                            ) ? $this->options["provider"]["client_id"] : "";

                            if(
                                $this->options["provider"]["is_connect"] &&
                                !empty($clientId) &&
                                $this->options["provider"]["key"] === $provider["key"]
                            ){
                                $readOnly = true;
                                $clientId = "********************";
                            }

                            if(
                                !empty($clientId) &&
                                $this->options["provider"]["key"] !== $provider["key"]
                            ){
                                $clientId = "";
                            }
                        ?>
                        <input
                            id="client_id"
                            name="<?php echo sprintf("%s[provider][client_id]", OptionHelper::OPTIONS_NAME); ?>"
                            type="text"
                            class="delipress__input <?php if(array_key_exists("_" . CodeErrorHelper::MAILJET_CLIENT_ID, $errors) ): ?> delipress__input--error<?php endif; ?>"
                            value="<?php esc_attr_e( $clientId ); ?>"
                            placeholder="<?php esc_html_e('Enter your API KEY', 'delipress'); ?>"
                            <?php echo ($readOnly) ? "readonly=true"  : "" ?>
                        />
                    </div>

                </div>
                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="client_secret"><?php esc_html_e( 'API Secret' , 'delipress' ); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <?php
                            $readOnly = false;
                            $clientSecret = (
                                $this->options["provider"] &&
                                array_key_exists("client_secret", $this->options["provider"])
                            ) ? $this->options["provider"]["client_secret"] : "";

                            if(
                                $this->options["provider"]["is_connect"] &&
                                !empty($clientSecret) &&
                                $this->options["provider"]["key"] === $provider["key"]
                            ){
                                $clientSecret = "********************";
                                $readOnly = true;
                            }

                            if(
                                !empty($clientSecret) &&
                                $this->options["provider"]["key"] !== $provider["key"]
                            ){
                                $clientSecret = "";
                            }
                        ?>
                        <input
                            id="client_secret"
                            name="<?php echo sprintf("%s[provider][client_secret]", OptionHelper::OPTIONS_NAME); ?>"
                            type="text"
                            class="delipress__input <?php if(
                                array_key_exists("_" . CodeErrorHelper::MAILJET_CLIENT_SECRET, $errors) ||
                                ($providerOption["key"] == ProviderHelper::MAILJET && !$providerOption["is_connect"])
                            ): ?> delipress__input--error<?php endif; ?>"
                            value="<?php esc_attr_e( $clientSecret ); ?>"
                            placeholder="<?php esc_html_e('Enter your SECRET KEY', 'delipress'); ?>"
                            <?php echo ($readOnly) ? "readonly=true"  : "" ?>
                        />
                    </div>

                </div>
            </div>
            <p class="delipress__center">
                <?php if(
                    $providerOption["key"] != ProviderHelper::MAILJET ||
                    ( $providerOption["key"] == ProviderHelper::MAILJET && !$providerOption["is_connect"])
                ): ?>
                    <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e("Save and connect", 'delipress'); ?></button>
                <?php endif; ?>
                <?php if($disabledForm): ?>
                    <div class="delipress__button delipress__button--soft delipress__button--demo-disabled"><?php _e('Disconnect', 'delipress'); ?> <?php _e("(Disabled)", "delipress"); ?></div>
                <?php else: ?>
                    <?php if($providerOption["key"] == ProviderHelper::MAILJET && $providerOption["is_connect"]): ?>
                        <a href="<?php echo $this->providerServices->getUrlDisconnectProvider(ProviderHelper::MAILJET); ?>" class="delipress__button delipress__button--soft"><?php esc_html_e("Disconnect", 'delipress'); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="delipress__provider__help">
            <h3><?php _e("About Mailjet", 'delipress'); ?></h3>

            <p><?php _e("Mailjet is a Paris-based, all-in-one Email Service Provider that allows businesses to send Marketing, Transactional Email and Email Automation.", 'delipress'); ?></p>
            <p><?php _e("Prices", 'delipress'); ?> : <?php _e("Free plan up to 6000 emails per month. Pro plans starts at $7.49 per month.",'delipress'); ?></p>

            <h3><?php _e("Help", 'delipress'); ?></h3>

            <ol>
                <li><?php echo sprintf(__("First, you need to create an account on <a href='%s' target='_blank'>Mailjet website</a>",'delipress'), $provider["url_register"]); ?></li>
                <li><?php _e('Then, go in your <a href="https://app.mailjet.com/account/api_keys" target="_blank">Mailjet Dashboard > Account > API Keys</a> and grab the keys', 'delipress'); ?></a></li>
                <li><?php _e("Copy and paste API Key and Secret Key here", 'delipress'); ?></li>
                <li><?php _e("We will connect and sync your subscribers", 'delipress'); ?></li>
            </ol>

            <h3><?php _e("Why do I need an email provider?", 'delipress'); ?></h3>

            <p><?php _e("Your webhost is not designed to send emails to massive lists. They will eventually end up as spams. Email providers have servers dedicated to sending massive email campaigns to huge lists.",'delipress'); ?></p>

        </div>
    </div>
    <input type="hidden" name="provider" value="<?php echo esc_attr($provider["key"]); ?>" />
</form>
