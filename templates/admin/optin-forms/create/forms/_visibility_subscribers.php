<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\OptinHelper;

$behaviors  = $this->optin->getBehavior();

$defaultValue = false;
switch($this->optin->getType()){
    case OptinHelper::POPUP:
    case OptinHelper::FLY:
        $defaultValue = true;
        break;
}

$valueChecked = (bool) AdminFormValues::displayOldValues("visibility_subscribers", 
    (isset($behaviors["visibility_subscribers"])) ? 
    $behaviors["visibility_subscribers"] :
    $defaultValue
);

?>

<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="visibility_subscribers"><?php _e("Hide once subscribed", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <input 
            type="checkbox" 
            id="visibility_subscribers" 
            class="delipress__checkbox__input" 
            name="visibility_subscribers"
            <?php if($valueChecked): ?>checked="checked"<?php endif; ?>
        />
        <label for="visibility_subscribers" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>


