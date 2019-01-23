<?php

namespace Delipress\WordPress\Services\Template;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;
use DeliSkypress\Models\ServiceInterface;


/**
 * DeleteTemplateServices
 */
class DeleteTemplateServices implements ServiceInterface, MediatorServicesInterface {

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){}

    public function deleteTemplate($templateId){
        wp_delete_post($templateId, true);

        return array(
            "success" => true
        );
    }
  

}
