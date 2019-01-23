<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ConnectorHelper;

$provider = $this->optionServices->getProvider();

$licenseStatusValid  = $this->optionServices->isValidLicense();
$fullLicense         = $this->optionServices->isFullLicense();

$options = $this->optionServices->getOptions();
$optionConnectors = $options["connectors"];

?>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/header_no_forms.php"); ?>

<h1>
    <?php echo esc_html__(get_admin_page_title(), "delipress"); ?>
    <small>
        <?php esc_html_e("Add new list", "delipress"); ?>
    </small>
</h1>

<p class="delipress__intro"><?php _e("What kind of list do you want to create?", "delipress"); ?></p>

<div class="delipress__new-list-choices">
    <div class="delipress__new-list-choices__col">
        <h2><?php _e('Start a new list', 'delipress'); ?></h2>

        <a href="<?php echo $this->listServices->getCreateUrl(); ?>" class="delipress__new-list-choices__block delipress__new-list-choices__block--big">
            <div class="delipress__new-list-choices__block__picto delipress__new-list-choices__block__picto--3">
                <span class="dashicons dashicons-plus-alt"></span>
            </div>
            <h3><?php _e('New empty list', 'delipress'); ?></h3>
            <p><?php _e('Start from scratch with a brand new list', 'delipress'); ?></p>
        </a>

        <?php if ($licenseStatusValid) : ?>
            <a href="<?php echo $this->listServices->getCreateListDynamicUrl(); ?>" class="delipress__new-list-choices__block delipress__new-list-choices__block--big">
                <div class="delipress__new-list-choices__block__picto delipress__new-list-choices__block__picto--1">
                    <span class="dashicons dashicons-networking"></span>
                </div>
                <h3><?php _e('New Dynamic list', 'delipress'); ?></h3>
                <p><?php _e('Powerful and intelligent lists', 'delipress'); ?></p>
            </a>
        <?php else : ?>
            <div class="delipress__new-list-choices__block delipress__new-list-choices__block--big">
                <span class="delipress__premium-only">
                    <span class="dashicons dashicons-awards"></span>
                </span>
                <div class="delipress__premium__blur">
                    <div class="delipress__new-list-choices__block__picto delipress__new-list-choices__block__picto--1">
                        <span class="dashicons dashicons-networking"></span>
                    </div>
                    <h3><?php _e('New Dynamic list', 'delipress'); ?></h3>
                    <p><?php _e('Powerful and intelligent lists', 'delipress'); ?></p>
                </div>
                <div class="delipress__premium__collection-item__stamp">
                    <div class="delipress__center-all">
                        <a class="delipress__button delipress__button--premium" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                            <?php esc_html_e("Upgrade to premium", "delipress"); ?>
                        </a>
                        <span><?php esc_html_e('This feature in only available to premium member', 'delipress'); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <div class="delipress__new-list-choices__col">
        <h2><?php _e('Special lists', 'delipress'); ?></h2>

        <div class="delipress__new-list-choices__item">
            <div class="delipress__new-list-choices__item__picto">
                <span class="dashicons dashicons-admin-users"></span>
            </div>
            <div class="delipress__new-list-choices__item__content">
                <h3><?php _e('WordPress Users', 'delipress'); ?></h3>
                <p><?php _e('Create a list from your WordPress users', 'delipress'); ?></p>
                <form action="<?php echo $this->optionServices->getUrlFormAdminPost("connectors"); ?>" method="post" id="form_page" enctype="multipart/form-data">

                    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

                    <?php if( $optionConnectors[ConnectorHelper::WORDPRESS_USER]["active"]): ?>
                        <p><button type="submit" class="delipress__button"><?php _e('Deactivate', 'delipress'); ?></button></p>
                    <?php else: ?>
                        <input
                            type="hidden"
                            name="_delipress_options[connectors][<?php echo ConnectorHelper::WORDPRESS_USER ?>][active]"
                                value="1"
                        >
                        <p><button type="submit" class="delipress__button delipress__button--second"><?php _e('Activate', 'delipress'); ?></button></p>
                    <?php endif; ?>

                    <?php if( isset($optionConnectors[ConnectorHelper::WOOCOMMERCE]) && $optionConnectors[ConnectorHelper::WOOCOMMERCE]["active"]): ?>
                        <input
                            type="hidden"
                            name="_delipress_options[connectors][<?php echo ConnectorHelper::WOOCOMMERCE ?>][active]"
                                value="1"
                        >
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
        $connector = ConnectorHelper::getConnectorByKey(ConnectorHelper::WOOCOMMERCE);
        if(
            !$connector["premium"] ||
            ($connector["premium"] && !$connector["full_premium"] && $licenseStatusValid) ||
            ($connector["full_premium"] && $licenseStatusValid && $fullLicense)
        ): ?>
        <div class="delipress__new-list-choices__item">
            <div class="delipress__new-list-choices__item__picto">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="delipress__new-list-choices__item__content">
                <h3><?php _e('WooCommerce Customers', 'delipress'); ?></h3>

                <?php

                    $load = true;
                    switch($connector["key"]){
                        case ConnectorHelper::WOOCOMMERCE:
                            if(!is_plugin_active("woocommerce/woocommerce.php")){
                                $load = false;
                            }
                            break;
                    }

                    if($load):
                ?>
                        <p><?php _e('Create a list from your Customers', 'delipress'); ?></p>
                        <form action="<?php echo $this->optionServices->getUrlFormAdminPost("connectors"); ?>" method="post" id="form_page" enctype="multipart/form-data">

                            <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>
                            <?php if( $optionConnectors[ConnectorHelper::WOOCOMMERCE]["active"]): ?>
                                <p><button type="submit" class="delipress__button"><?php _e('Deactivate', 'delipress'); ?></button></p>
                            <?php else: ?>
                                <input
                                    type="hidden"
                                    name="_delipress_options[connectors][<?php echo ConnectorHelper::WOOCOMMERCE ?>][active]"
                                        value="1"
                                >
                                <p><button type="submit" class="delipress__button delipress__button--second"><?php _e('Activate', 'delipress'); ?></button></p>
                            <?php endif; ?>
                            <?php if( $optionConnectors[ConnectorHelper::WORDPRESS_USER]["active"]): ?>
                                <input
                                    type="hidden"
                                    name="_delipress_options[connectors][<?php echo ConnectorHelper::WORDPRESS_USER ?>][active]"
                                        value="1"
                                >
                            <?php endif; ?>
                        </form>
                    <?php
                    else:
                        _e("WooCommerce is required", "delipress");
                    endif;
                ?>
            </div>
        </div>
        <?php else : ?>
        <div class="delipress__new-list-choices__item">
            <span class="delipress__premium-only">
                <span class="dashicons dashicons-awards"></span>
            </span>
            <div class="delipress__premium__blur">
                <div class="delipress__new-list-choices__item__picto">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="delipress__new-list-choices__item__content">
                    <h3><?php _e('WooCommerce Customers', 'delipress'); ?></h3>
                    <p><?php _e('Create a list from your Customers', 'delipress'); ?></p>
                </div>
            </div>
            <div class="delipress__premium__collection-item__stamp">
                <div class="delipress__center-all">
                    <a class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                        <?php esc_html_e("Upgrade to premium", "delipress"); ?>
                    </a>
                    <span><?php esc_html_e('This feature in only available to premium member', 'delipress'); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="delipress__new-list-choices__col">
        <h2><?php _e('Import from somewhere', 'delipress'); ?></h2>

        <a href="<?php echo $this->subscriberServices->getPageSubscribersImport(); ?>" class="delipress__new-list-choices__item">
            <div class="delipress__new-list-choices__item__picto">
                <span class="dashicons dashicons-download"></span>
            </div>
            <div class="delipress__new-list-choices__item__content">
                <h3><?php _e('Import from CSV file', 'delipress'); ?></h3>
                <p><?php _e('Fetch your contacts from elsewhere', 'delipress'); ?></p>
            </div>
        </a>
    </div>
</div>

<?php include_once(DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN . "/footer_no_forms.php"); ?>
