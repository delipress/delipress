<?php

namespace Delipress\WordPress\Services\Template;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;
use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Models\TemplateModel;

/**
 * TemplateServices
 */
class TemplateServices implements ServiceInterface, MediatorServicesInterface {

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){}

    public function getTemplates($offset = 0, $limit = 500){

        $templates = get_posts(array(
            "post_type"      => PostTypeHelper::CPT_TEMPLATE,
            "posts_per_page" => $limit,
            "offset"         => $offset
        ));

        if(empty($templates)){
            return array();
        }   

        $templatesModel = array();
        foreach($templates as $template){
            $tpl = new TemplateModel();
            $tpl->setTemplate($template);

            $templatesModel[] = $tpl;
        }

        return $templatesModel;
    }

}
