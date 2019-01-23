<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TableHelper;
use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Async\ExportToProviderBackgroundProcess;

/**
 * Migration
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Migration extends AbstractHook implements HooksAdminInterface {


    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices              = $containerServices->getService("OptionServices");
        $this->optinStatsTableServices     = $containerServices->getService("OptinStatsTableServices");
        
        $this->optinServices               = $containerServices->getService("OptinServices");
        $this->metaTableServices           = $containerServices->getService("MetaTableServices");
        $this->listServices                = $containerServices->getService("ListServices");
        $this->subscriberMetaTableServices = $containerServices->getService("SubscriberMetaTableServices");
        $this->campaignServices            = $containerServices->getService("CampaignServices");
        
        $this->exportToProvider            =  new ExportToProviderBackgroundProcess(
            array(
                "ProviderServices"     => $containerServices->getService("ProviderServices"),
                "OptionServices"       => $containerServices->getService("OptionServices"),
                "CreateListServices"   => $containerServices->getService("CreateListServices")
            )
        );
    }

    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(DELIPRESS_VERSION !== "{VERSION}"){
            add_action("admin_init", array($this, "upgradeVersion"), 9999);
        }
    }

    /**
     * Upgrade version DeliPress
     * @see admin_init
     * @return void
     */
    public function upgradeVersion(){
        $version       = $this->optionServices->getVersion();

        $hasOneUpdate = false;

        if(version_compare($version, "1.3.0", "<") && $version != null){
            $hasOneUpdate = true;

            $this->updateConnectors();
            $this->updateProviderOnCampaign();
            $this->updateListsOnCampaign();
            $this->updateListsOnOptIn();
            $this->verifySynchronizeLists();
        }

        if(version_compare($version, "1.3.1", "=") && version_compare($version, "1.3.2", "<") && $version != null){
            $hasOneUpdate = true;

            $this->removeTable();
        }

        if($hasOneUpdate){
            $this->optionServices->updateVersion();
        }

    }

    protected function removeTable(){

        global $wpdb;

        $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}delipress_subscriber" );
        $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}delipress_list_subscriber" );
        $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}delipress_meta" );
        $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}delipress_subscriber_meta" );
    }


    protected function verifySynchronizeLists(){

        $transient = get_transient(DELIPRESS_SLUG . "_sync_list_in_work", false);
        if($transient){
            return;
        }

        $lists = get_terms( 
            array(
                'taxonomy' => TaxonomyHelper::TAXO_LIST,
                'hide_empty' => false,
            )
        );

        if(!$lists){
            return;
        }

        $provider  = $this->optionServices->getProvider();
        $provider  = $provider["key"];

        $prepareLists = array();
        foreach($lists as $key => $list){
            $value = new ListModel();
            $value->setId($list->term_id);

            $providerId = $value->getProviderId();
            if(!empty($providerId)){
                $listProvider = $this->listServices->getList($providerId);

                if(!$listProvider){
                    $prepareLists[] = $list;
                    continue;
                }

                switch($provider){
                    case ProviderHelper::MAILJET:
                        if($listProvider->object["IsDeleted"]){
                            $prepareLists[] = $list;
                            continue;
                        }
                        break;
                }
            }
        }


        if(empty($prepareLists)){
            return;
        }

        $prepareExportLists = array();
        $range              = 100;

        foreach($prepareLists as $listPrepare){

            $totalSubscriberExport = $this->countListSubscribers($listPrepare->term_id);
            $countCall             = ceil($totalSubscriberExport /  $range);

            for ($i=1; $i <= $countCall; $i++) {
                $args = array(
                    "provider"          => $provider,
                    "page"              => $i,
                    "total"             => $countCall,
                    "limit"             => $range,
                    "name"              => $listPrepare->name,
                    "list_id"           => $listPrepare->term_id,
                );

                $this->exportToProvider->push_to_queue($args);
            }

        }


        $this->exportToProvider->save()->dispatch();
        
    }

    /**
     * Count number subscribers on list
     *
     * @param [type] $idList
     * @return void
     */
    protected function countListSubscribers($idList){
        global $wpdb;

        $alias           = "ls";
        $sAlias          = "s";
        $tableLS         = TableHelper::TABLE_LIST_SUBSCRIBER;
        $tableS          = TableHelper::TABLE_SUBSCRIBER;
        
        $sql         = "
            SELECT count({$alias}.subscriber_id)
            FROM {$wpdb->prefix}{$tableLS} {$alias}
            INNER JOIN {$wpdb->prefix}{$tableS} {$sAlias} ON {$alias}.subscriber_id = {$sAlias}.id
            WHERE {$alias}.term_taxonomy_id = %s
        ";

        $result    = $wpdb->get_var( 
            $wpdb->prepare( $sql, $idList)
        );

        return $result;

    }

    /**
     * Update list connectors
     *
     * @return void
     */
    protected function updateConnectors(){
        $connectors = $this->optionServices->getConnectors();
        
        if(empty($connectors)){
            return;
        }

        foreach($connectors as $key => $connector ){
            if(empty($connector["list_id"])){
                continue;
            }

            $list = new ListModel();
            $list->setId($connector["list_id"]);
            $providerId = $list->getProviderId();

            $connectors[$key]["list_id"] = $providerId;
        }

        $this->optionServices->setOptionsByKey($connectors, "connectors");
    }

    /**
     * Update provider on campaign
     *
     * @return void
     */
    protected function updateProviderOnCampaign(){
        $provider  = $this->optionServices->getProvider();
        $provider  = $provider["key"];

        $campaigns = $this->campaignServices->getCampaigns(array(
            "posts_per_page" => -1
        ));

        foreach($campaigns as $campaign) {
            $providerCampaign = $campaign->getCampaignProvider();
            if(!empty($providerCampaign) && $providerCampaign != $provider){
                continue;
            }
            
            update_post_meta($campaign->getId(), PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER, $provider);
            
        }

    }

    /**
     * @return void
     */
    protected function updateListsOnCampaign(){
        $campaigns = $this->campaignServices->getCampaigns(array(
            "posts_per_page" => -1
        ));

        foreach($campaigns as $campaign) {
            $lists = $campaign->getOldLists();
            
            $list    = current($lists);
            if(!$list){
                continue;
            }
            $idProvider = $list->getProviderId();
            $list   = $this->listServices->getList($idProvider);

            if(!$list){
                continue;
            }
            
            wp_delete_object_term_relationships($campaign->getId(), TaxonomyHelper::TAXO_LIST);

            update_post_meta($campaign->getId(), PostTypeHelper::CAMPAIGN_TAXO_LISTS, $list->getId());
            
        }
    }

    /**
     * @return void
     */
    protected function updateListsOnOptIn(){
        $optins = $this->optinServices->getOptins(array(
            "posts_per_page" => -1
        ));

        $provider  = $this->optionServices->getProvider();
        $provider  = $provider["key"];

        foreach($optins as $optin) {
            $lists = $optin->getOldLists();
            $providerIds = array();
            
            foreach($lists as $listModel){
                $idList = $listModel->getProviderId();
                $list   = $this->listServices->getList($idList);

                if(!$list){
                    $idList = $listModel->getId();
                    $list   = $this->listServices->getList($idList);
                }

                if($list){
                    switch($provider){
                        case ProviderHelper::MAILJET:
                            if(!$list->object["IsDeleted"]){
                                $providerIds[] = $idList;    
                            }
                            break;
                        default:
                            $providerIds[] = $idList;
                            break;
                    }
                }
            }

            wp_delete_object_term_relationships($optin->getId(), TaxonomyHelper::TAXO_LIST);

            if(empty($providerIds)){
                $this->optinServices->deactivateOptin($optin);
            }
            else{
                update_post_meta($optin->getId(), PostTypeHelper::OPTIN_TAXO_LISTS, $providerIds);
            }
            
        }
    }

}
