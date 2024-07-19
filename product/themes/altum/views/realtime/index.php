<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-clock mr-1"></i> <?= l('realtime.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('realtime.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
                    <div class="text-center">
                        <div id="realtime_visitors_result" class="h1"></div>

                        <?php if($this->website->tracking_type == 'normal'): ?>
                            <span class="text-muted"><?= l('realtime.visitors') ?></span>
                        <?php endif ?>

                        <?php if($this->website->tracking_type == 'lightweight'): ?>
                            <span class="text-muted"><?= l('realtime.pageviews') ?></span>
                        <?php endif ?>
                    </div>
                </div>

                <div class="col-12 col-md-8">
                    <div class="chart-container">
                        <canvas id="logs_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= l('global.countries') ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-sm fa-globe"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_countries_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= l('dashboard.device_types.header') ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-sm fa-laptop"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_device_types_result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 m-0"><?= l('dashboard.paths.header') ?></h2>
                        </div>
                        <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-sm fa-copy"></i>
                        </span>
                    </div>

                    <div class="mt-4" id="realtime_paths_result"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>

<script>
    let css = window.getComputedStyle(document.body);
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Chart */
    Chart.defaults.elements.line.borderWidth = 4;
    Chart.defaults.elements.point.radius = 3;
    let chart = document.getElementById('logs_chart').getContext('2d');

    /* Colors */
    color_gradient = chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.6));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.1));

    let pageviews_chart = new Chart(chart, {
        type: 'line',
        data: {
            labels: null,
            datasets: [{
                data: null,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true,
                label: <?= json_encode(l('dashboard.basic.chart.pageviews')) ?>
            }]
        },
        options: chart_options
    });

    /* Basic data to use for fetching extra data */
    let website_id = $('input[name="website_id"]').val();
    let tracking_type = <?= json_encode($this->website->tracking_type) ?>;
    let start_date = 'now';
    let end_date = 'now';
    let request_subtype = 'realtime';
    let dashboard_ajax_url = `${url}dashboard-ajax-${tracking_type}?website_id=${website_id}&request_subtype=${request_subtype}&start_date=${start_date}&end_date=${end_date}&global_token=${global_token}`;

    let load = () => {
        for (let request_type of ['realtime_visitors', 'realtime_paths', 'realtime_countries', 'realtime_device_types']) {

            if($(`#${request_type}_result`).length) {

                let limit = $(`#${request_type}_result`).data('limit') || 10;

                /* Put the loading placeholders */
                $(`#${request_type}_result`).html($('#loading').html());

                $.ajax({
                    type: 'GET',
                    url: `${dashboard_ajax_url}&request_type=${request_type}&limit=${limit}`,
                    success: (data) => {

                        $(`#${request_type}_result`).html(data.details.html);

                        if(request_type == 'realtime_visitors') {
                            let title = <?= json_encode(l('realtime.title') . ' - ' . \Altum\Title::$site_title) ?>;
                            let title_dynamic = <?= json_encode(l('realtime.title_dynamic')) ?>.replace('%s', data.details.html);

                            document.title = `${title_dynamic} - ${title}`;
                        }

                    },
                    dataType: 'json'
                });
            }
        }

        $.ajax({
            type: 'GET',
            url: `${dashboard_ajax_url}&request_type=realtime_chart_data&limit=10`,
            success: (data) => {

                let labels = JSON.parse(data.details.logs_chart_labels);
                let pageviews_dataset_data = JSON.parse(data.details.logs_chart_pageviews);

                pageviews_chart.data.labels = labels;
                pageviews_chart.data.datasets[0].data = pageviews_dataset_data;

                pageviews_chart.update();

            },
            dataType: 'json'
        });
    };

    load();

    setInterval(load, 10000);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
