<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;


/**
 * Setup
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class Setup extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices  = $containerServices->getService("OptionServices");
        $this->wizardServices  = $containerServices->getService("WizardServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_notices', array($this, 'setupWizard'), 0 );
            add_filter( 'plugin_action_links_' . DELIPRESS_BASE_FILE, array($this, "addSettingsActionLink") );
        }
    }

    public function addSettingsActionLink($actions){
        array_unshift(
            $actions,
            sprintf(
                '<a href="%s">%s</a>',
                $this->wizardServices->getPageSetupUrl(),
                __("Setup Wizard", "delipress")
            )
        );

        return $actions;
    }

    public function setupWizard($plugin){


        $screen   = get_current_screen();
        $screenId = $screen && ! empty( $screen->id ) ? $screen->id : false;

        $alreadyView = get_transient(DELIPRESS_SLUG . "_view_install_notices");
        if($screenId !== "plugins" || $alreadyView){
            return;
        }

        set_transient(DELIPRESS_SLUG . "_view_install_notices", 1);

        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php _e("DeliPress is active! ", "delipress"); ?>
                <a href="<?php echo $this->wizardServices->getPageWizard() ?>">
                    <?php _e( 'Configure it now', 'delipress' ); ?>
                </a>
                <?php _e("or go to <strong>DeliPress > Wizard</strong> to do it later","delipress"); ?>
            </p>
        </div>
        <?php


    }



}
