<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\AdminFormValues;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\OptinHelper;

$behaviors  = $this->optin->getBehavior();
$oldValues  = AdminFormValues::getFormValues();

// POST TYPES
$args = array(
    'show_ui' => true,
    'public' => true,
);

$postTypes = get_post_types( $args, "objects");
unset($postTypes['attachment']);
$postTypes = apply_filters(DELIPRESS_SLUG . "_display_post_types_optins", $postTypes);

// TAXOS
$taxos = get_taxonomies($args, "objects");
$taxos = apply_filters(DELIPRESS_SLUG . "_display_taxonomies_optins", $taxos);

$nonceAjax = wp_create_nonce(ActionHelper::REACT_AJAX);

$generalsPage = array(
    "homepage" => array(
        "title" => esc_html__("Home","delipress")
    ),
    "blogpage" => array(
        "title" => esc_html__("Blog page","delipress")
    ),
    "archives" => array(
        "title" => esc_html__("Archive page","delipress")
    ),
    "posttypes" => array(
        "title" => esc_html__("All Post Types","delipress"),
        "help" => esc_html__("Displays on all single posts, pages and other post types. More info about post types in WordPress by <a href='https://codex.wordpress.org/Post_Types' target='_blank'>clicking here</a>.", "delipress"),
        "switch" => true
    ),
    "categories" => array(
        "title" => esc_html__("All Categories","delipress"),
        "help" => esc_html__("Displays on all category and taxonomy archive pages of your website.", "delipress"),
        "switch" => true
    ),
);

$checkedEverything = false;

if(
    isset($behaviors["display_pages"]) &&
    array_key_exists("everything", $behaviors["display_pages"])
){
    $checkedEverything     = $behaviors["display_pages"]["everything"]["all"];
}

if(!isset($behaviors["display_pages"])){
    $checkedEverything = true;
}


?>

<h2><?php esc_html_e('Display on', 'delipress') ?> </h2>

