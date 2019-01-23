<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<div class="delipress__header__actions__left">
    <?php if(isset($_GET["campaign_id"])): ?>
        <a href="javascript:close();"class="delipress__button delipress__button--soft">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
            <?php esc_html_e("Back to campaign details","delipress"); ?>
        </a>
    <?php endif; ?>
</div>
<div class="delipress__header__actions__center">

</div>
<div class="delipress__header__actions__right"></div>
