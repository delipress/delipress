<?php

namespace Delipress\WordPress\PostType;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * OptinForms
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class OptinForms implements HooksInterface {


    /**
     * @see DeliSkypress\Models\HooksInterface
     */
    public function hooks(){

        add_action( "init", array($this, 'initPostType') );

    }


    public function initPostType(){

        $args = array(
            'labels'              => array(
                "name" => __("Opt-In Forms", "delipress")
            ),
            'public'              => false,
            'query_var'           => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'show_in_menu'        => false,
            'supports'            => array("title")

        );

        register_post_type(
            PostTypeHelper::CPT_OPTINFORMS , $args
        );
    }
}
