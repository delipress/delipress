<?php
    defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
?>
<?php if(property_exists($this, "tabs") ): ?>
<div class="delipress__tabs">
    <?php foreach ($this->tabs as $key => $tab): ?>
        <a href="<?php echo  add_query_arg(array("tab" => $key)); ?>" class="delipress__tabs__item <?php if($this->currentTab === $key): echo "delipress__isactive"; endif; ?>">
            <?php echo $tab; ?>
        </a>
    <?php endforeach ?>
</div>
<?php endif; ?>