<div class="delipress__settings--display">
    <?php  
        // Polylang && WPML compatibility
        if( is_plugin_active( 'polylang/polylang.php' ) && is_plugin_active( "sitepress-multilingual-cms/sitepress.php" ) ):
        ?>
            <div class="delipress__flex delipress__settings__item">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="display_choice_language"><?php _e('⚠️ It looks like you have Polylang and WPML active at the same time. DeliPress only support one multilingual plugin at a time. <br/> Please deactivate one of them.', 'delipress');?></label>
                </div>
            </div>
        <?php
        elseif ( is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( "sitepress-multilingual-cms/sitepress.php" ) ):
            $displayLanguages = (isset($behaviors["display_languages"])) ? $behaviors["display_languages"] : "";

            // Get an array of active language 
            if (is_plugin_active( 'polylang/polylang.php' )) {
                $languages = PLL()->model->get_languages_list();
            }else{
                $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
            }
            
    ?>
        <div class="delipress__flex delipress__settings__item">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="display_choice_language"><?php _e('Choose language', 'delipress');?></label>
            </div>
            <div class="delipress__f4 delipress__settings__item__field">
                <?php 
                    
                ?>
                <select name="display_languages" id="display_languages">
                    <option 
                        <?php if($displayLanguages == "all"){
                            echo 'selected';
                        } ?> 
                        value="all"
                    >
                        <?php _e("All", "delipress"); ?>
                    </option>
                    <?php 
                    if (is_plugin_active( 'polylang/polylang.php' )):
                    foreach ($languages as $keyLang => $lang) : ?>
                        <option 
                            <?php if($displayLanguages == $lang->slug){
                                echo 'selected';
                            }elseif(!isset($displayLanguages) && $keyLang == 0){
                                echo 'selected';
                            } ?> 
                            value="<?php echo $lang->slug; ?>"
                        >
                            <?php echo $lang->name; ?>
                        </option>
                    <?php endforeach; endif;?>
                    <?php 
                        if (is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' )) :
                            foreach ($languages as $key => $lang) : ?>
                            
                             <option <?php if($displayLanguages == $lang["code"]){
                                echo 'selected';
                            }elseif(!isset($displayLanguages) && $key == 0){
                                echo 'selected';
                            } ?> 
                            value="<?php echo $lang["code"]; ?>">
                                <?php echo $lang["translated_name"]; ?>
                            </option>
                    <?php
                        endforeach;
                        endif;
                    ?>
                </select>
            </div>
            <div class="delipress__f5"></div>
        </div>
    <?php
        endif; // End polylang && wpml
    ?>
    <div class="delipress__settings__item delipress__flex">
        <div class="delipress__settings__item__label delipress__f2">
            <label for="display_all_everything"><?php esc_html_e('Everything', 'delipress') ?></label>
        </div>
        <div class="delipress__settings__item__field delipress__f4">
            <input
                type="checkbox"
                id="display_all_everything"
                class="delipress__checkbox__input"
                <?php if($checkedEverything): ?>checked="checked"<?php endif; ?>
                name="display_pages[everything][all]"
            />
            <label for="display_all_everything" class="delipress__checkbox"></label>
        </div>
        <div class="delipress__settings__item__help delipress__f5">
        </div>
    </div>
    <div class="delipress__settings__switch-everything">
        <?php if(!in_array($this->optin->getType(), array(OptinHelper::AFTER_CONTENT)) ): ?>
            <?php foreach($generalsPage as $key => $generalPage):

                $checked = false;
                if(
                    isset($behaviors["display_pages"]) &&
                    array_key_exists($key, $behaviors["display_pages"]) &&
                    array_key_exists("all", $behaviors["display_pages"][$key])
                ){
                    $checked     = $behaviors["display_pages"][$key]["all"];
                }

            ?>
                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f2">
                        <label for="display_all_<?php echo $key; ?>"><?php echo $generalPage["title"]; ?></label>
                        <?php if (!empty($generalPage["help"])): ?>
                            <span title="<?php echo esc_html($generalPage["help"]); ?>" class="delipress__tooltip dashicons dashicons-editor-help"></span>
                        <?php endif; ?>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <input
                            type="checkbox"
                            id="display_all_<?php echo $key; ?>"
                            class="delipress__checkbox__input"
                            name="display_pages[<?php echo $key; ?>][all]"
                            <?php if($checked): ?>checked="checked"<?php endif; ?>
                            <?php if(isset($generalPage["switch"]) && $generalPage["switch"]): ?>data-switch="delipress__switch-<?php echo $key; ?>"<?php endif; ?>
                        />
                        <label for="display_all_<?php echo $key; ?>" class="delipress__checkbox"></label>
                    </div>
                    <div class="delipress__settings__item__help delipress__f5">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif ?>

        <h3><?php esc_html_e('Display on these post types', 'delipress'); ?></h3>

        <div id="delipress__switch-posttypes">
            <?php foreach($postTypes as $keyPostType => $postType):

                    $all = false;
                    if(
                        isset($behaviors["display_pages"]) &&
                        array_key_exists($keyPostType, $behaviors["display_pages"]) &&
                        array_key_exists("all", $behaviors["display_pages"][$keyPostType])
                    ){
                        $all     = $behaviors["display_pages"][$keyPostType]["all"];
                    }
                ?>

                <div class="delipress__flex delipress__settings__item">
                    <div class="delipress__settings__item__label delipress__f2">
                        <label for="display_choice_all_<?php echo $keyPostType; ?>"><?php esc_html_e("All", "delipress") ?> <?php echo $postType->label ?></label>
                    </div>
                    <div class="delipress__f4 delipress__settings__item__field">
                        <input
                            type="checkbox"
                            class="delipress__checkbox__input"
                            id="display_choice_all_<?php echo $keyPostType; ?>"
                            name="display_pages[<?php echo $keyPostType; ?>][all]"
                            data-switch="delipress__switch-<?php echo $keyPostType ?>"
                            <?php if($all): ?>checked="checked"<?php endif; ?>
                        />
                        <label for="display_choice_all_<?php echo $keyPostType; ?>" class="delipress__checkbox"></label>
                    </div>
                    <div class="delipress__f5"></div>
                </div>
            <?php endforeach; ?>


            <div class="delipress__settings__filters delipress__flex">
                <div class="delipress__f2">
                    <p>
                        <strong><?php esc_html_e('Choose specific posts', 'delipress'); ?></strong><br>
                        <?php esc_html_e('Define one or more posts/pages/post types where you want to show this Opt-In form.', "delipress") ?>
                    </p>
                </div>
                <div class="delipress__f10">
                    <div class="delipress__settings__filter__select">
                        <?php foreach($postTypes as $keyPostType => $postType):

                            $choicesPages = array();

                            if(
                                isset($behaviors["display_pages"]) && 
                                array_key_exists($keyPostType, $behaviors["display_pages"]) &&
                                array_key_exists("choice_pages", $behaviors["display_pages"][$keyPostType])

                            ){
                                $choicesPages     = $behaviors["display_pages"][$keyPostType]["choice_pages"];
                            }

                            $postsSelected = array();
                            if(!empty($choicesPages)){
                                $postsSelected = get_posts(
                                    array(
                                        "post_type" => $keyPostType,
                                        "post__in"  => $choicesPages
                                    )
                                );
                            }
                        ?>
                            <div id="delipress__switch-<?php echo $keyPostType; ?>">
                                <strong><?php echo $postType->label; ?> </strong>

                                <div class="delipress__settings__item delipress__settings__item-hauto delipress__flex">
                                    <div class="delipress__settings__item__field delipress__f4">

                                        <select
                                            id="display_choice_<?php echo $keyPostType; ?>"
                                            name="display_pages[<?php echo $keyPostType; ?>][choice_pages][]"
                                            multiple="multiple"
                                        ></select>

                                        <div class="delipress__select2-results" id="delipress__select2-results-<?php echo $keyPostType; ?>"></div>

                                    </div>
                                    <div class="delipress__settings__item__help delipress__f5">
                                    </div>
                                </div>
                            </div>
                            <script>
                                jQuery(document).ready(function(){
                                    var $        = jQuery
                                    var selected = [];
                                    var initials = [];

                                    <?php if(!empty($postsSelected)): ?>
                                        <?php foreach($postsSelected as $key => $post): ?>
                                            initials.push({
                                                id: <?php echo (int) $post->ID; ?>,
                                                text: "<?php echo $post->post_title ?>",
                                                selected: true
                                            });
                                            selected.push(<?php echo (int) $post->ID; ?>)
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    $("#display_choice_<?php echo esc_js($keyPostType); ?>").select2({
                                        placeholder: {
                                            id: '-1',
                                            text: "<?php echo sprintf(esc_html__("Select %s", "delipress"), $postType->label ); ?>"
                                        },
                                        escapeMarkup: function(m) {
                                            return m
                                        },
                                        data: initials,
                                        ajax: {
                                            url: ajaxurl,
                                            method: "POST",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function (params) {
                                                return {
                                                    s: params.term,
                                                    action: "delipress_get_posts",
                                                    _wpnonce_ajax: "<?php echo esc_js($nonceAjax);  ?>",
                                                    post_type: "<?php echo esc_js($keyPostType); ?>",
                                                    offset: params.page
                                                }
                                            },
                                            processResults: function (data, params) {
                                                params.page = params.page || 1

                                                let resultsForSelect2 = $.map(data.data.results, function (obj) {
                                                    obj.id   = obj['ID'];
                                                    obj.text = obj.post_title;

                                                    return obj;
                                                });

                                                return {
                                                    results: resultsForSelect2,
                                                    pagination: {
                                                        more: (params.page * 20) < data.data.total_count
                                                    }
                                                }
                                            },
                                            cache: true
                                        },
                                        dropdownParent: $('#delipress__select2-results-<?php echo esc_js($keyPostType); ?>'),
                                        minimumInputLength: 1,
                                        templateResult: function(post){
                                            return delipressTemplateHtmlSelect('<?php echo esc_js($keyPostType); ?>', post.text)
                                        },
                                        templateSelection: function(post){
                                            return delipressTemplateHtmlSelect('<?php echo esc_js($keyPostType); ?>', post.text)
                                        }
                                    })
                                })
                            </script>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!in_array($this->optin->getType(), array(OptinHelper::AFTER_CONTENT)) ): ?>
            <h3><?php _e('Display on these categories', 'delipress'); ?></h3>
            <div id="delipress__switch-categories" class="delipress__settings__filters delipress__flex">
                <div class="delipress__f2">
                    <p>
                        <strong><?php _e('Choose specific terms', 'delipress'); ?></strong><br>
                        <?php _e('Define one or more categories terms where you want to show this Opt-In form.') ?>
                    </p>
                </div>
                <div class="delipress__f10">
                    <div class="delipress__settings__filter__select">
                        <?php foreach($taxos as $keyTaxo => $taxo):

                            $choicesPages = array();
                            if(
                                isset($behaviors["display_pages"]) && 
                                array_key_exists($keyTaxo, $behaviors["display_pages"]) &&
                                array_key_exists("choice_pages", $behaviors["display_pages"][$keyTaxo])
                            ){
                                $choicesPages     = $behaviors["display_pages"][$keyTaxo]["choice_pages"];
                            }

                            $termSelected = array();
                            if(!empty($choicesPages)){
                                $termSelected = get_terms(
                                    array(
                                        "taxonomy"    => $keyTaxo,
                                        "object_ids"  => $choicesPages
                                    )
                                );
                            }
                        ?>
                            <strong><?php echo $taxo->label; ?> </strong>

                            <div class="delipress__settings__item delipress__settings__item-hauto delipress__flex">
                                <div class="delipress__settings__item__field delipress__f4">

                                    <select
                                        id="display_choice_<?php echo esc_html($keyTaxo); ?>"
                                        name="display_pages[<?php echo esc_html($keyTaxo); ?>][choice_pages][]"
                                        multiple="multiple"
                                    ></select>

                                    <div class="delipress__select2-results" id="delipress__select2-results-<?php echo esc_html($keyTaxo); ?>"></div>

                                </div>
                                <div class="delipress__settings__item__help delipress__f5">
                                </div>
                            </div>

                            <script>
                                jQuery(document).ready(function(){
                                    var $        = jQuery
                                    var selected = [];
                                    var initials = [];

                                    <?php if(!empty($termSelected)): ?>
                                        <?php foreach($termSelected as $key => $term): ?>
                                            initials.push({
                                                id: <?php echo (int) $term->term_id; ?>,
                                                text: "<?php echo esc_js($term->name) ?>",
                                                selected: true
                                            });
                                            selected.push(<?php echo (int) $term->term_id; ?>)
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    $("#display_choice_<?php echo esc_js($keyTaxo); ?>").select2({
                                        placeholder: {
                                            id: '-1',
                                            text: "<?php echo esc_js(sprintf(__("Select %s", "delipress"), $taxo->label ) ); ?>"
                                        },
                                        escapeMarkup: function(m) {
                                            return m
                                        },
                                        data: initials,
                                        ajax: {
                                            url: ajaxurl,
                                            method: "POST",
                                            dataType: 'json',
                                            delay: 250,
                                            data: function (params) {
                                                return {
                                                    name__like: params.term,
                                                    action: "delipress_get_terms",
                                                    _wpnonce_ajax: "<?php echo esc_js($nonceAjax);  ?>",
                                                    taxonomy: "<?php echo esc_js($keyTaxo); ?>",
                                                    offset: params.page
                                                }
                                            },
                                            processResults: function (data, params) {
                                                params.page = params.page || 1

                                                let resultsForSelect2 = $.map(data.data.results, function (obj) {
                                                    obj.id   = obj.term_id;
                                                    obj.text = obj.name;

                                                    return obj;
                                                });

                                                return {
                                                    results: resultsForSelect2,
                                                    pagination: {
                                                        more: (params.page * 20) < data.data.total_count
                                                    }
                                                }
                                            },
                                            cache: true
                                        },
                                        dropdownParent: $('#delipress__select2-results-<?php echo esc_js($keyTaxo); ?>'),
                                        minimumInputLength: 1,
                                        templateResult: function(post){
                                            return delipressTemplateHtmlSelect('<?php echo esc_js($keyTaxo); ?>', post.text)
                                        },
                                        templateSelection: function(post){
                                            return delipressTemplateHtmlSelect('<?php echo esc_js($keyTaxo); ?>', post.text)
                                        }
                                    })
                                })
                            </script>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>


