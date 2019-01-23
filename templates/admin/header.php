<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PageAdminHelper;

$urlAction = "";

switch($this->namePageInclude){
    case PageAdminHelper::PAGE_CAMPAIGNS:
        if($this->currentAction === "create"){
            if(!$this->currentStep){
                $this->currentStep = 1;
            }

            $urlAction = $this->campaignServices->getCreateUrlFormAdminPost((int) $this->currentStep);

        }
        break;

    case PageAdminHelper::PAGE_LISTS:
        switch($this->currentAction){
            case "create":
                $urlAction = $this->listServices->getCreateListUrlFormAdminPost();
                break;
            case "subscribers-create":
                $urlAction = $this->listServices->getCreateSubscriberOnListUrlFormAdminPost();
                break;
            case "subscribers-import":
                if(!$this->currentStep){
                    $this->currentStep = 1;
                }
                $urlAction = $this->listServices->getImportSubscriberOnListUrlFormAdminPost((int) $this->currentStep);
                break;
            case "dynamic":
                $urlAction = $this->listServices->getCreateDynamicListUrlFormAdminPost();
                break;
        }
        break;
    case PageAdminHelper::PAGE_OPTIN_FORMS:
        if(
            $this->currentAction === "create"
        ){
            if(!$this->currentStep){
                $this->currentStep = 1;
            }
            $urlAction = $this->optinServices->getCreateOptinUrlFormAdminPost((int) $this->currentStep);
        }
        break;
    case PageAdminHelper::PAGE_SYNCHRONIZE:
        $urlAction = $this->synchronizeServices->getUrlFormAdminPost($this->provider["key"]);
        break;
}

?>

<form action="<?php echo $urlAction; ?>" method="post" id="form_page" enctype="multipart/form-data">
    <?php include_once(__DIR__ . "/_header.php" );?>
