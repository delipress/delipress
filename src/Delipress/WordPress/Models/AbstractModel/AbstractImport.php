<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Async\ImportFileBackgroundProcess;
use Delipress\WordPress\Helpers\TypeFileHelper;

/**
 * AbstractImport
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class AbstractImport implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices     = $services["OptionServices"];
        $this->metaServices       = $services["MetaServices"];
        $this->createListServices = $services["CreateListServices"];
        $this->backgroundProcess = new ImportFileBackgroundProcess($services["ImportFileSubscriberServices"], $services["ProviderServices"]);
    }

    /**
     *
     * @param File $file
     * @return AbstractImport
     */
    public function setFile($file){
        $this->file = $file;
        return $this;
    }

    /**
     *
     * @param string $delimiter
     * @return AbstractImport
     */
    public function setDelimiter($delimiter){
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelimiter(){
        return  apply_filters(DELIPRESS_SLUG . "_abstract_import_delimiter", $this->delimiter);
    }

    /**
     *
     * @return string
     */
    public function getFile(){
        return $this->fileSideload['file'];
    }

    /**
     * @return array
     */
    public function getData(){

        if($this->file === null){
            return array("success" => false);
        }

        $fileType        = $this->file['import']['type'];
        $fileName        = $this->file['import']['name'];

        $_POST_action    = (isset($_POST['action'])) ? $_POST["action"] : "";
        $_POST['action'] = 'wp_handle_sideload';

        $listId       = (isset($_POST["list_id"]))     ? $_POST["list_id"]     : null;
        $listCreate   = (isset($_POST["create_list"])) ? $_POST["create_list"] : null;
        $createOrUpdate = (isset($_POST["create_or_update"])) ? $_POST["create_or_update"] : "create";
        
        $strategy = null;
        $mimes    = array();

        switch ($fileType) {
            case TypeFileHelper::CSV:
            case TypeFileHelper::VND_MS_EXCEL:
                $mimes = array(
                    "mimes" => array(
                        "csv" => $fileType
                    )
                );
                break;
            case 'application/json':
                $mimes = array(
                    "mimes" => array(
                        "json" => $fileType
                    )
                );
                break;
        }

        $this->fileSideload            = wp_handle_sideload( $this->file['import'], $mimes);
        if(!isset($this->fileSideload["file"])){
            return array("success" => false);
        }
        
        $_POST['action'] = $_POST_action;
        $data            = file_get_contents( $this->fileSideload['file'] );

        return array(
            "success"    => true,
            "type"       => $fileType,
            "listCreate" => $listCreate,
            "listId"     => $listId,
            "createOrUpdate"  => $createOrUpdate,
            "data"       => $data
        );

    }

    /**
     * @return void
     */
    public function removeFile(){
        file_put_contents( $this->fileSideload['file'], '');
        unlink( $this->fileSideload['file'] );
    }

}
