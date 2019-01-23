<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Services\Wizard\WizardServices;

$provider = $this->optionServices->getProvider();
$lists    = $this->listServices->getLists();

include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php");

?>

<div class="delipress__hero">
    <img class="delipress__hero__logo" src="<?php echo DELIPRESS_PATH_PUBLIC_IMG ?>/deli-white.svg" alt="DeliPress">
    <a class="delipress__hero__skip" href="<?php echo $this->wizardServices->removeSetupWizardPage(); ?>"><?php esc_html_e("I know what I'm doing, remove this wizard.", 'delipress'); ?> <span class="dashicons dashicons-dismiss"></span></a>
    <div class="delipress__hero__content">
        <h1><?php esc_html_e("Ready to deliver delicious newsletters?", "delipress"); ?></h1>
        <p><?php esc_html_e("DeliPress allows you to easily manage your email marketing process from visitor subscription to campaign customization and sending.", "delipress"); ?></p>
        <p>
            <a href="<?php echo $this->wizardServices->getPageWizard(); ?>" class="delipress__button delipress__button--main">
                <?php esc_html_e('Start configuration', 'delipress'); ?>
            </a>
        </p>
    </div>
</div>

<section class="delipress__section">
    <h1><?php esc_html_e("Features","delipress"); ?></h1>
    <p><?php esc_html_e("We have a lot of features for you ! Want more details ? Discover some of them:","delipress"); ?></p>

    <div class="delipress__features">
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Opt-In Forms", "delipress"); ?></h3>
            <div class="delipress__features__item__image">
                <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/features/capture.png" alt="<?php esc_html_e("Opt-In Forms", "delipress"); ?>">
            </div>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("DeliPress provides a lot of ways to collect your users' email addresses. You can create and customize the behavior, look and feel of your forms easily.", "delipress") ?></p>
            </div>
            <p class="delipress__features__item__action">
                <!-- <a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more", "delipress"); ?></a> | -->
                <a class="delipress__button delipress__button--main delipress__button--small" href="<?php echo $this->optinServices->getPageUrl(); ?>"><?php esc_html_e("Create your first Opt-In form", "delipress"); ?></a>
            </p>
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Campaign Visual Editor", "delipress"); ?></h3>
            <div class="delipress__features__item__image">
                <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/features/build.png" alt="<?php esc_html_e("Campaign Visual Editor", "delipress"); ?>">
            </div>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("The DeliPress drag'n'drop visual builder allows you to add text, images, columns, posts, and WooCommerce products in just a few clicks.","delipress"); ?></p>
            </div>
            <p class="delipress__features__item__action">
                <!-- <a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more", "delipress"); ?></a> |  -->
                <a class="delipress__button delipress__button--main delipress__button--small"  href="<?php echo $this->campaignServices->getPageUrl(); ?>"><?php esc_html_e("Create your first campaign", "delipress"); ?></a>
            </p>
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Beautiful templates", "delipress"); ?></h3>
            <div class="delipress__features__item__image">
                <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/features/templates.png" alt="<?php esc_html_e("Beautiful templates", "delipress"); ?>">
            </div>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("Your newsletter is unique. Customize it the way you want. You can also create, save and reuse templates.", "delipress"); ?></p>
            </div>
            <!-- <p>
                <a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more", "delipress"); ?></a>
            </p> -->
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Email providers", "delipress"); ?></h3>
            <div class="delipress__features__item__image">
                <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG; ?>/features/deliver.png" alt="<?php esc_html_e("Email providers", "delipress"); ?>">
            </div>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("The choice is yours ! Standard webhosts are not designed to send email campaigns to massive lists. Instead, plug in your favorite email service, like Mailchimp, Mailjet, and more.", "delipress"); ?></p>
            </div>
            <p class="delipress__features__item__action">
                <!-- <a href="#" class="delipress-help-coming"><?php esc_html_e("Why use a provider ?","delipress"); ?></a> |  -->
                <a  class="delipress__button delipress__button--soft delipress__button--small"  href="<?php echo $this->optionServices->getPageUrl(); ?>"><?php esc_html_e("Configure a provider","delipress"); ?></a>
            </p>
        </div>
    </div>

    <h1><?php esc_html_e("For developers", "delipress"); ?></h1>

    <p><?php esc_html_e("You are a developer ? You can easily customize the DeliPress experience and create your own addons.","delipress"); ?></p>


    <div class="delipress__features">
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Performance","delipress"); ?></h3>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("DeliPress is built with performance in mind. We don't use overweight libs, and we just load the minimum we need.","delipress"); ?></p>
            </div>
            <!-- <p><a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more","delipress"); ?></a></p> -->
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Hooks", "delipress"); ?></h3>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("DeliPress provides lots of actions and filters. You can easily plug your features and even build your own add-ons.","delipress"); ?></p>
            </div>
            <!-- <p><a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more","delipress"); ?></a></p> -->
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e( "MJML", "delipress" ); ?></h3>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("We use MJML to compile your campaigns. Emails are natively responsive. The code is clean.","delipress"); ?> <a target="_blank" href="https://mjml.io/"><?php esc_html_e("Learn more","delipress"); ?></a></p>
            </div>
        </div>
        <div class="delipress__features__item delipress__c14">
            <h3><?php esc_html_e("Further customisation","delipress"); ?></h3>
            <div class="delipress__features__item__desc">
                <p><?php esc_html_e("We are devs too, and we understand that you need more control about customization. We\'ve got you covered !", "delipress"); ?></p>
            </div>
            <!-- <p><a href="#" class="delipress-help-coming"><?php esc_html_e("Learn more","delipress"); ?></a></p> -->
        </div>
    </div>
</section>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php"); ?>
