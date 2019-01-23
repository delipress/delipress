<?php 
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

$disabledCreateList = apply_filters(DELIPRESS_SLUG . "_disabled_create_list", false);

$provider = $this->optionServices->getProvider();

if(!$provider["is_connect"]){
    return;
}

if($disabledCreateList){
    return;
}

?>

<div class="delipress__header__actions__left">
    <a href="<?php echo $this->listServices->getChooseCreateUrl(); ?>" class="delipress__button delipress__button--main"><?php esc_html_e('Add a new list', 'delipress'); ?></a>
</div>
<div class="delipress__header__actions__right">
    <a href="<?php echo $this->subscriberServices->getPageSubscribersImport(); ?>" class="delipress__button delipress__button--soft"><?php esc_html_e('Import CSV file', 'delipress'); ?></a>
</div>
