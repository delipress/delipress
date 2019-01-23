<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors  = $this->optin->getBehavior();
$exitIntent = (bool) AdminFormValues::displayOldValues("exit_intent", 
    (isset($behaviors["exit_intent"])) ?
    $behaviors["exit_intent"] :
    false
);

?>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="exit_intent"><?php esc_html_e("Exit intent", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input 
            type="checkbox" 
            id="exit_intent" 
            class="delipress__checkbox__input" 
            name="exit_intent"
            <?php if($exitIntent): ?>checked="checked"<?php endif; ?>
        />
        <label for="exit_intent" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>


