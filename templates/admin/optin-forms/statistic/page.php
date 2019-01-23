<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptinHelper;

$licenseStatusValid  = $this->optionServices->isValidLicense();

if(!$licenseStatusValid){
    wp_redirect($this->wizardServices->getPageSetupUrl());
    exit;
}

$args =  array(
    "meta"      => OptinHelper::COUNTER_VIEW,
    "last_days" => apply_filters(DELIPRESS_SLUG . "_get_stats_timeseries_last_days", 29),
    "range"     => "day"
);


$labels  = $this->optinStatsServices->getLabelTimeseries($args);
$views  = $this->optinStatsServices->getStatsTimeseries(
    $this->optin->getId(),
    $args
);

$args["meta"] = OptinHelper::COUNTER_CONVERT;
$converts        = $this->optinStatsServices->getStatsTimeseries(
    $this->optin->getId(),
    $args
);

?>

<h1>
    <?php


    echo sprintf(
        __('Reports for Opt-In form: <small>%s</small>', 'delipress'),
        $this->optin->getTitle()
    )
    ?>
</h1>

<div class="delipress__report">
    <h2><?php esc_html_e('Overview', 'delipress') ?></h2>
    <div class="delipress__stats">
        <div class="delipress__stats__item">
            <strong><?php echo $this->optin->getCounterView(); ?></strong>
            <span><?php esc_html_e('Views', 'delipress'); ?></span>
        </div>
        <div class="delipress__stats__item">
            <strong><?php echo $this->optin->getCounterConvert(); ?></strong>
            <span><?php esc_html_e('Visitors converted', 'delipress'); ?></span>
        </div>
    </div>
    <div class="delipress__flex">
        <div class="delipress__f1">
            <div class="delipress__stats__bar">
                <div class="delipress__stats__bar-legend">
                    <span><?php esc_html_e('Convertion rate', 'delipress') ?></span>
                    <strong><?php echo $this->optin->getRateCounterView(); ?>%</strong>
                </div>
                <div class="delipress__stats__bar-wrap">
                    <div class="delipress__stats__bar-fill" style="width: <?php echo $this->optin->getRateCounterView(); ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="delipress__report__chart">
    <h2><?php esc_html_e('Performance', 'delipress') ?></h2>
    <div class="delipress__chart">
        <canvas id="delipress-chart"></canvas>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function(){
        var ctx = document.getElementById("delipress-chart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo delipress_js_array($labels) ?>,
                datasets: [
                    {
                        label: 'Views',
                        data: <?php echo delipress_js_array($views) ?>,
                        backgroundColor: "#44C2F3",
                        borderColor: "#44C2F3",
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: 'Convert',
                        data: <?php echo delipress_js_array($converts) ?>,
                        backgroundColor: "#165A82",
                        borderColor: "#165A82",
                        fill: false,
                        lineTension: 0
                    }
                ]
            },
            options: {
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                legend:{
                    labels: {
                        defaultFontSize: 20
                    }
                }
            }
        });
    })
</script>
