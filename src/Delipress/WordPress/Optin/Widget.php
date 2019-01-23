<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Optin\BaseOptin;

use Delipress\WordPress\Helpers\OptinHelper;

/**
 * Widget
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Widget extends AbstractHook implements HooksInterface {

    protected $typeOptin = OptinHelper::WIDGET;

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optinServices = $containerServices->getService("OptinServices");
    }
    
    /**
     * @see HooksInterface
     * @return void
     */
    public function hooks(){

        add_action("widgets_init", array($this, "delipressOptin"));
    
    }

    public function delipressOptin(){
        register_widget( 'Delipress\WordPress\Optin\WidgetClassWP' );
    }
    

}
