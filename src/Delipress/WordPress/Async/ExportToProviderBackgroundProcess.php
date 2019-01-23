<?php

namespace Delipress\WordPress\Async;

use Delipress\WordPress\Async\WPBackgroundProcess;

use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Helpers\TaxonomyHelper;

use Delipress\WordPress\Services\Table\TableServices;


class ExportToProviderBackgroundProcess extends WPBackgroundProcess {

	protected $action    = 'delipress_export_to_provider_migration_background';

	public function __construct($services) {
		parent::__construct();

		$this->optionServices     = $services["OptionServices"];
		$this->providerServices   = $services["ProviderServices"];
        $this->createListServices = $services["CreateListServices"];
        
        $this->provider    = $this->optionServices->getProvider();
        $this->providerApi = $this->providerServices->getProviderApi($this->provider["key"]);
	}


	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
        
        set_transient(DELIPRESS_SLUG . "_sync_list_in_work", true);

        $transient  = get_transient(DELIPRESS_SLUG . "_sync_list_already_create", false);
        
        if ( false === $transient  ){
            $transient = array();
        }
        
        if(!array_key_exists($item["list_id"], $transient)){

            $result = $this->createList($item["name"]);

            if($result["success"]){
                $transient[$item["list_id"]] = $result["results"]["list"]->getId();

                $oldList = new ListModel();
                $oldList->setId($item["list_id"]);
                $optins = $oldList->getOldOptins();

                if(!empty($optins)){
                    foreach($optins as $optin){
                        $listsOnOptins = wp_get_object_terms( array($optin->ID ), TaxonomyHelper::TAXO_LIST );
                        
                        $termsId = array();
                        if(!empty($listsOnOptins)){
                            foreach($listsOnOptins as $term){
                                $termsId[] = $term->term_id;
                            }
                        }

                        $termsId[] = $result["results"]["list"]->getId();

                        wp_set_object_terms($optin->ID , $termsId, TaxonomyHelper::TAXO_LIST);
                    }
                }

                update_term_meta($result["results"]["list"]->getId(), TaxonomyHelper::META_LIST_ID_PROVIDER, sprintf("%s_%s", $this->provider["key"], $termsId) );
                
                set_transient(DELIPRESS_SLUG . "_sync_list_already_create", $transient);
            }
            else{
                return false;
            }

        }

        if(array_key_exists($item["list_id"], $transient)){
            $this->createSubscribers($transient[$item["list_id"]],  $item);
        }
        

		return false;
    }
    
    protected function createList($name){
        return $this->createListServices->createListStandalone(array(
            TaxonomyHelper::LIST_NAME => $name
        ), array(
            "safeError" => true
        ));
    }

    protected function createSubscribers($idList, $item){

        $subscribers = TableServices::getTable("ListSubscriberTableServices")->getSubscribers(
            array(
                "selects" => array(
                    "subscriber_id"
                ),
                "links" => array(
                    "subscribers" => array(
                        "selects" => array(
                            "email"
                        )
                    )
                ),
                "query" => array(
                    array(
                        "field"    => "status",
                        "operator" => "=",
                        "value"    => "subscribe"
                    ),
                        array(
                            "field"    => "term_taxonomy_id",
                            "operator" => "=",
                            "value"    => $item["list_id"]
                        )
                )
            ),
            $item["page"],
            $item["limit"]
        );

        $params = array();
        foreach($subscribers as $key => $subscriber){
            $params[$key]["email"] = $subscriber->email;
        }


        $response       = $this->providerApi->createSubscribersOnList($idList, $params);

    }


	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
        parent::complete();
	}
}