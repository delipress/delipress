<?php

/**
 * Plugin Name: DeliPress - Email marketing
 * Plugin URI: http://delipress.io
 * Description: Deliver Daily Delicious emails from WordPress
 * Version: {VERSION}
 * Author: DeliPress
 * Author URI: https://delipress.io/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: delipress
 * Domain Path: /languages/
 *
 * Copyright 2017 DeliPress
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once dirname(__FILE__) . "/vendor/autoload.php";
require_once dirname(__FILE__) . "/php_compatibility.php";
require_once dirname(__FILE__) . "/delipress_helpers.php";


require_once dirname(__FILE__) . "/wpgod/index.php";

__delipress__wpgod_init(
     array(
        "type_development" => "plugin",
        "plugin_file"      => plugin_basename(__FILE__),
        "basename"         => dirname(plugin_basename(__FILE__))
    )
);


use DeliSkypress\Kernel;
use DeliSkypress\ContainerServices;
use DeliSkypress\ContainerActions;
use DeliSkypress\WordPress\Actions\AbstractHook;
use DeliSkypress\Services\Specification\Specification;

use Delipress\WordPress\Front\OptinSubscribe;
use Delipress\WordPress\Front\CampaignOnline;
use Delipress\WordPress\Front\PageConfirmSubscribe;

use Delipress\WordPress\Admin\Pages;
use Delipress\WordPress\Admin\Exports;
use Delipress\WordPress\Admin\AdminNotices;
use Delipress\WordPress\Admin\ErrorFieldsNotices;
use Delipress\WordPress\Admin\Options;
use Delipress\WordPress\Admin\ImportProvider;
use Delipress\WordPress\Admin\ExportProvider;
use Delipress\WordPress\Admin\Setup;
use Delipress\WordPress\Admin\Migration;

use Delipress\WordPress\Admin\Campaign\CreateCampaign;
use Delipress\WordPress\Admin\Campaign\DeleteCampaign;
use Delipress\WordPress\Admin\Campaign\SendCampaign;

use Delipress\WordPress\Admin\Listing\CreateList;
use Delipress\WordPress\Admin\Listing\DeleteList;
use Delipress\WordPress\Admin\Listing\CreateDynamicList;

use Delipress\WordPress\Admin\Wizard\WizardStepOne;
use Delipress\WordPress\Admin\Wizard\WizardStepTwo;
use Delipress\WordPress\Admin\Wizard\WizardRemovePage;

use Delipress\WordPress\Admin\Subscriber\CreateSubscriber;
use Delipress\WordPress\Admin\Subscriber\DeleteSubscriber;

use Delipress\WordPress\Admin\Optin\CreateOptin;
use Delipress\WordPress\Admin\Optin\DeleteOptin;

use Delipress\WordPress\Admin\Import\ImportSubscriber;

use Delipress\WordPress\Optin\Shortcode;
use Delipress\WordPress\Optin\Popup;
use Delipress\WordPress\Optin\FlyIn;
use Delipress\WordPress\Optin\Widget;
use Delipress\WordPress\Optin\AfterContent;

use Delipress\WordPress\Cron\LicenseCron;

use Delipress\WordPress\Table\OptinStatsTable;

use Delipress\WordPress\Services\PageAdminServices;
use Delipress\WordPress\Services\OptionServices;
use Delipress\WordPress\Services\ExportServices;
use Delipress\WordPress\Services\EmailHtmlServices;
use Delipress\WordPress\Services\MetaSubscriberServices;

use Delipress\WordPress\Services\Wizard\WizardServices;
use Delipress\WordPress\Services\Wizard\WizardStepOneServices;
use Delipress\WordPress\Services\Wizard\WizardStepTwoServices;

use Delipress\WordPress\Services\Campaign\CampaignServices;
use Delipress\WordPress\Services\Campaign\CreateCampaignServices;
use Delipress\WordPress\Services\Campaign\SendCampaignServices;
use Delipress\WordPress\Services\Campaign\DeleteCampaignServices;

use Delipress\WordPress\Services\Listing\ListServices;
use Delipress\WordPress\Services\Listing\CreateListServices;
use Delipress\WordPress\Services\Listing\DeleteListServices;
use Delipress\WordPress\Services\Listing\SynchronizeListServices;
use Delipress\WordPress\Services\Listing\ListSubscriberServices;
use Delipress\WordPress\Services\Listing\CreateDynamicListServices;

use Delipress\WordPress\Services\Subscriber\ImportSubscriberServices;
use Delipress\WordPress\Services\Subscriber\SubscriberServices;
use Delipress\WordPress\Services\Subscriber\CreateSubscriberServices;
use Delipress\WordPress\Services\Subscriber\DeleteSubscriberServices;
use Delipress\WordPress\Services\Subscriber\SynchronizeSubscriberServices;
use Delipress\WordPress\Services\Subscriber\ConfirmSubscribeServices;

use Delipress\WordPress\Integration\ContactForm7\ContactForm7Backend;
use Delipress\WordPress\Integration\ContactForm7\ContactForm7Frontend;

use Delipress\WordPress\Services\Template\TemplateUrlServices;
use Delipress\WordPress\Services\Template\DeleteTemplateServices;
use Delipress\WordPress\Services\Template\TemplateServices;

use Delipress\WordPress\Admin\Template\DeleteTemplate;

use Delipress\WordPress\Services\Table\TableServices;
use Delipress\WordPress\Services\Table\SubscriberTableServices;
use Delipress\WordPress\Services\Table\ListSubscriberTableServices;
use Delipress\WordPress\Services\Table\OptinStatsTableServices;
use Delipress\WordPress\Services\Table\SubscriberMetaTableServices;

use Delipress\WordPress\Services\Optin\OptinServices;
use Delipress\WordPress\Services\Optin\CreateOptinServices;
use Delipress\WordPress\Services\Optin\DeleteOptinServices;
use Delipress\WordPress\Services\Optin\OptinStatsServices;


use Delipress\WordPress\Services\Connector\ConnectorServices;
use Delipress\WordPress\Services\Connector\DynamicListServices;
use Delipress\WordPress\Services\Connector\WordPressUserServices;
use Delipress\WordPress\Services\Connector\WooCommerceServices;

use Delipress\WordPress\Services\Import\ImportFileSubscriberServices;

use Delipress\WordPress\Connectors\WordPressUser;
use Delipress\WordPress\Connectors\WooCommerceUser;

use Delipress\WordPress\Services\SynchronizeServices;
use Delipress\WordPress\Services\MetaServices;

use Delipress\WordPress\Services\Provider\ProviderServices;
use Delipress\WordPress\Services\Provider\ProviderSettingsFactory;
use Delipress\WordPress\Services\Provider\ProviderApiFactory;
use Delipress\WordPress\Services\Provider\ProviderImportExportFactory;

use Delipress\WordPress\PostType\Campaign;
use Delipress\WordPress\PostType\OptinForms;
use Delipress\WordPress\PostType\Template;
use Delipress\WordPress\Taxonomy\ListUsers;

use Delipress\WordPress\Endpoints\EndpointCampaign;
use Delipress\WordPress\Endpoints\EndpointPostType;
use Delipress\WordPress\Endpoints\EndpointOptin;
use Delipress\WordPress\Endpoints\EndpointFrontOptin;
use Delipress\WordPress\Endpoints\EndpointTerms;
use Delipress\WordPress\Endpoints\EndpointSubscriber;
use Delipress\WordPress\Endpoints\EndpointTemplate;


define("DELIPRESS_VERSION", "{VERSION}");
define("DELIPRESS_LOGS", false);
define("DELIPRESS_BASE_FILE", plugin_basename( __FILE__ ));
define("DELIPRESS_SLUG", "delipress");
define("DELIPRESS_PLUGIN_PATH", plugin_dir_path( __FILE__ ));
define("DELIPRESS_PLUGIN_URL", plugin_dir_url(__FILE__) );
define("DELIPRESS_PHP_VERSION_MIN", "5.6");

define("DELIPRESS_PLUGIN_DIR_EMAILS", DELIPRESS_PLUGIN_PATH . "emails" );
define("DELIPRESS_PLUGIN_DIR_TEMPLATES", DELIPRESS_PLUGIN_PATH . "templates" );
define("DELIPRESS_PLUGIN_DIR_TEMPLATES_FRONT", DELIPRESS_PLUGIN_DIR_TEMPLATES . "/front" );
define("DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN", DELIPRESS_PLUGIN_DIR_TEMPLATES . "/admin" );
define("DELIPRESS_PLUGIN_DIR_TEMPLATES_EMAILS", DELIPRESS_PLUGIN_DIR_TEMPLATES . "/emails" );
define("DELIPRESS_PLUGIN_DIR_TEMPLATES_METABOXES", DELIPRESS_PLUGIN_DIR_TEMPLATES . "/metaboxes" );

define("DELIPRESS_PATH_PUBLIC_IMG", plugin_dir_url(__FILE__) . "public/images");
define("DELIPRESS_PATH_PUBLIC_JS", plugin_dir_url(__FILE__) . "public/js");
define("DELIPRESS_PATH_PUBLIC_CSS", plugin_dir_url(__FILE__) . "public/css");
define("DELIPRESS_EMAIL_CONTACT", "support@delipress.io");

define( 'DELIPRESS_STORE_URL', 'https://delipress.io' );
define( 'DELIPRESS_ITEM_NAME', 'DeliPress' );

function uninstallDeliPressPlugin(){

    global $wpdb;

    $options = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'delipress_%'" );
    if(!empty($options)){
        array_map( 'delete_option', $options );
    }

    if ( is_multisite() ) {
        $options = $wpdb->get_col( "SELECT meta_key FROM $wpdb->sitemeta WHERE meta_key LIKE 'delipress_%'" );
        if(!empty($options)){
            array_map( 'delete_site_option', $options );
        }
    }

    $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type LIKE 'delipress%';" );
    $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}delipress_optin_stats" );


}

/**
 * Delipress
 *
 * @filter delipress_container_services
 * @filter delipress_prepare_actions
 * @filter delipress_container_actions
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Delipress extends Kernel {

    protected $slug = DELIPRESS_SLUG;


    public function __construct(){
        load_plugin_textdomain( "delipress", false, dirname( DELIPRESS_BASE_FILE ) . '/languages');
    }

    /**
     * @action delipress_before_loaded
     * @action delipress_after_loaded
     *
     * @return void
     */
    public function execute(){

        $result = $this->canLoaded();
        if(!$result["success"]){
            $this->notLoaded($result);
            return;
        }

        do_action('delipress_before_loaded');

        add_action( 'plugins_loaded' , array($this,'executePlugin'));
        add_action( 'init' , array($this,'includeDelipressFunctions'));
        register_activation_hook(__FILE__, array($this, 'executePlugin'));
        register_deactivation_hook(__FILE__, array($this, 'executePlugin'));

        do_action('delipress_after_loaded');
    }


    public function executePlugin(){
        switch (current_filter()) {
            case 'activate_' . $this->slug . '/' . $this->slug . '.php':
                register_uninstall_hook(__FILE__, 'uninstallDeliPressPlugin');
                break;
        }

        parent::executePlugin();

    }

    /**
     *
     * @return void
     */
    public function includeDelipressFunctions(){
        require_once dirname(__FILE__) . "/delipress_functions.php";
    }

    /**
     * Execute when delipress not loaded
     */
    public function notLoaded($params){
        if ( current_filter() !== 'activate_' . DELIPRESS_BASE_FILE ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( DELIPRESS_BASE_FILE, true );
		}

		wp_die($params["message"]);
    }

    /**
     * Can load delipress
     *
     * @return boolean
     */
    public function  canLoaded(){

        if ( version_compare( phpversion(), DELIPRESS_PHP_VERSION_MIN, '<=' ) ) {
            return array(
                "success" => false,
                "message" =>  sprintf(
                    __( '<strong>%1$s</strong> requires PHP %2$s minimum, your website is actually running version %3$s.', 'depipress' ),
                    'DeliPress', '<code>' . DELIPRESS_PHP_VERSION_MIN . '</code>', '<code>' . phpversion() . '</code>'
                )
            );
        }

        if(!function_exists('curl_version')){
            return array(
                "success" => false,
                "message" =>  sprintf(
                    __( '<strong>%1$s</strong> requires cURL, your website have not this extension.', 'depipress' ),
                    'DeliPress'
                )
            );
        }

        return array(
            "success" => true
        );
    }
}

