<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<h1><?php echo esc_html__(get_admin_page_title(), "delipress"); ?> <small><?php esc_html_e("All items", "delipress"); ?></small></h1>

<p class="delipress__intro"><?php esc_html_e("We provide everything you need to help your visitors subscribe to your lists!", "delipress"); ?><br>
<?php esc_html_e("Choose and customize your Opt-In forms (Popup, Smart Bar, after content, Widget...).", "delipress"); ?></p>


<?php include_once __DIR__ . "/_active.php"; ?>

<?php include_once __DIR__ . "/_inactive.php"; ?>


<script>
    jQuery(document).ready(function(){
        var DelipressPreventChooseAction = require("javascripts/backend/PreventChooseAction")

        var preventChooseActionClass = new DelipressPreventChooseAction(
            "#form_page",
            ".js-delipress-action-choose"
        )

        preventChooseActionClass.init()
    })
</script>