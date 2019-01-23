<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;

$errors         = ErrorFieldsNoticesHelper::getErrorNotices();
$providerOption = $this->optionServices->getProvider();

include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php");

include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/tabs.php");

if(file_exists(__DIR__ .  sprintf("/tab_%s.php", $this->currentTab) ) ) {
    include_once(__DIR__ .  sprintf("/tab_%s.php", $this->currentTab) );
}

include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php");
