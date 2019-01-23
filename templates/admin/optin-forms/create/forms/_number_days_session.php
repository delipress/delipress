<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\AdminFormValues;
$behaviors         = $this->optin->getBehavior();

$numberDaysSession = AdminFormValues::displayOldValues(
    "number_days_session", 
    (isset($behaviors["number_days_session"])) ? $behaviors["number_days_session"] : 1
);

$rangeSession = AdminFormValues::displayOldValues(
    "range_session", 
    (isset($behaviors["range_session"])) ? $behaviors["range_session"] : "0"
);

$optionsRange = array(
    "0"     =>  esc_html__('Always show', 'delipress'),
    "hour"  =>  esc_html__('hour(s)',  'delipress'),
    "day"   =>  esc_html__('day(s)',   'delipress'),
    "week"  =>  esc_html__('week(s)',  'delipress'),
    "month" =>  esc_html__('month(s)', 'delipress'),
);

?>
<h2><?php esc_html_e('Visibility', 'delipress') ?></h2>
<div class="delipress__settings__item delipress__flex">
    <div class="delipress__settings__item__label delipress__f2">
        <label for="number_days_session"><?php esc_html_e("Display once every", "delipress"); ?></label>
    </div>
    <div class="delipress__settings__item__field delipress__f4">
        <div id="number_days_session-wrap" class="delipress__numberinput-select">
            <input
                id="number_days_session"
                name="number_days_session"
                type="number"
                class="delipress__input"
                min="1"
                value="<?php echo esc_attr($numberDaysSession); ?>"
            />
            <select 
                class="delipress__numberinput__suffix-select" 
                id="number_days_duration" 
                name="range_session"
            >  
                <?php foreach($optionsRange as $key => $option): ?>
                    <option 
                        value="<?php echo $key ?>"
                        <?php if($key === $rangeSession): ?>selected="selected"<?php endif; ?>
                    ><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select>
            <!-- <span class="delipress__numberinput__suffix">days</span> -->
        </div>
    </div>
    <div class="delipress__settings__item__help delipress__f5">
    </div>
</div>

<script>

    jQuery('document').ready(function(){
        var $ = jQuery

        function stateNumberDays(e){
            if($(this).val() == 0){
                $('#number_days_session').prop('disabled', true)
            }else{
                $('#number_days_session').prop('disabled', false)
            }
        }


        var daysSession = $('#number_days_session')
        if($("#number_days_duration").val() == 0){
            daysSession.prop('disabled', true)
        }else{
            daysSession.prop('disabled', false)
        }
        

        $('#number_days_duration').on('change', stateNumberDays)
    })
</script>
