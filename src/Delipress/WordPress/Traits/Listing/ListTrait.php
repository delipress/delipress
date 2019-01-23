<?php

namespace Delipress\WordPress\Traits\Listing;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Models\ListModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait ListTrait {

    /**
     * 
     * @param string $value
     * @return void|ListModel
     */
    public function checkListExist($value){
        if($value == "-1"){
            $this->missingParameters[PostTypeHelper::CAMPAIGN_TAXO_LISTS] = 1;
            return null;
        }

        $term = get_term( $value, TaxonomyHelper::TAXO_LIST);
        
        if(!is_wp_error($term) && !is_null($term) ){
            $model = new ListModel();
            $model->setList($term);
            return $model;
        }

        $this->missingParameters[PostTypeHelper::CAMPAIGN_TAXO_LISTS] = 1;

        return null;
        
    }

    
    /**
     * 
     * @param array $value
     * @return array
     */
    public function checkListsExist($values){
        if(!is_array($values)){
            $this->missingParameters["lists_not_empty"] = 1;
            return $values;
        }

        foreach($values as $key => $value){
            $values[$key] = $this->checkListExist($value);
        }

        return $values;
        
    }



 

}

