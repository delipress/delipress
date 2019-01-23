<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors  = $this->optin->getBehavior();
$afterScrolling = (bool) AdminFormValues::displayOldValues("trigger_after_scrolling", 
    (isset($behaviors["trigger_after_scrolling"])) ?
    $behaviors["trigger_after_scrolling"] :
    false
);
$afterScrollingPercent =  AdminFormValues::displayOldValues("after_scrolling_percent",
    (isset($behaviors["after_scrolling_percent"])) ? 
    $behaviors["after_scrolling_percent"] : 
    0
);



?>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="trigger_after_scrolling"><?php esc_html_e("After scrolling", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input
            type="checkbox"
            autocomplete="off"
            id="trigger_after_scrolling"
            class="delipress__checkbox__input delipress__checkbox__reveal"
            name="trigger_after_scrolling"
            data-reveal="after_scrolling_percent-wrap"
            <?php if($afterScrolling): ?>checked="checked"<?php endif; ?>
        />
        <label for="trigger_after_scrolling" class="delipress__checkbox"></label>

        <div id="after_scrolling_percent-wrap" class="delipress__numberinput delipress__input-reveal <?php if($afterScrolling): ?>delipress__is-visible<?php endif; ?>">
            <input
                id="after_scrolling_percent"
                name="after_scrolling_percent"
                type="number"
                min="0"
                class="delipress__input "
                value="<?php echo esc_attr($afterScrollingPercent ); ?>"
            />
            <span class="delipress__numberinput__suffix">%</span>
        </div>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>
