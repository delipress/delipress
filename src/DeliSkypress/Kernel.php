<?php

namespace DeliSkypress;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\ContainerServiceTrait;
use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\HooksFrontInterface;
use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\DeactivationInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

abstract class Kernel{
    use ContainerServiceTrait;

    /** 
     * @var string
     */
    protected $slug;

    /**
     *
     * @param stdClass $action
     */
    protected function preHooks($action){
        if($action instanceOf AbstractHook){
            $action->preHooks();    
        }
    }

    /**
     * @return Kernel
     */
    public function execute(){

        foreach ($this->getActions() as $key => $action) {
            
            switch(true) {  
                case $action instanceOf HooksAdminInterface:
                    if (is_admin()) {
                        $this->preHooks($action);
                        $action->hooks();
                    }
                    break;

                case $action instanceOf HooksFrontInterface:
                    if (!is_admin()) {
                        $this->preHooks($action);
                        $action->hooks();
                    }
                    break;

                case $action instanceOf HooksInterface:
                    $this->preHooks($action);
                    $action->hooks();
                    break;
            }
        }

        return $this;

    }
    
    /**
     * @return void
     */
    public function executePlugin(){
        switch (current_filter()) {
            case 'plugins_loaded':
                foreach ($this->getActions() as $key => $action) {
                    switch(true) {  
                        case $action instanceOf HooksAdminInterface:
                            if (is_admin()) {
                                $this->preHooks($action);
                                $action->hooks();
                            }
                            break;

                        case $action instanceOf HooksFrontInterface:
                            if (!is_admin()) {
                                $this->preHooks($action);
                                $action->hooks();
                            }
                            break;

                        case $action instanceOf HooksInterface:
                            $this->preHooks($action);
                            $action->hooks();
                            break;
                    }
                }
                break;
            case 'activate_' . $this->slug . '/' . $this->slug . '.php':
                foreach ($this->getActions() as $key => $action) {
                    
                    if($action instanceOf ActivationInterface){
                        $this->preHooks($action);
                        $action->activation();
                    }
                }
                break;
            case 'deactivate_' . $this->slug . '/' . $this->slug . '.php':
                foreach ($this->getActions() as $key => $action) {

                    if($action instanceOf DeactivationInterface){
                        $this->preHooks($action);
                        $action->deactivation();
                    }
                }
                break;
        }
    }

}