$services = apply_filters("delipress_container_services",
    array(
        new Specification(),
        new OptionServices(),
        new PageAdminServices(),
        new ExportServices(),
        new ImportSubscriberServices(),
        new ListServices(),
        new CreateCampaignServices(),
        new CampaignServices(),
        new ProviderServices(
            new ProviderSettingsFactory(),
            new ProviderApiFactory(),
            new ProviderImportExportFactory()
        ),
        new SendCampaignServices(),
        new CreateListServices(),
        new SubscriberServices(),
        new CreateSubscriberServices(),
        new DeleteSubscriberServices(),
        new DeleteListServices(),
        new DeleteCampaignServices(),
        new SynchronizeListServices(),
        new SynchronizeServices(),
        new WizardServices(),
        new WizardStepOneServices(),
        new WizardStepTwoServices(),
        new OptinServices(),
        new OptinStatsServices(),
        new CreateOptinServices(),
        new DeleteOptinServices(),
        new ListSubscriberServices(),
        new EmailHtmlServices(),
        new TableServices(),
        new SubscriberTableServices(),
        new SubscriberMetaTableServices(),
        new ListSubscriberTableServices(),
        new OptinStatsTableServices(),
		new SynchronizeSubscriberServices(),
        new ConnectorServices(),
        new WordPressUserServices(),
        new TemplateUrlServices(),
        new ImportFileSubscriberServices(),
        new WooCommerceServices(),
        new MetaSubscriberServices(),
        new CreateDynamicListServices(),
        new MetaServices(),
        new ConfirmSubscribeServices(),
        new DynamicListServices(),
        new DeleteTemplateServices(),
        new TemplateServices()
    )
);


