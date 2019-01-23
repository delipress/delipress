<?php
    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
    
    use Delipress\WordPress\Helpers\PrepareModelHelper;
    
    $list = PrepareModelHelper::getListFromUrl();

    $disabledCreateSubscriber = apply_filters(DELIPRESS_SLUG . "_disabled_create_subscriber", false);

    if($disabledCreateSubscriber){
        return;
    }
?>

<div class="delipress__header__actions__left">
    <a href="<?php echo $this->listServices->getPageListUrl(); ?>" class="delipress__button delipress__button--soft"><span class="dashicons dashicons-arrow-left-alt2"></span> <?php esc_html_e('Back', 'delipress'); ?></a>
    <a href="<?php echo $this->subscriberServices->getCreateUrl($_GET["list_id"]); ?>" class="delipress__button delipress__button--main"><?php esc_html_e('Add a subscriber', 'delipress'); ?></a>
    
</div>
<div class="delipress__header__actions__right">
</div>
