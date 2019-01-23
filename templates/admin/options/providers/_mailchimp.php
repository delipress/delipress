<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$provider       = ProviderHelper::getProviderByKey(ProviderHelper::MAILCHIMP);
$providerOption = $this->optionServices->getProvider();

?>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost("providers"); ?>" method="post" id="form_page">
    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <div class="delipress__provider">
        <div class="delipress__provider__form">
            <p class="delipress__provider__form__letsconnect">
                <?php if($providerOption["key"] != ProviderHelper::MAILCHIMP){ esc_html_e("Let's connect to ", 'delipress'); }?>
                <img src="<?php echo esc_html($provider["img_src"]) ?>" alt="Mailjet"> 
                <?php if(!$disabledForm): ?>
                    (<a href="<?php echo $this->optionServices->getPageUrl(); ?>" class="js-delipress-wizard-change-provider"><?php esc_html_e("or change", 'delipress'); ?></a>)
                <?php endif; ?>
            </p>
            <?php if($providerOption["key"] == ProviderHelper::MAILCHIMP && $providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--done"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Successfully connected", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <?php if($providerOption["key"] == ProviderHelper::MAILCHIMP && !$providerOption["is_connect"]): ?>
                <p class="delipress__center">
                    <span class="delipress__task delipress__task--fail"><span class="dashicons dashicons-yes"></span></span> <?php esc_html_e("Invalid key", 'delipress'); ?>
                </p>
            <?php endif; ?>
            <div class="delipress__settings">

                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="api_key_mailchimp"><?php esc_html_e( 'API Key' , 'delipress' ); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <?php
                            $readOnly = false;
                            $clientId = (
                                $this->options["provider"] &&
                                array_key_exists("api_key_mailchimp", $this->options["provider"])
                            ) ? $this->options["provider"]["api_key_mailchimp"] : "";

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
                            id="api_key_mailchimp"
                            name="<?php echo sprintf("%s[provider][api_key_mailchimp]", OptionHelper::OPTIONS_NAME); ?>"
                            type="text"
                            class="delipress__input <?php if(
                                array_key_exists("_" . CodeErrorHelper::MAILCHIMP_API_KEY, $errors) || 
                                ($providerOption["key"] == ProviderHelper::MAILCHIMP && !$providerOption["is_connect"])
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
                <?php if($providerOption["key"] != ProviderHelper::MAILCHIMP || $providerOption["key"] == ProviderHelper::MAILCHIMP && !$providerOption["is_connect"]): ?>
                    <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e("Save and connect", 'delipress'); ?></button>
                <?php endif; ?>
                <?php if($disabledForm): ?>
                    <div class="delipress__button delipress__button--soft delipress__button--demo-disabled"><?php _e('Disconnect', 'delipress'); ?> <?php _e("(Disabled)", "delipress"); ?></div>
                <?php else: ?>
                    <?php if($providerOption["key"] == ProviderHelper::MAILCHIMP && $providerOption["is_connect"]): ?>
                        <a href="<?php echo $this->providerServices->getUrlDisconnectProvider(ProviderHelper::MAILCHIMP); ?>" class="delipress__button delipress__button--soft"><?php esc_html_e("Disconnect", 'delipress'); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="delipress__provider__help">
            <h3><?php esc_html_e("About Mailchimp", 'delipress'); ?></h3>

            <p><?php esc_html_e("MailChimp is an email marketing service founded in 2001 sending over 10 billion emails per month.", 'delipress'); ?></p>
            <p><?php esc_html_e("Prices", 'delipress'); ?> : <?php esc_html_e("Free plan up to 2,000 subscribers and 12,000 emails per month. Pro plans starts at $10 per month.",'delipress'); ?></p>

            <h3><?php esc_html_e("Help", 'delipress'); ?></h3>

            <ol>
                <li><?php _e("First, you need to create an account on <a href='https://mailchimp.com/' target='_blank'>Mailchimp website</a>",'delipress'); ?></li>
                <li>
                    <?php _e(
                        sprintf('Then, go to your <a href="%s" target="_blank">MailChimp Dashboard > Account > Extras > API Keys</a> and create a key', $provider["url_api_key"]), 'delipress'
                    );
                    ?>
                </li>
                <li><?php esc_html_e("Copy and paste API Key here", 'delipress'); ?></li>
                <li><?php esc_html_e("We will connect and sync your subscribers", 'delipress'); ?></li>
            </ol>

            <h3><?php esc_html_e("Why do I need an email provider?", 'delipress'); ?></h3>

            <p><?php esc_html_e("Your webhost is not designed to send emails to massive lists. They will eventually end up as spams. Email providers have servers dedicated to sending massive email campaigns to huge lists.",'delipress'); ?></p>

        </div>
    </div>
    <input type="hidden" name="provider" value="<?php echo esc_attr($provider["key"]); ?>" />
</form>
