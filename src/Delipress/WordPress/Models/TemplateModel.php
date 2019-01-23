<?php

namespace Delipress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;


class TemplateModel  {

    public function setTemplate($object){
        $this->object = $object;
        return $this;
    }

    public function getConfig(){
        return get_post_meta($this->object->ID, PostTypeHelper::META_TEMPLATE_CONFIG, true);
    }

    public function getId(){
        return $this->object->ID;
    }

    public function getName(){
        return $this->object->post_title;
    }

}
