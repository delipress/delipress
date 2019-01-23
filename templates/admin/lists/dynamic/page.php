<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\Wordpress\Helpers\MarkupIncentiveHelper;

$response            = $this->metaServices->getMetasUsers();

$metas = $response["results"];

$specifications   = $this->specification->getConditionTest();
$locale           = get_locale();

$licenseStatusValid  = $this->optionServices->isValidLicense();

?>

<?php if (!$licenseStatusValid) : ?>
    <?php
        MarkupIncentiveHelper::printLineItem(
            array(
                "title" => __('Dynamic list feature', 'delipress'),
                "content" => __('Oops, This functionnality is only available to premium "business" user', 'delipress')
            )
        );
    ?>
<?php else : ?>
    <div class="delipress__dynamic-list">
        <h1><?php _e('Dynamic list', 'delipress'); ?><small><?php _e('Add a new dynamic list', 'delipress'); ?></small></h1>
        <p class="delipress__intro">
            <?php _e('You can think of a <strong>Dynamic list</strong> as a powerful way to query all your subscribers accross all your lists. The goal is to make it easy to send people content they care about. Simply choose your conditions and target your audience.', 'delipress'); ?>
        </p>
        <div class="delipress__settings">
            <h2><?php _e('Informations', 'delipress'); ?></h2>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f2">
                    <label for="save_dynamic_list_presset">
                        <?php _e('Name your list', 'delipress'); ?>
                    </label>
                </div>
                <div class="delipress__settings__item__field delipress__f3">
                    <input class="delipress__input" id="save_dynamic_list_presset" name="<?php echo TaxonomyHelper::LIST_NAME ?>" type="text" value="" />
                </div>
                <div class="delipress__settings__item__help delipress__f5">
                    <span class="delipress__mandatory"><?php _e('Required', 'delipress'); ?></span>
                    <?php _e('This name is also used within your email provider and could be seen by your subscribers', 'delipress'); ?>                            
                </div>
            </div>
            <h2><?php _e('Choose your conditions', 'delipress'); ?></h2>
            <div class="delipress__settings__item delipress__flex">
                <div class="delipress__settings__item__label delipress__f4">
                    <div id="js-delipress-number-users" class="delipress__dynamic-list__count">
                        <span id="js-delipress-number-users-count" class="delipress__dynamic-list__count__number">0</span> <span id="js-delipress-number-users-text"><?php _e('subscriber match these conditions', "delipress") ?></span>
                    </div>     
                    <a href="#" id="js-delipress-delipress-check-number-users" class="delipress__button delipress__button--soft">
                        <?php _e("Update subscriber count", "delipress"); ?>
                    </a>
                </div>
                <div class="delipress__settings__item__label delipress__f4"></div>
            </div>
            <div class="delipress__settings__item">
                <div id="js-delipress-container-specification">
                    <table data-or="0" class="js-delipress-or delipress__list-dynamic-table">
                        <tbody>
                            <tr data-and="0">
                                <td>
                                    <select name="specification[0][0][meta_id]">
                                        <?php foreach ($metas as $meta) : ?>
                                            <option value="<?php echo $meta["id"]; ?>">
                                                <?php echo $meta["name"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="specification[0][0][operator]" class="js-delipress-select-value" data-or="0" data-and="0">
                                        <?php foreach ($specifications as $key => $specification) : ?>
                                            <option value="<?php echo $key; ?>">
                                                <?php echo $specification; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input class="delipress__input js-delipress-input-value" type="text" name="specification[0][0][value]" data-or="0" data-and="0" />
                                </td>
                                <td class="delipress__list-dynamic__and-actions"> 
                                    <div data-or="0" class="js-delipress-add-and delipress__button delipress__button--second delipress__button--small">
                                        <?php _e('and', 'delipress'); ?>
                                    </div>
                                    <div data-or="0" data-and="0" class="js-delipress-remove-and">
                                        <span class="dashicons dashicons-minus"></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <strong><?php _e('or', 'delipress'); ?></strong>
                    <br />
                    <a href="#" class="js-delipress-add-or delipress__button delipress__button--soft delipress--small">
                        <?php _e("Add a condition", "delipress"); ?>
                    </a>
                </div>
            </div>

            <div class="delipress__settings__item delipress__dynamic-list__save">
                <button type="submit" class="delipress__button delipress__button--save">
                    <?php _e('Create list', 'delipress'); ?>
                </button>
                <p id="js-duration-create-list" style="display:none;">
                    <span class="dashicons dashicons-clock"></span>
                    <span id="js-txt"></span>
                </p>
            </div>
                

            <template id="template-or">
                <strong data-or="OR_VALUE"><?php _e('or', 'delipress'); ?></strong>
                <table data-or="OR_VALUE" class="js-delipress-or delipress__list-dynamic-table">
                    <tbody>

                    </tbody>
                </table>
            </template>

            <template id="template-and">
                <tr data-and="AND_VALUE">
                    <td>
                        <select name="specification[OR_VALUE][AND_VALUE][meta_id]">
                            <?php foreach ($metas as $meta) : ?>
                                <option value="<?php echo $meta["id"]; ?>">
                                    <?php echo $meta["name"]; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="specification[OR_VALUE][AND_VALUE][operator]" class="js-delipress-select-value" data-or="OR_VALUE" data-and="AND_VALUE">
                            <?php foreach ($specifications as $key => $specification) : ?>
                                <option value="<?php echo $key; ?>">
                                    <?php echo $specification; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input class="delipress__input js-delipress-input-value" type="text" name="specification[OR_VALUE][AND_VALUE][value]" data-or="OR_VALUE" data-and="AND_VALUE"/>
                    </td>
                    <td class="delipress__list-dynamic__and-actions"> 
                        <button data-or="OR_VALUE" class="js-delipress-add-and delipress__button delipress__button--second delipress__button--small">
                            <?php _e('and', 'delipress'); ?>
                        </button>
                        <button data-or="OR_VALUE" data-and="AND_VALUE" class="js-delipress-remove-and">
                            <span class="dashicons dashicons-minus"></span>
                        </button>
                    </td>
                </tr>
            </template>

        </div>
        

    </div>
    

    <script type="text/javascript">
        var $ = jQuery
        
        $(document).ready(function(){

            var flatPickerInput = []
            var $containerSpec  =  $("#js-delipress-container-specification")
            var $templateAnd    =  $("#template-and");
            var $templateOr     =  $("#template-or");

            <?php
            switch ($locale) {
                case "fr_FR":
                ?>
                    var optionsFlatPicker = {
                        dateFormat: "d-m-Y",
                        locale: "fr",
                        time_24hr: true,

                    }
                <?php
                    break;
                default:
                ?>
                    var optionsFlatPicker = {
                        dateFormat: "m-d-Y",
                    }
                <?php
                    break;
            }
            ?>

            $(document).on("change", ".js-delipress-select-value", function(e){
                e.preventDefault()
                var valueSelect = $(this).val()

                var orValue     = $(this).data("or");
                var andValue    = $(this).data("and");

                if(valueSelect.indexOf("date") < 0){

                    if(flatPickerInput[orValue] && flatPickerInput[orValue][andValue]){
                        flatPickerInput[orValue][andValue].destroy();
                        flatPickerInput[orValue].splice(andValue, 1)
                    }
                }
                else{

                    if(flatPickerInput[orValue] && flatPickerInput[orValue][andValue]){
                        return
                    }

                    $(".js-delipress-input-value[data-or='" + orValue + "'][data-and='" + andValue + "']").val("")
                    if(!flatPickerInput[orValue]){
                        flatPickerInput[orValue] = []
                    }

                    flatPickerInput[orValue][andValue] =  flatpickr(".js-delipress-input-value[data-or='" + orValue + "'][data-and='" + andValue + "']", optionsFlatPicker);
                }

            })

            // Add or
            $(document).on("click", ".js-delipress-add-or", function(e){
                e.preventDefault()
                var htmlOr     = $templateOr.clone().html()
                var orValue    = $(".js-delipress-or").length
                htmlOr         = htmlOr.replace(/OR_VALUE/g, orValue)
                
                $containerSpec.append(htmlOr)

                addAndValue(orValue, 0)
                
            })

            //Add html and value
            function addAndValue(orValue, andValue){
                var html        = $templateAnd.clone().html()

                html = html.replace(/OR_VALUE/g, orValue)
                        .replace(/AND_VALUE/g, andValue)
                
                $(".js-delipress-or[data-or=" + orValue + "] tbody").append(html)
            }

            // Remove and 
            $(document).on("click", ".js-delipress-remove-and", function(e){
                e.preventDefault();
                var orValue      = $(this).data("or")
                var andValue     = $(this).data("and")
                $(".js-delipress-or[data-or=" + orValue + "] tr[data-and=" + andValue + "]").remove()
                
                if(flatPickerInput[orValue] && flatPickerInput[orValue][andValue]){
                    flatPickerInput[orValue][andValue].destroy();
                    flatPickerInput[orValue].splice(andValue, 1)
                }

                if($(".js-delipress-or[data-or=" + orValue + "] tr").length === 0){
                    // Check and remove the or before
                    $(".js-delipress-or[data-or=" + orValue + "]").prev('strong[data-or="'+ orValue +'"]').remove()
                    $(".js-delipress-or[data-or=" + orValue + "]").remove()
                }
            })

            // Add and
            $(document).on("click", ".js-delipress-add-and", function(e){
                e.preventDefault();
                var orValue     = $(this).data("or")
                var newAndValue = $(".js-delipress-or[data-or=" + orValue + "] tr").length

                addAndValue(orValue, newAndValue)
            
            })

            var loadInProgress = false;

            $("#js-delipress-delipress-check-number-users").on("click", function(e){
                e.preventDefault();
                var $this = $(this);

                if(loadInProgress) {return false;}
                
                // Loader functionnality
                $this.css({
                    height: $this.outerHeight(),
                    opacity: .7
                })
                $this.append('<span class="dashicons dashicons-update dashicons--roll"></span>')
                loadInProgress = true

                $.ajax({
                    url: ajaxurl,
                    method: "POST",
                    dataType: "json",
                    data :{
                        action : "delipress_count_subscriber_dynamic_list",
                        specification : $("#form_page").serializeObject()
                    },
                    success: function(data){
                        if(!data.success){
                            return;
                        }

                        $("#js-delipress-number-users #js-delipress-number-users-count").text(
                            data.data.results.total
                        )

                        if(data.data.results.total > 1){
                            $("#js-delipress-number-users #js-delipress-number-users-text").text(
                                "<?php _e('subscribers match these conditions', "delipress") ?>"
                            )
                        }
                        else{
                            $("#js-delipress-number-users #js-delipress-number-users-text").text(
                                "<?php _e('subscriber match these conditions', "delipress") ?>"
                            )
                        }

                        if(data.data.results.duration_msg != null){
                            $("#js-duration-create-list #js-txt").text(
                                data.data.results.duration_msg
                            )
                            $("#js-duration-create-list").show();
                        }
                        else{
                            $("#js-duration-create-list").hide()
                        }

                        loadInProgress = false
                        $this.css({
                            opacity: 1
                        }).find('.dashicons').remove()
                    }
                })
            })
        })

        
    </script>
<?php endif; ?>
