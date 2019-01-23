<?php

namespace Delipress\WordPress\PostType;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * DynamicList
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class DynamicList implements HooksInterface {


    /**
     * @see DeliSkypress\Models\HooksInterface
     */
    public function hooks(){

        // add_action( "init", array($this, 'initPostType') );

    }

    public function initPostType(){

        $args = array(
            'labels' => array(
                'name' => "campagne"
            ),
            'public'             => false,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => "campaigns" ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'show_in_menu'       => false,
            'show_in_nav_menus'  => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports'           => array("title")
        );

        register_post_type(
            PostTypeHelper::CPT_CAMPAIGN , $args
        );
    }
}
