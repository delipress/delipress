<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>
<?php
    // $steps = array(
    //     array(
    //         "step" => 1,
    //         "label" => __("Choose provider", "delipress")
    //     ),
    //     array(
    //         "step" => 2,
    //         "label" => __("", "delipress")
    //     ),
    //     array(
    //         "step" => 3,
    //         "label" => __("Done! What’s next?", "delipress")
    //     ),
    // );

?>
<a href="<?php echo $this->wizardServices->getPageSetupUrl(); ?>" class="delipress__wizard__modal__logo">
    <img src="<?php echo DELIPRESS_PATH_PUBLIC_IMG ?>/logo.svg" alt="DeliPress">
</a>

<?php /*
<nav class="delipress__wizard__modal__steps delipress__steps">

    <?php
    foreach($steps as $key => $step):
        $class = "";
        if($this->currentStep == $step["step"] ) {
            $class = "delipress__steps__item--is-active";
        }

        if($this->currentStep > $step["step"]){
            $class = " delipress__steps__item--is-valid";
        }

    ?>
        <a href="<?php echo $this->wizardServices->getPageWizard($step["step"]); ?>" class="delipress__steps__item <?php echo $class; ?>">
            <?php echo $step["label"]; ?>
        </a>
    <?php endforeach; ?>
</nav>
*/ ?>
