<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;

$this->currentTab = "others";

$disabledForm = apply_filters(DELIPRESS_SLUG . "_disabled_form_options", false);

?>
<h1><?php esc_html_e("Other settings", "delipress"); ?></h1>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost($this->currentTab); ?>" method="post" id="form_page" enctype="multipart/form-data">

    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <div class="delipress__settings">
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="delipress_show_setup"><?php esc_html_e('Setup Wizard', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    type="checkbox"
                    id="delipress_show_setup"
                    class="delipress__checkbox__input"
                    name="<?php echo sprintf("%s[others][show_setup]", OptionHelper::OPTIONS_NAME); ?>"
                    value="1"
                    <?php checked( $this->options[$this->currentTab]["show_setup"], 1 ); ?>
                />
                <label for="delipress_show_setup" class="delipress__checkbox"><?php esc_html_e('Show Wizard Page from DeliPress menu', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4"></div>
        </div>
        <?php if(!$disabledForm): ?>
            <button type="submit" class="delipress__button delipress__button--save"><?php _e('Save settings', 'delipress'); ?></button>
        <?php else: ?>
            <div class="delipress__button delipress__button--save delipress__button--demo-disabled"><?php _e('Save settings', 'delipress'); ?> <?php  _e("(Disabled)", "delipress"); ?></div>
        <?php endif; ?>

    </div> <!-- delipress__settings -->

</form>
