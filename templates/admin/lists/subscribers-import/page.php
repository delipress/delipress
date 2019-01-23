<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>


<?php

use Delipress\WordPress\Helpers\PrepareModelHelper;

$bytes     = apply_filters( 'import_upload_size_limit', wp_max_upload_size() ); // Filter from WP Core
$size      = size_format( $bytes );
$uploadDir = wp_upload_dir();

$lists              = $this->listServices->getLists(array(
    "limit" => 500
));

if ( ! empty( $uploadDir['error'] ) ):

?>
    <div class="error">
        <p><?php esc_html_e('You need to fix these errors before uploading a file:', 'delipress'); ?></p>
        <p><strong><?php echo $uploadDir['error']; ?></strong></p>
    </div>

<?php else: ?>

<h1>
    <?php esc_html_e(get_admin_page_title(), "delipress"); ?>
    <small>
        <?php esc_html_e("Import contacts from CSV file","delipress"); ?>
    </small>
</h1>

<p class="delipress__intro">
    <?php esc_html_e("Choose delimiter and file to import.","delipress"); ?>
</p>

<div class="delipress__settings">
    <?php if(!empty($lists)): ?>
    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="create_or_update"><?php esc_html_e('Create or update?', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">

            <div class="delipress__buttonsgroup delipress__buttonsgroup--large">
                <div class="delipress__buttonsgroup__cell">
                    <input type="radio" name="create_or_update" id="create_or_update_create" value="create" checked>
                    <label for="create_or_update_create"><?php _e('Create new list', 'delipress'); ?></label>
                </div>
                <div class="delipress__buttonsgroup__cell">
                    <input type="radio" name="create_or_update" id="create_or_update_update" value="update">
                    <label for="create_or_update_update"><?php _e('Update existing list', 'delipress'); ?></label>
                </div>
            </div>
        </div>
        <div class="delipress__settings__item__help delipress__f5"></div>
    </div>

    <div class="delipress__settings__item delipress__flex" style="display:none" id="delipress_update_list">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="list_id"><?php esc_html_e('Choose a list', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">

            <div class="delipress__select delipress__smallinput js-delipress-select">
                <input type="hidden" name="list_id">
                <div class="delipress__select__selected"></div>
                <div class="delipress__select__more">
                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                </div>
                <ul class="delipress__select__list">
                    <?php
                        $first=true;
                        foreach($lists as $list):
                    ?>
                        <li data-value="<?php echo $list->getId() ?>" <?php if($first): $first=false; ?>data-selected="true"<?php endif; ?>>
                            <?php echo $list->getName(); ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("required", "delipress"); ?></span>
        </div>
    </div>
    <?php endif; ?>


    <div class="delipress__settings__item delipress__flex" id="delipress_create_list">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="create_list"><?php esc_html_e('List Name', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input name="create_list" class="delipress__input delipress__smallinput" type="text">
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("required", "delipress"); ?></span>
        </div>
    </div>


    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="delimiter"><?php esc_html_e('Delimiter', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">

            <div class="delipress__select delipress__smallinput js-delipress-select">
                <input type="hidden" name="delimiter">
                <div class="delipress__select__selected"></div>
                <div class="delipress__select__more">
                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                </div>
                <ul class="delipress__select__list">
                    <li data-value="," data-selected="true"><?php esc_html_e('Comma (,)', 'delipress'); ?></li>
                    <li data-value=";"><?php esc_html_e('Semicolon (;)', 'delipress'); ?></li>
                    <li data-value=" "><?php esc_html_e('Space', 'delipress'); ?></li>
                    <?php do_action(DELIPRESS_SLUG . "_select_list_delimiter_import_subscriber"); ?>
                </ul>
            </div>

        </div>
        <div class="delipress__settings__item__help delipress__f5">
        </div>
    </div>
    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="upload"><?php esc_html_e('Choose a CSV file', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input
                type="file"
                id="upload"
                name="import"
                class="delipress__input"
                accept=".csv"
                required
            >
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php echo sprintf(__('Maximum size: %s', "delipress"), $size ); ?></span>
        </div>
    </div>

    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="upload"><?php esc_html_e('Confirmation', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input type="checkbox" id="delipress_import_confirm" class="delipress__checkbox__input" name="delipress_import_confirm" value="1" checked="checked">
            <label for="delipress_import_confirm" class="delipress__checkbox"><?php esc_html_e('I legally got that list', 'delipress'); ?></label>
        </div>
        <div class="delipress__settings__item__help delipress__f5">
            <span class="delipress__mandatory"><?php esc_html_e("required", "delipress"); ?></span>
        </div>
    </div>

    <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />

</div>

<footer class="delipress__content__bottom">
    <button type="submit" class="delipress__button delipress__button--save">
        <?php esc_html_e('Prepare import', 'delipress'); ?>
    </button>
</footer>

<?php endif; ?>

<script>
    (function($) {
        $(document).ready(function(){
            $('input[name=create_or_update]').change(function() {
                var value = $(this).val();

                if(value=="create") {
                    $('#delipress_create_list').show();
                    $('#delipress_update_list').hide();
                } else {
                    $('#delipress_create_list').hide();
                    $('#delipress_update_list').show();
                }
            })
        })
    })(jQuery);
</script>
