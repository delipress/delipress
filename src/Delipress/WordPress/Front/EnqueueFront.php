<?php

namespace Delipress\WordPress\Front;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksFrontInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;

/**
 * EnqueueFront
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EnqueueFront extends AbstractHook implements HooksFrontInterface{


    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){}


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){
        add_action( 'wp_enqueue_scripts', array( $this, 'frontEnqueueScripts' ) );
    }

    /**
     * @return void
     */
    public function frontEnqueueScripts(){

        $loadAfterAdmin = false;
        if ( current_theme_supports( 'admin-bar' ) && is_user_logged_in()) {
            $loadAfterAdmin = 'admin-bar';
        }

        $optinsAvailable = get_posts(
            array(
                "post_type" => PostTypeHelper::CPT_OPTINFORMS,
                "meta_query" => array(
                    array(
                        "key" => PostTypeHelper::META_OPTIN_IS_ACTIVE,
                        "value" => 1
                    )
                ),
                "posts_per_page" => 1
            )
        );

        if(empty($optinsAvailable)){
            return;
        }

        wp_enqueue_style( 'delipress-style', DELIPRESS_PATH_PUBLIC_CSS.'/optins.css' );

        wp_register_script( 'delipress-optin',  DELIPRESS_PATH_PUBLIC_JS.'/optins.js' , $loadAfterAdmin, false, true );
        wp_enqueue_script( 'delipress-optin' );

        // wp_add_inline_script( 'delipress-optin', "
        //     function delipressLoadOptin(){
        //         require('javascripts/optins/BaseOptin');
        //     }

        //     window.addEventListener ?
        //         window.addEventListener('load',delipressLoadOptin,false) :
        //         window.attachEvent && window.attachEvent('onload', delipressLoadOptin);
        // " );
       wp_localize_script('delipress-optin', 'translationDelipressReact', array(
           'text_link_rgpd' =>  __('View Privacy Policy', 'delipress')
       ));

       wp_localize_script('delipress-optin', 'DelipressGRPD', array(
           'privacy_page_url' =>  get_permalink( get_option('wp_page_for_privacy_policy') )
       ));

        wp_localize_script('delipress-optin', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    }



}
