<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PageAdminHelper;

?>

<header class="delipress__header">
    <div class="delipress__header__topbar">
        <img class="delipress__header__topbar__logo" src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/logo-text.svg" alt="DeliPress">
        <span class="delipress__header__topbar__hint"><?php esc_html_e("Deliver Delicious newsletters on WordPress.","delipress"); ?></span>
    </div>

    <?php do_action(DELIPRESS_SLUG . "_admin_notices_info"); ?>
    <?php do_action(DELIPRESS_SLUG . "_admin_notices_success"); ?>
    <?php do_action(DELIPRESS_SLUG . "_admin_notices_error"); ?>

    <?php
        $menuInclude = PageAdminHelper::getMenuIncludeAdmin($this->namePageInclude);
        if(
            file_exists($menuInclude) &&
            $this->namePageInclude != "options" &&
            $this->namePageInclude != "setup"
        ){
            ?>
                <div class="delipress__header__actions">
                <?php /* <img class="delipress__header__actions__logo" src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/logo-d.svg" alt="DeliPress"> */ ?>
                <?php include_once($menuInclude); ?>
            </div>
            <?php
        }
        else{
            do_action(DELIPRESS_SLUG . '_admin_template_header_include_' . $menuInclude);
        }
    ?>

</header>

<main class="delipress__content">

    <?php do_action(DELIPRESS_SLUG . "_admin_notices_provider_info"); ?>
    <?php do_action(DELIPRESS_SLUG . "_admin_notices_provider_success"); ?>
    <?php do_action(DELIPRESS_SLUG . "_admin_notices_provider_error"); ?>
