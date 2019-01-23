<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;

$providers = ProviderHelper::getListProviders();

$stickyProvider = null;
foreach($providers as $key => $provider){
    if($provider["sticky"]){
        unset($providers[$key]);
        $stickyProvider = $provider;
    }
}

?>
<div class="delipress__wizard">
    <?php include_once __DIR__ . "/_btn_quit.php"; ?>
    <div class="delipress__wizard__modal">
        <?php include_once __DIR__ . "/_nav.php"; ?>
        <div class="delipress__wizard__modal__main">
            <?php
            if(!isset($_GET["provider"])) :
                include_once __DIR__ . "/step1/_index.php";
            else:
                if(isset($_GET["tab"])){
                    switch($_GET["tab"]){
                        case "send":
                            include_once __DIR__ . "/step1/_send.php";
                            break;
                        case "finish":
                            include_once __DIR__ . "/step1/_finish.php";
                            break;
                        case "connect":
                        default:
                            include_once __DIR__ . "/step1/_connect.php";
                            break;
                    }
                }
                else{
                    include_once __DIR__ . "/step1/_connect.php";
                }
            endif;
            ?>
        </div>
    </div>

</div>
