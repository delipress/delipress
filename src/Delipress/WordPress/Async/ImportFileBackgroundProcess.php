<?php

namespace Delipress\WordPress\Async;

use Delipress\WordPress\Async\WPBackgroundProcess;

use Delipress\WordPress\Models\ListModel;


class ImportFileBackgroundProcess extends WPBackgroundProcess {

	protected $action 	 = 'delipress_import_background_file';
	
	protected $transient = 'delipress_sync_import_background_file';

	public function __construct($importFileSubscriberServices, $providerServices) {
		parent::__construct();
		$this->importFileSubscriberServices = $importFileSubscriberServices;
		$this->providerServices             = $providerServices;
	}


	/**
	 * Task
     * 
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		if ( false === ( $transient = get_transient( $this->transient ) ) ){
            $transient = array();
		}
        
		if($item["page"] < $item["total_call"]){
			$transient[$item["list_id"]] = 1;
		}
		else{
			$transient[$item["list_id"]] = 0;
		}

		set_transient($this->transient, $transient);

		$this->importFileSubscriberServices->importSubscribers($item);
		
		return false;
	}

	protected function complete() {
		parent::complete();
		delete_transient($this->transient);
	}
}