<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Models\CampaignModel;

$provider = $this->optionServices->getProvider();
?>
<h1>
    <?php echo esc_html__(get_admin_page_title(),"delipress"); ?></span> <small><?php esc_html_e("All items", "delipress"); ?></small>
</h1>

<p class="delipress__intro"><?php esc_html_e("Write, customize and send your campaigns from here.","delipress"); ?></p>

<?php
    include_once(__DIR__ . "/_draft.php");
    include_once(__DIR__ . "/_schedules.php");
    include_once(__DIR__ . "/_sent.php");
?>

<script>
    jQuery(document).ready(function(){
        var DelipressPreventChooseAction = require("javascripts/backend/PreventChooseAction")

        var preventChooseActionClass = new DelipressPreventChooseAction(
            "#form_page",
            ".delipress__button--action_choose"
        )

        preventChooseActionClass.init()
    })
</script>
