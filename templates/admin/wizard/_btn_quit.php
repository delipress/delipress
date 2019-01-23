<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<a href="<?php echo $this->wizardServices->getPageSetupUrl(); ?>" class="delipress__wizard__quit"> 
    <?php esc_html_e('Exit wizard', 'delipress'); ?> 
    <span class="dashicons dashicons-dismiss"></span>
</a>