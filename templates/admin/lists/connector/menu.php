<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PrepareModelHelper;

$provider = $this->optionServices->getProvider();

?>

<div class="delipress__header__actions__left">
    <a href="<?php echo $this->listServices->getChooseCreateUrl(); ?>" class="delipress__button delipress__button--soft"><span class="dashicons dashicons-arrow-left-alt2"></span> <?php esc_html_e('Back', 'delipress'); ?></a>
</div>
<div class="delipress__header__actions__right">
</div>
