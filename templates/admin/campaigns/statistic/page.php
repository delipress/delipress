<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

$provider         = $this->optionServices->getProvider();

if(empty($provider["key"])){
    wp_redirect($this->optionServices->getPageUrl());
    exit;
}

$provideStatistic = $this->providerServices->getProviderStatistic($provider["key"]);

$stats            = $provideStatistic->getStatisticsGeneral($this->campaign->getCampaignProviderId());
?>

<h1>
    <?php


    echo sprintf(
        __('Reports for campaign: <small>%s</small>', 'delipress'),
        $this->campaign->getTitle()
    )
    ?>
</h1>

<div class="delipress__report">
    <h2><?php esc_html_e('Overview', 'delipress') ?></h2>
    <div class="delipress__flex">
        <div class="delipress__f1">
            <div class="delipress__stats__bar">
                <div class="delipress__stats__bar-legend">
                    <span><?php esc_html_e('Open rate', 'delipress') ?></span>
                    <strong><?php echo $stats["percent_opened"]; ?>%</strong>
                </div>
                <div class="delipress__stats__bar-wrap">
                    <div class="delipress__stats__bar-fill" style="width: <?php echo $stats["percent_opened"]; ?>%"></div>
                </div>
            </div>
            <div class="delipress__stats__list">
                <div class="delipress__stats__list-item">
                    <span><?php esc_html_e('Total opens (no unique)', 'delipress') ?></span>
                    <i></i>
                    <span>
                        <?php echo $stats["total_opened"]; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="delipress__f1">
            <div class="delipress__stats__bar">
                <div class="delipress__stats__bar-legend">
                    <span><?php esc_html_e('Click rate', 'delipress') ?></span>
                    <strong><?php echo $stats["percent_clicked"]; ?>%</strong>
                </div>
                <div class="delipress__stats__bar-wrap">
                    <div class="delipress__stats__bar-fill" style="width: <?php echo $stats["percent_clicked"]; ?>%"></div>
                </div>
            </div>
            <div class="delipress__stats__list">
                <div class="delipress__stats__list-item">
                    <span><?php esc_html_e('Total clicks (no unique)', 'delipress') ?></span>
                    <i></i>
                    <span>
                        <?php echo $stats["total_clicked"]; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="delipress__stats">
        <div class="delipress__stats__item">
            <strong><?php echo $stats["unique_opened"]; ?></strong>
            <span><?php esc_html_e('Opened', 'delipress'); ?></span>
        </div>
        <div class="delipress__stats__item">
            <strong><?php echo $stats["unique_clicked"]; ?></strong>
            <span><?php esc_html_e('Clicked', 'delipress'); ?></span>
        </div>
        <div class="delipress__stats__item">
            <strong><?php echo $stats["bounced"]; ?></strong>
            <span><?php esc_html_e('Bounced', 'delipress'); ?></span>
        </div>
        <div class="delipress__stats__item">
            <strong><?php echo $stats["unsubscribed"]; ?></strong>
            <span><?php esc_html_e('Unsubscribed', 'delipress'); ?></span>
        </div>
    </div>

    <p class="delipress__stats__summary">
        <?php

        $strEmail = esc_html(
            _n(
                "email",
                "emails",
                ((int) $stats["total_email_send"] === 0) ? 1 : $stats["total_email_send"],
                "delipress"
            )
        );

        echo sprintf(
            __("You sent <strong>%s %s</strong> for this campaign.", "delipress"),
            $stats["total_email_send"],
            $strEmail
        ); ?>
    </p>
</div>


<?php if(!empty($stats["chart"]) ): ?>


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
                labels: <?php echo delipress_js_array($stats["chart"]["labels"]) ?>,
                datasets: [
                    {
                        label: 'Opens',
                        data: <?php echo delipress_js_array($stats["chart"]["datas"]["chart_open"]) ?>,
                        backgroundColor: "#44C2F3",
                        borderColor: "#44C2F3",
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: 'Clicks',
                        data: <?php echo delipress_js_array($stats["chart"]["datas"]["chart_click"]) ?>,
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

<?php endif; ?>
