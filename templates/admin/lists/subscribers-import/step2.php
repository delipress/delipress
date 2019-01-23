<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<?php

use Delipress\WordPress\Services\Table\TableServices;

use Delipress\WordPress\Models\ListModel;

use Delipress\WordPress\Helpers\PrepareModelHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

$list = PrepareModelHelper::getListFromUrl();

$data        = get_transient(DELIPRESS_SLUG . "_import_subscriber_data_services", false);
$dataForm    = get_transient(DELIPRESS_SLUG . "_import_subscriber_data_form", false);

$providerKey = $this->optionServices->getProviderKey();

$list = null;
if(isset($dataForm["listCreate"]) && !empty($dataForm["listCreate"])){
    $list = new ListModel();
}
else{
    $list = $this->listServices->getList($dataForm["listId"]);
}

$metas           = $this->metaServices->getMetas($list->getId());

if($metas["success"]){
    $metas = $metas["results"];
}else{
    $metas = array();
}

switch($providerKey){
    default:
        $tag = "Email";
        break;
}

$metas[] = $this->providerServices->getMetaModel(
    array(
        "datatype"  => "str",
        "id"        => SubscriberMetaHelper::EMAIL,
        "tag"       => SubscriberMetaHelper::EMAIL,
        "name"      => __("Email", "delipress"),
        "namespace" => "static"
    )
);

$filterFirstName = apply_filters(DELIPRESS_SLUG . "_import_file_is_first_name", array("first_name", "firstname", "Prénom", "prénom", "prenom", "prénom", "FNAME") );
$filterLastName  = apply_filters(DELIPRESS_SLUG . "_import_file_is_last_name", array("last_name", "lastname", "Nom" , "LNAME") );
$alreadyUse      = array();
?>

<?php if(!$data || !$data["success"]): ?>
<div class="error">
    <p><?php esc_html_e('No data found, please retry.', 'delipress'); ?></p>
</div>
<?php endif; ?>

<h1>
    <?php echo esc_html__(get_admin_page_title(), "delipress"); ?>
    <small>
        <?php esc_html_e("Import contacts from CSV file", "delipress"); ?>
    </small>
</h1>

<p class="delipress__intro">
    <?php esc_html_e("Here are the fields we found in your file. Select the ones you want to import.","delipress"); ?>
</p>

<?php if($data && $data["success"]): ?>
    <div class="delipress__csvimport">
        <header>
            <div class="delipress__csvimport__title">
                <?php _e('Found data', 'delipress'); ?>
            </div>
            <div class="delipress__csvimport__title">
                <?php _e('Import as', 'delipress'); ?>
            </div>
        </header>
        <?php foreach($data["results"] as $key => $value):
            $presetData = null;
        ?>
        <section>
            <div class="delipress__csvimport__col">
                <ul class="delipress__csvimport__list">
                    <?php
                    foreach($value as $k => $v): if(!empty($v)):
                        if($presetData === null){
                            if(is_email($v) && !in_array(SubscriberMetaHelper::EMAIL, $alreadyUse)){
                                $presetData = $alreadyUse[] = SubscriberMetaHelper::EMAIL;
                            }
                            else if(in_array($v, $filterFirstName) && !in_array(SubscriberMetaHelper::WORDPRESS_META_FIRST_NAME, $alreadyUse)){
                                $presetData = $alreadyUse[] = SubscriberMetaHelper::WORDPRESS_META_FIRST_NAME;
                            }
                            else if(in_array($v, $filterLastName) && !in_array(SubscriberMetaHelper::WORDPRESS_META_LAST_NAME, $alreadyUse)){
                                $presetData = $alreadyUse[] = SubscriberMetaHelper::WORDPRESS_META_LAST_NAME;
                            }
                        }

                    ?>
                        <li><?php echo $v ?></li>
                    <?php endif; endforeach; ?>
                    <li>&nbsp;<span>...</span></li>
                </ul>
            </div>
            <div class="delipress__csvimport__col">
                <div class="delipress__csvimport__assign">
                    <div class="delipress__select js-delipress-select">
                        <input type="hidden" name="meta_import[<?php echo $key; ?>]">
                        <div class="delipress__select__selected"><?php _e('Select a value', 'delipress'); ?></div>
                        <div class="delipress__select__more">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <ul class="delipress__select__list">
                            <li data-value="-1"><?php _e('Ignore this field', 'delipress'); ?></li>
                            <?php foreach($metas as $k => $meta):
                                $dataValue = $meta->getId();
                                if($presetData === SubscriberMetaHelper::EMAIL && in_array($providerKey, array(ProviderHelper::SENDINBLUE))){
                                    $dataValue = $presetData = SubscriberMetaHelper::EMAIL;
                                }
                                if(empty($presetData) ): ?>
                                    <li data-value="<?php echo $dataValue ?>"><?php echo $meta->getTag() ?></li>
                                <?php else: ?>
                                    <li
                                        data-value="<?php echo $dataValue ?>"
                                        <?php if($dataValue === $presetData): ?>
                                            data-selected="true"
                                        <?php endif; ?>
                                    >
                                        <?php echo $meta->getTitle() ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <p><?php _e('Or create new field', 'delipress'); ?></p>
                    <input name="create_import[<?php echo $key; ?>]" class="delipress__input" type="text">
                </div>
            </div>
        </section>
        <?php endforeach; ?>
    </div>

    <footer class="delipress__content__bottom">
        <button type="submit" class="delipress__button delipress__button--save">
            <?php esc_html_e('Import subscribers', 'delipress'); ?>
        </button>
    </footer>

<?php endif; ?>
