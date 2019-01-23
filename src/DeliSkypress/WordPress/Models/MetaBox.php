<?php

namespace DeliSkypress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

/**
 * Metabox
 *
 * @author DeliPress <thomasdeneulin@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class Metabox extends AbstractHook implements HooksInterface{

    protected $metaBoxes;

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){}

    public function hooks(){
        add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ), 1, 0 );
    }

    public function addMetaBox(){

        foreach ($this->metaBoxes as $key => $metaBox) {
            foreach ($metaBox["post_types"] as $keyPostType => $postType) {
                add_meta_box(
                    $key,
                    $metaBox["title"],
                    array( $this, $metaBox["callback"] ),
                    $postType
                );
            }
        }
    }

}









