<?php defined('ALTUMCODE') || die() ?>

<div class="card border-0">
    <div class="card-body">
        <div class="chart-container">
            <canvas id="logs_chart"></canvas>
        </div>
    </div>
</div>

<div class="row mt-5">

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.paths.header') ?></h2>

                        <a href="<?= url('dashboard/paths') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-copy"></i>
                    </span>
                </div>

                <div class="mt-4" id="paths_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.referrers.header') ?></h2>

                        <a href="<?= url('dashboard/referrers') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-random"></i>
                    </span>
                </div>

                <div class="mt-4" id="referrers_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('global.countries') ?></h2>

                        <a href="<?= url('dashboard/countries') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-globe"></i>
                    </span>
                </div>

                <div class="mt-4" id="countries_result"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.operating_systems.header') ?></h2>

                        <a href="<?= url('dashboard/operating-systems') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-server"></i>
                    </span>
                </div>

                <div class="mt-4" id="operating_systems_result" data-limit="5"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.device_types.header') ?></h2>

                        <a href="<?= url('dashboard/device_types') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-laptop"></i>
                    </span>
                </div>

                <div class="mt-4" id="device_types_result" data-limit="5"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.browser_names.header') ?></h2>

                        <a href="<?= url('dashboard/browser-names') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-window-restore"></i>
                    </span>
                </div>

                <div class="mt-4" id="browser_names_result" data-limit="5"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.utms.header') ?></h2>

                        <a href="<?= url('dashboard/utms') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-link"></i>
                    </span>
                </div>

                <div class="mt-4" id="utms_source_result" data-limit="7"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.screen_resolutions.header') ?></h2>

                        <a href="<?= url('dashboard/screen-resolutions') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-desktop"></i>
                    </span>
                </div>

                <div class="mt-4" id="screen_resolutions_result" data-limit="7"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 col-xl-4 mb-4 mb-md-5">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h2 class="h5 m-0"><?= l('dashboard.browser_languages.header') ?></h2>

                        <a href="<?= url('dashboard/browser-languages') ?>" class="text-muted ml-3" data-toggle="tooltip" title="<?= l('global.view_more') ?>"><i class="align-self-end fas fa-arrows-alt-h text-gray"></i></a>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-language"></i>
                    </span>
                </div>

                <div class="mt-4" id="browser_languages_result" data-limit="7"></div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>

<?php if(count($data->logs)): ?>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>

<script>
    let css = window.getComputedStyle(document.body);
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Chart */
    let chart = document.getElementById('logs_chart').getContext('2d');

    /* Colors */
    color_gradient = chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.6));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.1));

    new Chart(chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [
                {
                    data: <?= $data->logs_chart['pageviews'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true,
                    label: <?= json_encode(l('dashboard.basic.chart.pageviews')) ?>
                },
                {
                    data: <?= $data->logs_chart['sessions'] ?? '[]' ?>,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'rgba(0,0,0,0)',
                    fill: false,
                    showLine: false,
                    borderWidth: 0,
                    pointBorderWidth: 0,
                    pointBorderRadius: 0,
                    label: <?= json_encode(l('dashboard.basic.chart.sessions')) ?>
                },
                {
                    data: <?= $data->logs_chart['visitors'] ?? '[]' ?>,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'rgba(0,0,0,0)',
                    fill: false,
                    showLine: false,
                    borderWidth: 0,
                    pointBorderWidth: 0,
                    pointBorderRadius: 0,
                    label: <?= json_encode(l('dashboard.basic.chart.visitors')) ?>
                }
            ]
        },
        options: chart_options
    });
</script>
<?php endif ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
