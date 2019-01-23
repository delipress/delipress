<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\ListModel;

/**
 * ListServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ListServices implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->providerServices = $services["ProviderServices"];
        $this->optionServices    = $services["OptionServices"];
    }

    /**
     *
     * @param array $args
     * @param bool $safeError
     * @return Array ListModel
     */
    public function getLists($args = array(), $safeError = false){

        $provider = $this->optionServices->getProvider();

        if(!$provider["is_connect"]){
            return array();
        }

        $response = $this->providerServices
                         ->getProviderApi($provider["key"])
                         ->setSafeError($safeError)
                         ->getLists($args);

        if(!$response["success"]){
            return array();
        }

        $lists = array();
        foreach($response["results"] as $list){
            $lists[] = $this->providerServices->getListModel($provider["key"], $list);
        }

        return $lists;
    }

    /**
     *
     * @param int $listId
     * @return ListInterface|null
     */
    public function getList($listId, $safeError = false){
        $provider = $this->optionServices->getProvider();

        if(!$provider["is_connect"]){
            return new ListModel();
        }

        if(!$listId){
            return new ListModel();
        }

        $response = $this->providerServices
                         ->getProviderApi($provider["key"])
                         ->setSafeError($safeError)
                         ->getList($listId);

        if(!$response["success"]){
            return null;
        }

        switch($provider["key"]){
            case ProviderHelper::MAILJET:
                $listObj = $response["results"][0];
                break;
            default:
                $listObj = $response["results"];
                break;
        }

        return $this->providerServices->getListModel($provider["key"], $listObj);

    }

    /**
     *
     * @param array $args
     * @return int
     */
    public function getCountLists($args){
        $provider = $this->optionServices->getProvider();

        if(!$provider["is_connect"]){
            return 0;
        }

        $response = $this->providerServices
                         ->getProviderApi($provider["key"])
                         ->getLists($args);

        if(!$response["success"]){
            return 0;
        }

        if(isset($response["total_items"])){
            return $response["total_items"];
        }

        return count($response["results"]);
    }


    /**
     *
     * @param array $data
     */
    public function importLists($data){
        foreach ($data as $key => $term) {
            $response = wp_insert_term(
                $term->name,
                TaxonomyHelper::TAXO_LIST,
                array(
                    'description' => $term->description,
                    'slug'        => $term->slug,
                    'parent'      => $term->parent
                )
            );

            if(is_wp_error($response)){
                ErrorFieldsNoticesHelper::registerError($response->get_error_message(), "_import_list");
                wp_redirect(admin_url( 'admin.php?page=' . PageAdminHelper::PAGE_OPTIONS ) );
                exit;
                break;
            }
        }

        ErrorFieldsNoticesHelper::registerSuccess(__("List successfully imported", "delipress"), "_import_list");
        wp_redirect(admin_url( 'admin.php?page=' . PageAdminHelper::PAGE_OPTIONS ) );
        exit;
    }


    /**
     *
     * @return string
     */
    public function getCreateUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action" => "create"
            ),
            admin_url("admin.php")
        );

    }

    /**
     *
     * @return string
     */
    public function getChooseCreateUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action" => "choose-create"
            ),
            admin_url("admin.php")
        );

    }

    /**
     * @param int $idList
     * @return string
     */
    public function getEditUrl($idList){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action" => "create",
                "list_id" => $idList
            ),
            admin_url("admin.php")
        );

    }

    /**
     *
     * @return string
     */
    public function getPageListUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
            ),
            admin_url("admin.php")
        );

    }

    /**
     *
     * @param int $id
     * @return string
     */
    public function getSinglePageListUrl($id){
        return add_query_arg(
            array(
                "page"    => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action"  => "subscribers",
                "list_id" => $id
            ),
            admin_url("admin.php")
        );
    }

    /**
     *
     * @param int $listId
     * @return string
     */
    public function getDeleteListUrl($listId){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::DELETE_LIST,
                    "list_id"     => $listId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_LIST
        );
    }

    /**
     * @return string
     */
    public function getDeleteListsUrl(){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::DELETE_LISTS
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_LISTS
        );
    }

    /**
     * @return string
     */
    public function getCreateListDynamicUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action" => "dynamic"
            ),
            admin_url("admin.php")
        );

    }

    /**
     * @return string
     */
    public function getCreateListConnectorUrl(){

        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action" => "connector"
            ),
            admin_url("admin.php")
        );

    }

    /**
     * @return string
     */
    public function getCreateListUrlFormAdminPost(){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::CREATE_LIST
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::CREATE_LIST
        );
    }

    /**
     * @return string
     */
    public function getCreateSubscriberOnListUrlFormAdminPost(){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::CREATE_SUBSCRIBER
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::CREATE_SUBSCRIBER
        );
    }

    /**
     *
     * @param ListModel $list
     * @return string
     */
    public function executeSynchronizeListSubscriberImport(ListModel $list){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::SYNCHRONIZE_LIST_SUBSCRIBER_IMPORT,
                    "list_id"     => $list->getId()
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::SYNCHRONIZE_LIST_SUBSCRIBER_IMPORT
        );
    }

    /**
     *
     * @param ListModel $list
     * @return string
     */
    public function executeSynchronizeListSubscriberExport(ListModel $list){
        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::SYNCHRONIZE_LIST_SUBSCRIBER_EXPORT,
                    "list_id"     => $list->getId()
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::SYNCHRONIZE_LIST_SUBSCRIBER_EXPORT
        );
    }

    /**
     * @return string
     */
    public function getImportSubscriberOnListUrlFormAdminPost($step = 1){
        switch($step){
            case 1:
                $action = ActionHelper::IMPORT_SUBSCRIBER_STEP_ONE;
                break;
            case 2:
                $action = ActionHelper::IMPORT_SUBSCRIBER_STEP_TWO;
                break;

        }

        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => $action
                ),
                admin_url("admin-post.php")
            ),
            $action
        );
    }

    /**
     * @return string
     */
    public function getCreateDynamicListUrlFormAdminPost(){

        return wp_nonce_url(
            add_query_arg(
                array(
                    "action"      => ActionHelper::CREATE_DYNAMIC_LIST
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::CREATE_DYNAMIC_LIST
        );
    }


}
