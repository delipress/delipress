<?php

    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

    use Delipress\WordPress\Helpers\PageAdminHelper;
    use Delipress\WordPress\Helpers\ActionHelper;
    use Delipress\WordPress\Helpers\OptionHelper;

    $steps = array(
        1 => esc_html__("Setup", "delipress"),
        2 => esc_html__("Choose template", "delipress"),
        3 => esc_html__("Build email", "delipress"),
        4 => esc_html__("Review & Send", "delipress")
    );

    $currentStep   = (PageAdminHelper::getCurrentStep()) ? PageAdminHelper::getCurrentStep() : 1;
    $currentAction = (PageAdminHelper::getCurrentAction()) ? PageAdminHelper::getCurrentAction() : "";
    $nextUrl       = false;
    $prevUrl       = false;

    if($currentStep != count($steps)){
        $nextUrl = true;
    }

    if($currentStep != 1){
        $prevUrl = true;
    }

?>

<div class="delipress__header__actions__left">
    <?php if($currentStep == 1 ): ?>
        <a href="<?php echo $this->campaignServices->getPageUrl(); ?>" class="delipress__button delipress__button--soft">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
            <?php esc_html_e('All Campaigns', 'delipress'); ?>
        </a>
    <?php endif; ?>
    <?php if ($prevUrl): ?>
        <a class="delipress__button delipress-js-step-submit-prevent delipress-js-step-submit-prevent delipress__button--soft" data-next-step="<?php echo $currentStep-1; ?>">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
            <?php echo sprintf( esc_html__('Step %s : %s', 'delipress'), $currentStep-1, $steps[$currentStep-1] ); ?>
        </a>
    <?php endif ?>

</div>
<div class="delipress__header__actions__center">
    <nav class="delipress__steps">
        <?php foreach ($steps as $key => $step):
                $class = "";
                if($currentStep == $key ) {
                    $class = "delipress__steps__item--is-active";
                }

                if($currentStep > $key){
                    $class = " delipress__steps__item--is-valid";
                }
            ?>
            <a href="#" class="delipress__steps__item delipress-js-step-submit-prevent <?php echo $class; ?>" data-next-step="<?php echo $key; ?>">
                <?php echo $step; ?>
            </a>
        <?php endforeach ?>
    </nav>
</div>
<?php if($currentStep != 4 ): ?>
<div class="delipress__header__actions__right">

    <?php if ($nextUrl): ?>
        <a class="delipress__button delipress-js-step-submit-prevent delipress-js-step-submit-prevent delipress__button--main" data-next-step="<?php echo $currentStep+1; ?>">
            <?php echo sprintf( __('Step %s : %s', 'delipress'), $currentStep+1, $steps[$currentStep+1] ); ?> <span class="dashicons dashicons-arrow-right-alt2"></span>
        </a>
    <?php endif ?>
</div>
<?php else: ?>
<div class="delipress__header__actions__right" data-next-step="3">
    <button class="delipress__button delipress-js-step-submit-prevent delipress-js-step-submit-prevent delipress__button--soft" data-next-step="4"><?php esc_html_e('Save draft', 'delipress'); ?></button>
</div>
<?php endif; ?>

<input type="hidden" id="next_step" name="next_step" value="<?php echo $currentStep+1 ?>">

<?php if($this->campaign): ?>
    <input type="hidden" name="campaign_id" value="<?php echo $this->campaign->getId(); ?>" />
<?php endif; ?>

<script>
    jQuery(document).ready(function(){
        var PreventChangeStep = require('javascripts/backend/PreventChangeStep');
        var pcs = new PreventChangeStep("#next_step",".delipress-js-step-submit-prevent");
        pcs.init()
    })
</script>
