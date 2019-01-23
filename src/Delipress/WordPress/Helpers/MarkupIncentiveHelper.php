<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class MarkupIncentiveHelper
{

    public static function printWhiteMarkupOptin()
    {
        $str = __("Powered by <a href='%s' target='_blank' style='color: #DEDEDE !important; text-decoration:underline !important;'>DeliPress</a>", "delipress");
        $str = sprintf($str, DELIPRESS_STORE_URL);

        $markup = "<div id='delipress__whitemark' class='delipress__white__mark' style='text-align:left !important; color: #DEDEDE !important; font-size:11px !important;'> 
            {$str}
        </div>";

        return $markup;
    }

    /**
     *
     * @param array $params
     * @return void
     */
    public static function printListItem($params)
    {
        ?>
        <div class="delipress__premium-bloc--list delipress__list__item delipress__flex">
            <div class="delipress__list__item__col-box">
                <span class="dashicons dashicons-awards"></span>
            </div>
            <div class="delipress__f3 delipress__list__item__standard">
                <h4><?php echo esc_html($params["title"]); ?></h4>
            </div>
            <div class="delipress__f4 delipress__list__item__standard">
                <p>
                    <?php echo $params["content"]; ?>
                </p>
            </div>
            <div class="delipress__f2 delipress__list__item__col-actions">
                <a class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                    <?php _e("Upgrade to premium", "delipress"); ?>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     *
     * @param array $params
     * @return void
     */
    public static function printBlockItem($params)
    {
        ?>
         <div class="delipress__premium-bloc">
            <div class="delipress__premium-bloc__wrap">
                <div class="delipress__premium-bloc__title">
                    <span class="dashicons dashicons-awards"></span>
                    <h3><?php echo esc_html($params["title"]); ?></h3>
                </div>
                <div class="delipress__premium-bloc__content">
                    <p>
                        <?php _e($params["content"]); ?>
                    </p>
                    <a class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                        <?php esc_html_e("Upgrade to premium", "delipress"); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *
     * @param array $params
     * @return void
     */
    public static function printLineItem($params)
    {
        ?>

        <div class="delipress__premium-bloc delipress__premium-bloc--line">
            <div class="delipress__premium-bloc__wrap delipress__flex delipress__flex--center">
                <div class="delipress__f5">
                    <div class="delipress__premium-bloc__title">
                        <span class="dashicons dashicons-awards"></span>
                        <h3><?php echo esc_html($params["title"]); ?></h3>
                    </div>
                    <div class="delipress__premium-bloc__content">
                        <p>
                            <?php _e($params["content"]); ?>
                        </p>
                    </div>
                </div>
                <div class="delipress__f1">
                    <a class="delipress__button delipress__button--premium delipress__button--small" href="<?php echo delipress_get_url_premium() ?>" target="_blank">
                        <?php esc_html_e("Upgrade to premium", "delipress"); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *
     * @param string $type
     * @param array $params
     */
    public static function printMarkup($type, $params)
    {
        switch ($type) {
            case "list_item":
                self::printListItem($params);
                break;
            case "block":
                self::printBlockItem($params);
                break;
            case "line":
                self::printLineItem($params);
                break;
        }
    }
}
