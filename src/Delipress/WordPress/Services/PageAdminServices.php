<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;
use Delipress\WordPress\Helpers\PageAdminHelper;


/**
 * PageAdminServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class PageAdminServices implements ServiceInterface, MediatorServicesInterface{

    protected $pages = null;

    protected $tabs  = array();

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices = $services["OptionServices"];

    }

    /**
     * @filter DELIPRESS_SLUG . '_pages_unset'
     * @return array
     */
    public function getPagesUnset(){

        $options = $this->optionServices->getOptions();
        $args = array(
            DELIPRESS_SLUG,
            PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SYNCHRONIZE),
            PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_WIZARD)
        );

        if(!$options["others"]["show_setup"]){
            $args[] = PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SETUP);
        }

        return apply_filters(DELIPRESS_SLUG . "_pages_unset", $args);
    }

    /**
     * @filter DELIPRESS_SLUG . '_settings_pages'
     * @return array
     */
    public function getPages(){

        if($this->pages !== null){
            return apply_filters(DELIPRESS_SLUG . "_setting_pages", $this->pages);
        }

        $this->pages =  array(

            PageAdminHelper::PAGE_SETUP => array(
                'page_title' => __("Setup", "delipress"),
                'menu_title' => _x("Setup Wizard", "menu entry", "delipress"),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_SETUP),
                'capability' => 'manage_options',
            ),
            PageAdminHelper::PAGE_CAMPAIGNS => array(
                'page_title' => __("Campaigns", 'delipress'),
                'menu_title' => __("Campaigns", 'delipress'),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_CAMPAIGNS),
                'capability' => 'manage_options',
            ),
            PageAdminHelper::PAGE_LISTS => array(
                'page_title' => __("Lists", 'delipress'),
                'menu_title' => __("Lists", 'delipress'),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                'capability' => 'manage_options',
            ),
            PageAdminHelper::PAGE_OPTIN_FORMS => array(
                'page_title' => __("Opt-In Forms", 'delipress'),
                'menu_title' => __("Opt-In Forms", 'delipress'),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIN_FORMS),
                'capability' => 'manage_options',
            ),
            PageAdminHelper::PAGE_OPTIONS => array(
                'page_title' => __("Settings", 'delipress'),
                'menu_title' => __("Settings", 'delipress'),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIONS),
                'capability' => 'manage_options',
            ),
            PageAdminHelper::PAGE_WIZARD => array(
                'page_title' => __("Setup wizard", 'delipress'),
                'menu_title' => __("Setup wizard", 'delipress'),
                'slug'       => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_WIZARD),
                'capability' => 'manage_options',
            )
        );

        return apply_filters(DELIPRESS_SLUG . "_setting_pages", $this->pages);
    }

    /**
     * @param string $key
     * @return (array|null)
     */
    public function getPage($key = ""){
        if(array_key_exists($key, $this->pages)){
            return $this->pages[$key];
        }

        return null;
    }

    /**
     * @filter DELIPRESS_SLUG . '_settings_tabs'
     * @return array
     */
    public function getTabs(){

        $this->tabs  = array(
            PageAdminHelper::PAGE_OPTIONS => array(
                "options"     => __("Settings", "delipress"),
                "subscribers" => __("Subscriptions", "delipress"),
                "others"      => __("Others",   "delipress")
            )
        );


        return apply_filters(DELIPRESS_SLUG . "_settings_tabs", $this->tabs);
    }

    /**
     * @filter DELIPRESS_SLUG . '_settings_tabs_by_page'
     * @return array
     */
    public function getTabsByPage($page){
        $tabs   = $this->getTabs();
        $result = array();
        if(array_key_exists($page, $tabs)){
            $result = $tabs[$page];
        }

        return apply_filters(DELIPRESS_SLUG . "_settings_tabs_by_page", $result, $tabs, $page);
    }


}
