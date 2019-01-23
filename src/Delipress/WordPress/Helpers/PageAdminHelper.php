<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class PageAdminHelper{

    const PAGE_POSITION_SETTINGS = 30;

    const PAGE_SETUP       = "setup";
    const PAGE_CAMPAIGNS   = "campaigns";
    const PAGE_LISTS       = "lists";
    const PAGE_AUTOMATION  = "automation";
    const PAGE_SYNCHRONIZE = "synchronize";

    const PAGE_OPTIN_FORMS = "optin-forms";

    const PAGE_OPTIONS      = "options";
    const PAGE_WIZARD       = "wizard";

    public static function getPageNameByConst($const){
        return sprintf("%s-%s", DELIPRESS_SLUG, $const);
    }

    /**
     * @filter delipress_page_position
     */
    public static function getPagePosition(){
        return apply_filters("delipress_page_position", self::PAGE_POSITION_SETTINGS);
    }

    /**
     * @static
     * @return string
     */
    public static function getCurrentTab(){
        return (isset($_GET['tab'])) ? esc_html($_GET['tab']) : "";
    }

    /**
     * @static
     * @return string
     */
    public static function getCurrentAction(){
        return (isset($_GET['action'])) ? esc_html($_GET['action']) : false;
    }

    /**
     * @static
     * @return string
     */
    public static function getCurrentStep(){
        return (isset($_GET['step'])) ? (int) $_GET['step'] : false;
    }

    /**
     * @static
     * @return string
     */
    public static function getPageIncludeAdmin($namePageInclude){
        $currentAction = self::getCurrentAction();
        $currentStep   = self::getCurrentStep();

        $pageInclude = sprintf(
            "%s/%s/page.php",
            DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
            $namePageInclude
        );

        if($currentAction) {
            if($currentStep && $currentStep != 1) {

                $pageInclude = sprintf(
                    "%s/%s/%s/step%s.php",
                    DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
                    $namePageInclude,
                    $currentAction,
                    $currentStep
                );

            } else {

                $pageInclude = sprintf(
                    "%s/%s/%s/page.php",
                    DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
                    $namePageInclude,
                    $currentAction
                );

            }
        }
        else if($currentStep && $currentStep != 1){
            $pageInclude = sprintf(
                "%s/%s/step%s.php",
                DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
                $namePageInclude,
                $currentStep
            );
        }

        return $pageInclude;
    }


    /**
     * @static
     * @return string
     */
    public static function getMenuIncludeAdmin($namePageInclude){
        $currentAction = self::getCurrentAction();

        $menuInclude = sprintf(
            "%s/%s/menu.php",
            DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
            $namePageInclude
        );

        if($currentAction) {

            $menuInclude = sprintf(
                "%s/%s/%s/menu.php",
                DELIPRESS_PLUGIN_DIR_TEMPLATES_ADMIN,
                $namePageInclude,
                $currentAction
            );
        }


        return $menuInclude;
    }

}