$containerServices = new ContainerServices($services);

$prepareActions = apply_filters("delipress_prepare_actions",
    array(
        new ListUsers(),
        new Pages(),
        new Campaign(),
        new OptinForms(),
        new Template(),
        new Exports(),
        new AdminNotices(),
        new ErrorFieldsNotices(),
        new EndpointCampaign(),
        new EndpointPostType(),
        new EndpointOptin(),
        new EndpointFrontOptin(),
        new EndpointTerms(),
        new EndpointSubscriber(),
        new Options(),
        new CreateCampaign(),
        new SendCampaign(),
        new CreateList(),
        new CreateSubscriber(),
        new DeleteSubscriber(),
        new DeleteList(),
        new DeleteCampaign(),
        new ImportProvider(),
        new ExportProvider(),
        new OptinSubscribe(),
        new Setup(),
        new Shortcode(),
        new Popup(),
        new FlyIn(),
        new Widget(),
        new AfterContent(),
        new WizardRemovePage(),
        new WizardStepOne(),
        new WizardStepTwo(),
        new CreateOptin(),
        new DeleteOptin(),
        new CampaignOnline(),
        new Migration(),
        new OptinStatsTable(),
        new WordPressUser(),
        new LicenseCron(),
        new ImportSubscriber(),
        new WooCommerceUser(),
        new CreateDynamicList(),
        new ContactForm7Backend(),
        new ContactForm7Frontend(),
        new PageConfirmSubscribe(),
        new EndpointTemplate(),
        new DeleteTemplate()
    )
);


foreach ($prepareActions as $key => $prepareAction) {
    if($prepareAction instanceOf AbstractHook){
        $prepareAction->setContainerServices($containerServices);
    }
}

$actions  = apply_filters("delipress_container_actions", $prepareActions);

$containerActions = new ContainerActions($actions);

$delipressPlugin  = new Delipress();
$delipressPlugin->setContainerServices($containerServices)
				->setContainerActions($containerActions)
                ->execute();
