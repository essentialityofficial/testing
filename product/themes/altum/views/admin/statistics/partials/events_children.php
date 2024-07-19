<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-bell fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.events_children.header') ?></h2>

            <div>
                <span data-toggle="tooltip" title="<?= l('admin_statistics.events_children.chart_click') ?>" class="badge <?= $data->total['click'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['click'] > 0 ? '+' : null) . nr($data->total['click']) ?></span>
                <span data-toggle="tooltip" title="<?= l('admin_statistics.events_children.chart_form') ?>" class="badge <?= $data->total['form'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['form'] > 0 ? '+' : null) . nr($data->total['form']) ?></span>
                <span data-toggle="tooltip" title="<?= l('admin_statistics.events_children.chart_scroll') ?>" class="badge <?= $data->total['scroll'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['scroll'] > 0 ? '+' : null) . nr($data->total['scroll']) ?></span>
                <span data-toggle="tooltip" title="<?= l('admin_statistics.events_children.chart_resize') ?>" class="badge <?= $data->total['resize'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['resize'] > 0 ? '+' : null) . nr($data->total['resize']) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="events_children"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let click_color = css.getPropertyValue('--teal');
    let form_color = css.getPropertyValue('--indigo');
    let scroll_color = css.getPropertyValue('--cyan');
    let resize_color = css.getPropertyValue('--blue');

    /* Display chart */
    new Chart(document.getElementById('events_children').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->events_children_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.events_children.chart_click')) ?>,
                    data: <?= $data->events_children_chart['click'] ?? '[]' ?>,
                    backgroundColor: click_color,
                    borderColor: click_color,
                    fill: false
                },
                {
                    label: <?= json_encode(l('admin_statistics.events_children.chart_form')) ?>,
                    data: <?= $data->events_children_chart['form'] ?? '[]' ?>,
                    backgroundColor: form_color,
                    borderColor: form_color,
                    fill: false
                },
                {
                    label: <?= json_encode(l('admin_statistics.events_children.chart_scroll')) ?>,
                    data: <?= $data->events_children_chart['scroll'] ?? '[]' ?>,
                    backgroundColor: scroll_color,
                    borderColor: scroll_color,
                    fill: false
                },
                {
                    label: <?= json_encode(l('admin_statistics.events_children.chart_resize')) ?>,
                    data: <?= $data->events_children_chart['resize'] ?? '[]' ?>,
                    backgroundColor: resize_color,
                    borderColor: resize_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
