<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;

/**
 * SynchronizeServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SynchronizeServices implements ServiceInterface {

    /**
     * 
     * @param string $provider
     * @return string
     */
    public function getPageSynchronize($provider){
        return add_query_arg(
            array(
                'page'     => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SYNCHRONIZE),
                'provider' => $provider
            ), 
            admin_url( 'admin.php' ) 
        );
    }

    /**
     * 
     * @param string $provider
     * @return string
     */
    public function getUrlFormAdminPost($provider){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"   => ActionHelper::IMPORT_LISTS_PROVIDER,
                    "provider" => $provider
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::IMPORT_LISTS_PROVIDER
        );
    }

    /**
     * 
     * @param string $provider
     * @return string
     */
    public function getUrlExportProviderFormAdminPost($provider){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"   => ActionHelper::EXPORT_LISTS_PROVIDER,
                    "provider" => $provider
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::EXPORT_LISTS_PROVIDER
        );
    }

}
