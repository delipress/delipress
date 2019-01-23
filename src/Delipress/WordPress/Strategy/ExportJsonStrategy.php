<?php

namespace Delipress\WordPress\Strategy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 * ExportJsonStrategy
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ExportJsonStrategy {

    protected $data     = array();
    
    protected $filename = "export_json";

    /**
     *
     * @param array $data
     * @return ExportJsonStrategy
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }

    /**
     *
     * @param string $filename
     * @return ExportJsonStrategy
     */
    public function setFilename($filename){
        $this->filename = $filename;
        return $this;
    }

    /**
     * @filter DELIPRESS_SLUG . '_export_json_strategy'
     * @action DELIPRESS_SLUG . '_export_json_strategy_' . $strategyExport
     * @return type
     */
    public function execute(){

        $strategyExport = apply_filter(DELIPRESS_SLUG . "_export_json_strategy", "download");

        switch ($strategyExport) {
            case 'download':
                $data = json_encode($this->data);
                
                header(sprintf('Content-disposition: attachment; filename=%s.json', $this->filename));
                header('Content-type: application/json');
                echo $data;
                die;
                break;
            
            default:
                do_action(DELIPRESS_SLUG . "_export_json_strategy_" . $strategyExport, $this->data, $this->filename);
                break;
        }

    }

}

