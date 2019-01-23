<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PrepareModelHelper;
use Delipress\WordPress\Helpers\ErrorFieldsNoticesHelper;
use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\CodeErrorHelper;

$list   = PrepareModelHelper::getListFromUrl();
$idList = $list->getId();
?>

<h1>
    <?php echo esc_html__(get_admin_page_title(), "delipress"); ?>
    <small>
        <?php if(empty($idList)): ?>
            <?php esc_html_e("Add new list","delipress"); ?>
        <?php else: ?>
            <?php esc_html_e("Edit list","delipress"); ?>
        <?php endif; ?>
    </small>
</h1>

<div class="delipress__settings">
    <?php if($list->getId()): ?>
        <input type="hidden" name="list_id" value="<?php echo $list->getId(); ?>" />
    <?php endif; ?>
    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="<?php echo TaxonomyHelper::LIST_NAME; ?>"><?php esc_html_e('Name your list', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input
                id="<?php echo TaxonomyHelper::LIST_NAME; ?>"
                name="<?php echo TaxonomyHelper::LIST_NAME; ?>"
                type="text"
                class="delipress__input <?php if(ErrorFieldsNoticesHelper::hasError(TaxonomyHelper::LIST_NAME) ): ?> delipress__input--error<?php endif; ?>"
                value="<?php echo esc_attr(AdminFormValues::displayOldValues(TaxonomyHelper::LIST_NAME, $list->getName()) ); ?>"
                placeholder="" />
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("Required", "delipress"); ?></span>
            <?php ErrorFieldsNoticesHelper::displayError(TaxonomyHelper::LIST_NAME) ?>
        </div>
    </div>
    <?php if(empty($idList)): ?>
        <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Create list', 'delipress'); ?></button>
    <?php else: ?>
        <button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e('Save changes', 'delipress'); ?></button>
    <?php endif; ?>

</div> <!-- delipress__settings -->
