<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors  = $this->optin->getBehavior();
$hideMobile = (bool) AdminFormValues::displayOldValues("hide_on_mobile", 
    (isset($behaviors["hide_on_mobile"])) ?
    $behaviors["hide_on_mobile"] : 
    false
);

?>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="hide_on_mobile"><?php esc_html_e("Hide on mobile", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input 
            type="checkbox" 
            id="hide_on_mobile" 
            class="delipress__checkbox__input" 
            name="hide_on_mobile"
            <?php if($hideMobile): ?>checked="checked"<?php endif; ?>
        />
        <label for="hide_on_mobile" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>


