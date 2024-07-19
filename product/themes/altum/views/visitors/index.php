<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-users mr-1"></i> <?= l('visitors.header') ?></h1>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <button
                        id="daterangepicker"
                        type="button"
                        class="btn btn-sm btn-light"
                        data-min-date="<?= \Altum\Date::get($this->website->datetime, 4) ?>"
                        data-max-date="<?= \Altum\Date::get('', 4) ?>"
                >
                    <i class="fas fa-fw fa-calendar mr-lg-1"></i>
                    <span class="d-none d-lg-inline-block">
                        <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                            <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) ?>
                        <?php else: ?>
                            <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->datetime['end_date'], 2, \Altum\Date::$default_timezone) ?>
                        <?php endif ?>
                    </span>
                    <i class="fas fa-fw fa-caret-down d-none d-lg-inline-block ml-lg-1"></i>
                </button>
            </div>

            <div class="ml-3">
                <button type="button" class="btn btn-sm btn-light d-print-none" onclick="$('#filters').toggle();" data-toggle="tooltip" title="<?= l('analytics.filters.toggle') ?>">
                    <i class="fas fa-fw fa-filter"></i>
                </button>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group btn-group-sm d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('analytics.visitors') ?></small>
                            <span class="h4 font-weight-bolder"><?= nr($data->total_rows) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fas fa-fw fa-lg fa-users"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('visitors.average_time_per_session') ?></small>
                            <span class="h4 font-weight-bolder"><?= \Altum\Date::get_seconds_to_his($data->average_time_per_session) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fas fa-fw fa-lg fa-stopwatch"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('visitors.average_sessions_per_visitor') ?></small>
                            <span class="h4 font-weight-bolder"><?= nr($data->average_sessions_per_visitor) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                <i class="fas fa-fw fa-lg fa-hourglass-half"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= (new \Altum\View('partials/analytics/filters_wrapper', (array) $this))->run(['available_filters' => 'websites_visitors']) ?>

    <?php if(!$data->total_rows): ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'global',
            'has_secondary_text' => false,
        ]); ?>

    <?php else: ?>

        <form id="table" action="<?= SITE_URL . 'visitors/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                                <label class="custom-control-label" for="bulk_select_all"></label>
                            </div>
                        </th>

                        <th><?= l('visitors.visitor.visitor') ?></th>
                        <th><?= l('visitors.visitor.system') ?></th>
                        <th></th>
                        <th><?= l('visitors.visitor.last_date') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data->visitors as $row): ?>
                        <?php
                        /* Visitor */
                        $icon = new \Jdenticon\Identicon([
                            'value' => $row->visitor_uuid,
                            'size' => 50
                        ]);
                        $row->icon = $icon->getImageDataUri();
                        ?>

                        <tr data-visitor-id="<?= $row->visitor_id ?>">
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_visitor_id_<?= $row->visitor_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->visitor_id ?>" />
                                    <label class="custom-control-label" for="selected_visitor_id_<?= $row->visitor_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <?php if(($row->custom_parameters = json_decode($row->custom_parameters ?? '', true)) && count($row->custom_parameters)): ?>
                                        <?php ob_start() ?>
                                        <div class='d-flex flex-column p-3 text-left'>
                                            <div class='d-flex flex-column my-1'>
                                                <strong><?= sprintf(l('visitors.visitor.custom_parameters'), count($row->custom_parameters)) ?></strong>
                                            </div>

                                            <?php foreach($row->custom_parameters as $key => $value): ?>
                                            <div class='d-flex flex-column my-1'>
                                                <div><?= $key ?></div>
                                                <strong><?= $value ?></strong>
                                            </div>
                                            <?php endforeach ?>
                                        </div>

                                        <?php $tooltip = ob_get_clean() ?>

                                        <a href="<?= url('visitor/' . $row->visitor_id) ?>" class="mr-3" data-toggle="tooltip" data-html="true" title="<?= $tooltip ?>">
                                            <span>
                                                <i class="fas fa-fw fa-2x fa-fingerprint text-primary"></i>
                                            </span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('visitor/' . $row->visitor_id) ?>" class="mr-3">
                                            <img src="<?= $row->icon ?>" class="visitor-avatar rounded-circle" alt="" />
                                        </a>
                                    <?php endif ?>

                                    <div class="d-flex flex-column">
                                        <div>
                                            <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($row->country_code ? mb_strtolower($row->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />

                                            <span class="align-middle"><?= $row->country_code ? get_country_from_country_code($row->country_code) : l('global.unknown') ?></span>
                                        </div>
                                        <small class="text-muted"><?= l('visitors.visitor.since') ?> <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->date, 2) ?></span></small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div>
                                    <span class="mr-1" data-toggle="tooltip" title="<?= l('global.device.' . $row->device_type) ?>"><i class="fas fa-fw fa-sm fa-<?= $row->device_type ?>"></i></span>
                                    <span><?= $row->os_name ?></span>
                                </div>

                                <small class="text-muted"><?= $row->browser_name . ' - ' . $row->screen_resolution ?></small>
                            </td>

                            <td class="text-nowrap">
                                <a href="<?= url('visitor/' . $row->visitor_id) ?>" class="badge badge-primary">
                                    <i class="fas fa-fw fa-hourglass-half fa-sm mr-1"></i> <?= sprintf(l('visitors.visitor.total_sessions'), '<strong>' . nr($row->total_sessions) . '</strong>') ?>
                                </a>
                            </td>

                            <td class="text-nowrap">
                                <span data-toggle="tooltip" title="<?= \Altum\Date::get($row->last_date, 1) ?>" class="text-muted"><?= \Altum\Date::get($row->last_date, 2) ?></span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/visitors/visitor_dropdown_button.php', ['id' => $row->visitor_id]) ?>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php endif ?>

</div>

<input type="hidden" name="start_date" value="<?= \Altum\Date::get($data->datetime['start_date'], 1) ?>" />
<input type="hidden" name="end_date" value="<?= \Altum\Date::get($data->datetime['end_date'], 1) ?>" />
<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->datetime['start_date']) ?>,
        endDate: <?= json_encode($data->datetime['end_date']) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode(l('global.date.today')) ?>: [moment(), moment()],
            <?= json_encode(l('global.date.yesterday')) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode(l('global.date.last_7_days')) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode(l('global.date.last_30_days')) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode(l('global.date.this_month')) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode(l('global.date.last_month')) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            <?= json_encode(l('global.date.all_time')) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`<?= url('visitors') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
