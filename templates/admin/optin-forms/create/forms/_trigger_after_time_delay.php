<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors      = $this->optin->getBehavior();
$afterDelay     = false;
$afterTimeDelay = 0;

if(!empty($behaviors)){
    $afterDelay = (bool) AdminFormValues::displayOldValues(
        "trigger_after_time_delay",
        (isset($behaviors["trigger_after_time_delay"])) ? $behaviors["trigger_after_time_delay"] : false
    );
    $afterTimeDelay = AdminFormValues::displayOldValues(
        "after_time_delay",
        (isset($behaviors["after_time_delay"])) ?  $behaviors["after_time_delay"] : 0
    );
}

?>
<h2><?php esc_html_e('Choose a trigger', 'delipress') ?></h2>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="trigger_after_time_delay"><?php esc_html_e("After delay", "delipress"); ?></label>
        <span title="<?php esc_html_e('Define how much time to wait before the Opt-In appears.', 'delipress'); ?>" class="delipress__tooltip dashicons dashicons-editor-help"></span>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input
            type="checkbox"
            autocomplete="off"
            id="trigger_after_time_delay"
            name="trigger_after_time_delay"
            class="delipress__checkbox__input delipress__checkbox__reveal"
            data-reveal="after_time_delay-wrap"
            <?php if($afterDelay): ?>checked="checked"<?php endif; ?>
        />
        <label for="trigger_after_time_delay" class="delipress__checkbox"></label>
        <div
            id="after_time_delay-wrap"
            class="delipress__numberinput delipress__input-reveal <?php if($afterDelay): ?>delipress__is-visible<?php endif; ?>"
        >
            <input
                id="after_time_delay"
                name="after_time_delay"
                type="number"
                min="0"
                class="delipress__input"
                value="<?php echo esc_attr($afterTimeDelay); ?>"
            />
            <span class="delipress__numberinput__suffix"><?php echo esc_html_x('s' , 'shorthand for second', 'delipress') ?></span>
        </div>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
        <?php esc_html_e('', 'delipress') ?>
    </div>
</div>
