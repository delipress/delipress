<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptinHelper;

switch($type){
    case OptinHelper::POPUP:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content"></div>
                <div class="delipress__minioptin__sidebar"></div>
            </div>
            <div class="delipress__minioptin__popup"></div>
        </div>
        <?php
        break;
    case OptinHelper::SMARTBAR:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content"></div>
                <div class="delipress__minioptin__sidebar"></div>
            </div>
            <div class="delipress__minioptin__bar"></div>
        </div>
        <?php
        break;
    case OptinHelper::FLY:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content"></div>
                <div class="delipress__minioptin__sidebar"></div>
            </div>
            <div class="delipress__minioptin__flyin"></div>
        </div>
        <?php
        break;
    case OptinHelper::WIDGET:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content"></div>
                <div class="delipress__minioptin__sidebar">
                    <div class="delipress__minioptin__widget"></div>
                </div>
            </div>
        </div>
        <?php
        break;
    case OptinHelper::AFTER_CONTENT:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content">
                    <div class="delipress__minioptin__aftercontent"></div>
                </div>
                <div class="delipress__minioptin__sidebar"></div>
            </div>
        </div>
        <?php
        break;
    case OptinHelper::LOCKED:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__content">
                    <div class="delipress__minioptin__lockedcontent">
                        <span class="dashicons dashicons-lock"></span>
                    </div>
                </div>
                <div class="delipress__minioptin__sidebar"></div>
            </div>
        </div>
        <?php
        break;
    case OptinHelper::CONTACT_FORM_7:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG . "/third/cf7.png" ?>" alt="Contact Form 7" />
        </div>
        <?php
        break;
    case OptinHelper::GRAVITY_FORM:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG . "/third/gf.png" ?>" alt="Gravit Form" />
        </div>
        <?php
        break;
    case OptinHelper::WOOCOMMERCE_ORDER:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
        <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG . "/third/woocommerce.png" ?>" alt="WooCommerce" />
    </div>
        <?php
        break;
    case OptinHelper::SHORTCODE:
        ?>
        <div class="delipress__collection__item__thumb delipress__minioptin">
            <div class="delipress__minioptin__page">
                <div class="delipress__minioptin__shortcode">[DeliPress]</div>
            </div>
        </div>
        <?php
        break;
}

?>
