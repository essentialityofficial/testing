<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-pager fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.websites.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['websites'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['websites'] > 0 ? '+' : null) . nr($data->total['websites']) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="websites"></canvas>
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
    let websites_chart = document.getElementById('websites').getContext('2d');
    color_gradient = websites_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    new Chart(websites_chart, {
        type: 'line',
        data: {
            labels: <?= $data->websites_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.websites.chart')) ?>,
                    data: <?= $data->websites_chart['websites'] ?? '[]' ?>,
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
