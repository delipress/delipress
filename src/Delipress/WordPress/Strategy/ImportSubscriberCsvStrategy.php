<?php

namespace Delipress\WordPress\Strategy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\Strategy\CsvStrategyInterface;

use Delipress\WordPress\Helpers\TypeFileHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

/**
 * ImportSubscriberCsvStrategy
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class ImportSubscriberCsvStrategy extends AbstractImportSubscriber implements CsvStrategyInterface {


    /**
     * 
     * @param string $delimiter
     * @return ImportSubscriberCsvStrategy
     */
    public function setDelimiter($delimiter){
        $this->delimiter = $delimiter;
        return $this;
    }


    /**
     * @return array
     */
    public function prepareImport(){

        $objReader = \PHPExcel_IOFactory::createReader('CSV')
                            ->setDelimiter($this->delimiter)
                            ->load($this->file);

        $activeSheet = $objReader->getActiveSheet();
        $startFrom   = 0;
        $limit       = 5;

        $prepareData = array();

        foreach( $activeSheet->getRowIterator($startFrom, $limit) as $row ){
            foreach( $row->getCellIterator() as $key => $cell ){
                $prepareData[$key][] = $cell->getValue();
            }
        }

        return array(
            "success" => true,
            "results" => $prepareData
        );

    }

    /**
     * @return void
     */
    public function execute(){

        parent::execute();

        $objReader = \PHPExcel_IOFactory::createReader('CSV')
                        ->setDelimiter($this->delimiter)
                        ->load($this->file);
        
        $activeSheet = $objReader->getActiveSheet();

        $total  = $activeSheet->getHighestDataRow();
        $params = array(
            "total_subscribers" => $total,
            "delimiter"         => $this->delimiter,
            "type"              => TypeFileHelper::CSV
        );

        $this->pushBackgroundProcess($params);
    }


    /**
     * @param array $item
     * @return array
     */
    public function getSubscribersFromImport($item){

        $objReader = \PHPExcel_IOFactory::createReader('CSV')
                        ->setDelimiter($this->delimiter)
                        ->load($this->file);

        $activeSheet = $objReader->getActiveSheet();

        $startFrom = ($item["limit"] * $item["page"]) - $item["limit"];
        $limit     =  $item["limit"] * $item["page"];
        
        $subscribers         = array();
        $keysMetasSubscriber = SubscriberMetaHelper::getKeysWordPressUser();
        foreach( $activeSheet->getRowIterator($startFrom, $limit) as $line => $row ){
            foreach( $row->getCellIterator() as $key => $cell ){

                if(isset($item["meta_import"][$key]) && !empty($item["meta_import"][$key])){
                    $keyMeta = $item["meta_import"][$key];
                }
                else if(isset($item["create_import"][$key]) && !empty($item["create_import"][$key])){
                    $keyMeta = $item["create_import"][$key];
                }
                else{
                    $keyMeta = null;
                }

                $value               = $cell->getValue();

                if($keyMeta && in_array($keyMeta, $keysMetasSubscriber)){
                    switch($keyMeta){
                        case SubscriberMetaHelper::EMAIL:
                            $subscribers[$line]["email"] = $value;
                            break;
                    }
                }
                else if($keyMeta != "-1"){
                    $subscribers[$line]["metas"][$keyMeta] = $value;
                }
            }
        }

        return $subscribers;
    }

}

