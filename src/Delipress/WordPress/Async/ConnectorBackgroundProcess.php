<?php

namespace Delipress\WordPress\Async;

use Delipress\WordPress\Async\WPBackgroundProcess;

use Delipress\WordPress\Models\ListModel;


class ConnectorBackgroundProcess extends WPBackgroundProcess {

	protected $action 	 = 'delipress_connector_background';
	
	protected $transient = 'delipress_sync_import_background';

	public function __construct($name, $connectorService, $services) {
		$this->action .= "_" . $name;
		$this->connectorServices       = $connectorService;
		$this->synchronizeListServices = $services["SynchronizeListServices"];
		$this->listServices 		   = $services["ListServices"];
		parent::__construct();
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

		$listModel = $this->listServices->getList($item["list_id"]);

		$this->connectorServices->importListContacts($listModel, $item["offset"], $item["limit"]);
		
		if ( false === ( $transient = get_transient( $this->transient ) ) ){
            $transient = array();
		}
        
		if($item["page"] < $item["total"]){
			$transient[$listModel->getId()] = 1;
		}
		else{
			$transient[$listModel->getId()] = 0;
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
		delete_transient($this->transient);
		parent::complete();
	}
}