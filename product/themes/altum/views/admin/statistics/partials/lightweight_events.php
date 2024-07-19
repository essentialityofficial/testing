<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">

        <h2 class="h4"><i class="fas fa-fw fa-seedling fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.lightweight_events.header') ?></h2>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['lightweight_events']) ?></span> <?= l('admin_statistics.lightweight_events.chart') ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="lightweight_events"></canvas>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let lightweight_events_chart = document.getElementById('lightweight_events').getContext('2d');
    color_gradient = lightweight_events_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    new Chart(lightweight_events_chart, {
        type: 'line',
        data: {
            labels: <?= $data->lightweight_events_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.lightweight_events.chart')) ?>,
                    data: <?= $data->lightweight_events_chart['lightweight_events'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
