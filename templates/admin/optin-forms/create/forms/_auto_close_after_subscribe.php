<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors  = $this->optin->getBehavior();
$autoClose = (bool) AdminFormValues::displayOldValues("auto_close_after_subscribe", 
    (isset($behaviors["auto_close_after_subscribe"])) ? 
    $behaviors["auto_close_after_subscribe"] :
    false
);

?>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="auto_close_after_subscribe"><?php esc_html_e("Auto close after subscribe", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input 
            type="checkbox" 
            id="auto_close_after_subscribe" 
            class="delipress__checkbox__input" 
            name="auto_close_after_subscribe"
            <?php if($autoClose): ?>checked="checked"<?php endif; ?>
        />
        <label for="auto_close_after_subscribe" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>


