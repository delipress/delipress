<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptinHelper;

$licenseStatusValid  = $this->optionServices->isValidLicense();

$optinHelper = OptinHelper::getOptinByKey($optin->getType());

$pageCreateUrl = ($optinHelper["key"] === OptinHelper::SHORTCODE) ? 1 : 2;

?>

<li class="delipress__list__item delipress__flex delipress__flex--center">
    <div class="delipress__list__item__col-box">
        <input type="checkbox" id="<?php echo $optin->getId(); ?>" class="delipress__checkbox__input <?php echo $class; ?>" name="<?php echo $nameInput; ?>" value="<?php echo $optin->getId(); ?>" />
        <label for="<?php echo $optin->getId(); ?>" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__f3">
        <?php
            $urlTitle = $this->optinServices->getCreateUrl(2, $optin->getId());
            if($optinHelper["key"] === OptinHelper::CONTACT_FORM_7){
                $urlTitle = $this->optinServices->getCreateUrl(1, $optin->getId());
            }
        ?>
        <a href="<?php echo $urlTitle; ?>" class="delipress__list__item__title">
            <?php echo $optin->getTitle(); ?>
        </a>
    </div>
    <?php if($optin->getType() === OptinHelper::SHORTCODE): ?>
        <div class="delipress__list__item__standard delipress__f2">
            <div class="delipress__list__item__copy">
                <button
                    type="button"
                    name="button"
                    data-clipboard-text='[delipress_optin id="<?php echo $optin->getId(); ?>"]'
                    data-copied-text="<?php _e('Copied!', 'delipress'); ?>"
                    class="delipress-copy delipress__button delipress__button--soft delipress__button--small">
                        <?php esc_html_e('Copy Shortcode', 'delipress'); ?>
                    </button>
            </div>
        </div>
    <?php else: ?>
    <div class="delipress__list__item__standard delipress__list_item--no-mobile delipress__f2">
        <?php esc_html_e('Type', 'delipress'); ?> <br>
        <strong><?php echo $optin->getTypeLabel(); ?></strong>
    </div>
    <?php endif; ?>
    <div class="delipress__list__item__standard delipress__list_item--no-mobile delipress__f3">
        <?php esc_html_e('List', 'delipress'); ?> <br>
        <?php
        $lists = $optin->getLists();

        if(!empty($lists)):
        foreach($lists as $key => $list):
            if($list->getName() == ""):
        ?>
        <span class="delipress__tag delipress__tag--empty"><?php esc_html_e('Need to be redefined', 'delipress'); ?></span>
        <?php
        else:
        ?>
            <a href="<?php echo $this->subscriberServices->getPageSubscribersUrl($list->getId()); ?>" class="delipress__tag">
                 <?php echo esc_html($list->getName() ); ?>
            </a>
        <?php
        endif;
        endforeach;
        else:
        ?>
        <span class="delipress__tag delipress__tag--empty"><?php esc_html_e('Not yet defined', 'delipress'); ?></span>
        <?php endif; ?>
    </div>
    <?php if($licenseStatusValid): ?>
        <div class="delipress__list__item__standard delipress__center delipress__f2">
            <?php esc_html_e('Impressions', 'delipress'); ?><br>
            <span class="delipress__list__item__num"><?php echo $optin->getCounterView(); ?></span>
        </div>
        <div class="delipress__list__item__standard delipress__center delipress__f2">
            <?php esc_html_e('Visitors converted', 'delipress'); ?><br>
            <span class="delipress__list__item__num"><?php echo $optin->getCounterConvert(); ?></span>

        </div>
        <div class="delipress__list__item__standard delipress__center delipress__f3">
            <?php esc_html_e('Conversion Rate', 'delipress'); ?><br>
            <span class="delipress__list__item__num"><?php echo $optin->getRateCounterView(); ?>%</span>
        </div>
    <?php endif; ?>
    <div class="delipress__list__item__col-actions delipress__f3">
        <a href="<?php echo $this->optinServices->getCreateUrl(1, $optin->getId() ); ?>" class="delipress__button delipress__button--soft">
           <span class="dashicons dashicons-admin-generic"></span>
        </a>
        <nav class="delipress__more">
            <a href="#" class="delipress__button delipress__button--soft">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </a>
            <ul class="delipress__more__sub">
                <?php if($optinHelper["has_behavior"]): ?>
                <li>
                    <a
                        href="<?php echo $this->optinServices->getCreateUrl(3, $optin->getId() ); ?>"
                    >
                        <?php esc_html_e("Edit behavior", "delipress"); ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($licenseStatusValid): ?>
                <li>
                    <a
                        href="<?php echo $this->optinServices->getOptinStatistic($optin->getId() ); ?>"
                    >
                        <?php esc_html_e("Statistics", "delipress"); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a
                        href="<?php echo $this->optinServices->getDeleteOptinUrl($optin->getId() ); ?>"
                        class="js-prevent-delete-action"
                        data-title="<?php echo sprintf(esc_html__("Delete %s", 'delipress'), esc_attr($optin->getTitle())); ?>"
                        data-message="<?php esc_html_e("Do you really want to delete this Opt-In form?", 'delipress'); ?>"
                    >
                        <?php esc_html_e("Delete", "delipress"); ?>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</li>