<script type="text/javascript">
    function delipressTemplateHtmlSelect(postType, title){

        let icon = jQuery('<span class="dashicons dashicons-admin-post"></span>')
        switch(postType){
            case "page":
                icon = jQuery('<span class="dashicons dashicons-admin-page"></span>')
                break;
        }


        return jQuery('<div class="delipress__select2-posttype" />').append(icon).append(jQuery('<span/>').html(title));
    }

    jQuery(document).ready(function(){
        var $ = jQuery

        var oldChecked = []

        <?php if(!$checkedEverything): ?>
            $('.delipress__settings__switch-everything').addClass('delipress__is-visible')
            if(oldChecked.length > 0){
                oldChecked.each(function(ind, el) {
                    $(this).prop('checked', true)
                })
            }
        <?php else: ?>
            oldChecked = $('.delipress__settings__switch-everything input[type=checkbox]:checked')
            $('.delipress__settings__switch-everything input[type=checkbox]').prop('checked', false)
            $('.delipress__settings__switch-everything').removeClass('delipress__is-visible')
        <?php endif; ?>

        // Everything switch

        $('#display_all_everything').on('change', function(e){
            var isEverythingChecked = $(this).prop('checked')
            if(!isEverythingChecked){
                $('.delipress__settings__switch-everything').addClass('delipress__is-visible')
                if(oldChecked.length > 0){
                    oldChecked.each(function(ind, el) {
                        $(this).prop('checked', true)
                    })
                }
            }else{
                oldChecked = $('.delipress__settings__switch-everything input[type=checkbox]:checked')
                $('.delipress__settings__switch-everything input[type=checkbox]').prop('checked', false)
                $('.delipress__settings__switch-everything').removeClass('delipress__is-visible')
            }
        })

        function checkDataSwitch(e){
            var isChecked = $(this).prop('checked')
            var $elToSwitch = $('#'+$(this).data('switch'))

            if (isChecked) {
                $elToSwitch.addClass('delipress__is-disabled')
            }else{
                $elToSwitch.removeClass('delipress__is-disabled')
            }
        }

        $('input[data-switch]').each(checkDataSwitch)

        // Other switch
        $('input[data-switch]').on('change', checkDataSwitch)

    })
</script>
