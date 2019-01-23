<?php

namespace Delipress\WordPress\Taxonomy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * ListUsers
 *
 */
class ListUsers implements HooksInterface, ActivationInterface {


    /**
     * @see DeliSkypress\Models\HooksInterface
     */
    public function hooks(){

        add_action( "init", array($this, 'initTaxonomy') );

    }

    /**
     * @see DeliSkypress\Models\ActivationInterface
     */
    public function activation(){
        $this->initTaxonomy();
    }

    public function initTaxonomy(){

        $args = array(
            'rewrite'            => false,
            'public'             => false,
            'show_admin_column'  => true,
            'show_in_menu'       => "delipress"
        );

        register_taxonomy( 
            TaxonomyHelper::TAXO_LIST, array( PostTypeHelper::CPT_CAMPAIGN ),
            $args
        );    

    }
}