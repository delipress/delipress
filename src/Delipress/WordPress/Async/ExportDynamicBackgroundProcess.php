<?php

namespace Delipress\WordPress\Async;

use Delipress\WordPress\Async\WPBackgroundProcess;

use Delipress\WordPress\Models\ListModel;


class ExportDynamicBackgroundProcess extends WPBackgroundProcess {

	protected $action    = 'delipress_export_dynamic_background';

	protected $transient = 'delipress_sync_export_dynamic_background';

	public function __construct($services) {
		parent::__construct();

		$this->dynamicListServices = $services["DynamicListServices"];
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


		$listModel = new ListModel();
		$listModel->setId(
			$item["list_id"]
		);

		$this->dynamicListServices->exportDynamicListContacts($listModel, $item);

		if ( false === ( $transient = get_transient( $this->transient ) ) ){
            $transient = array();
		}
        
		if($item["page"] < $item["total"]){
			$transient[$item["list_id"]] = 1;
		}
		else{
			$transient[$item["list_id"]] = 0;
		}


		set_transient($this->transient, $transient);

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();
		delete_transient($this->transient);
	}
}