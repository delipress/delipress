<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;

$loadHeaderFooter = true;

switch($this->namePageInclude){
    case PageAdminHelper::PAGE_LISTS:
        if(
            empty($this->currentAction) ||
            $this->currentAction === "subscribers" ||
            $this->currentAction === "connector" ||
            $this->currentAction === "choose-create"
        ) {
            $loadHeaderFooter = false;
        }
        break;
    case PageAdminHelper::PAGE_WIZARD:
    case PageAdminHelper::PAGE_SETUP:
    case PageAdminHelper::PAGE_OPTIONS:
        $loadHeaderFooter = false;
        break;
}

$haveMenu = "delipress--has-menu";
if(in_array($this->namePageInclude, array("options", "setup"))){
    $haveMenu = "";
}

do_action(DELIPRESS_SLUG . "_admin_notices_generals");

?>

<div class="delipress <?php echo $haveMenu; ?>">

<?php

if ($loadHeaderFooter) {
    include_once(__DIR__ . "/header.php");
}

$pageInclude = PageAdminHelper::getPageIncludeAdmin($this->namePageInclude);

if(file_exists($pageInclude)){
    include_once($pageInclude);
}
else{
    do_action(DELIPRESS_SLUG . '_admin_template_include_' . $pageInclude);
}

if ($loadHeaderFooter) {
    include_once(__DIR__ . "/footer.php");
}

?>

</div>
