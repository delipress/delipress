<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ConnectorHelper;
use Delipress\WordPress\Helpers\MarkupIncentiveHelper;

$connectors = ConnectorHelper::getConnectors();

$this->currentTab = "connectors";
$options = $this->optionServices->getOptions();
$optionConnectors = $options[$this->currentTab];

$licenseStatusValid  = $this->optionServices->isValidLicense();
$fullLicense         = $this->optionServices->isFullLicense();

?>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php"); ?>

<h1><?php _e("Connectors", "delipress"); ?></h1>

<p class="delipress__intro">
    <?php esc_html_e("Connectors allow you to build lists using your WordPress data. For example, the WordPress Users connector automatically creates a list named WordPress Users with all your users data.", "delipress"); ?>
</p>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost($this->currentTab); ?>" method="post" id="form_page" enctype="multipart/form-data">

    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <div class="delipress__settings">
        <?php foreach($connectors as $key => $connector): ?>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="delipress_<?php echo $connector['key'] ?>"><?php echo esc_html($connector["label"] ); ?></label>
                </div>
                <div class="delipress__settings__item__field delipress__f3">
                    <?php
                    if(
                        !$connector["premium"] ||
                        ($connector["premium"] && !$connector["full_premium"] && $licenseStatusValid) ||
                        ($connector["full_premium"] && $licenseStatusValid && $fullLicense)
                    ): ?>
                        <?php
                        $load = true;
                        switch($connector["key"]){
                            case ConnectorHelper::WOOCOMMERCE:
                                if(!is_plugin_active("woocommerce/woocommerce.php")){
                                    $load = false;
                                }
                                break;
                        }

                        if($load):
                        ?>
                        <input
                            type="checkbox"
                            id="delipress_<?php echo esc_attr($connector['key']) ?>"
                            class="delipress__checkbox__input"
                            name="<?php echo sprintf("%s[%s][%s][active]", OptionHelper::OPTIONS_NAME, $this->currentTab, $connector["key"]); ?>"
                            value="1"
                            <?php checked( $optionConnectors[$connector["key"]]["active"], 1 ); ?>
                        />
                        <label for="delipress_<?php echo esc_attr($connector['key']) ?>" class="delipress__checkbox">
                            <?php echo esc_html($connector["description"]) ?>
                        </label>
                    <?php
                        else:
                            _e("WooCommerce is required", "delipress");
                        endif;
                    else:
                        MarkupIncentiveHelper::printMarkup("block", array(
                            "title" => esc_html__("Do you want connect your WooCommerce customers ?", "delipress"),
                            "content" => esc_html__("With this connector, we take care of synchronizing all your customers", "delipress")
                        ));
                    endif; ?>
                </div>
                <div class="delipress__settings__item__helper delipress__f5">

                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Save settings', 'delipress'); ?></button>

    </div>

</form>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php"); ?>
