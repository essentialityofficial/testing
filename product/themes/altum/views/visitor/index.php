<?php defined('ALTUMCODE') || die() ?>

<section class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('visitors') ?>"><?= l('visitors.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('visitor.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="text-center mb-4">
                <?php

                $icon = new \Jdenticon\Identicon([
                    'value' => $data->visitor->visitor_uuid,
                    'size' => 75
                ]);

                ?>

                <img src="<?= $icon->getImageDataUri() ?>" class="visitor-big-avatar rounded-circle" alt="" />
            </div>

            <div class="card border-0">
                <div class="card-body">

                    <div class="row">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.custom_parameters') ?></span>
                        </div>

                        <div class="col-7 col-lg-12">
                            <?php $data->visitor->custom_parameters = json_decode($data->visitor->custom_parameters, true); ?>

                            <?php if($data->visitor->custom_parameters && count($data->visitor->custom_parameters)): ?>

                                <div class="row">
                                    <?php foreach($data->visitor->custom_parameters as $key => $value): ?>
                                        <div class="col-4 text-muted font-weight-bold"><?= $key ?></div>
                                        <div class="col-8 text-left"><?= $value ?></div>
                                    <?php endforeach ?>
                                </div>

                            <?php else: ?>
                                -
                            <?php endif ?>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.total_sessions') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= nr($data->visitor->total_sessions) ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.date') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><span data-toggle="tooltip" title="<?= \Altum\Date::get($data->visitor->date, 1) ?>"><?= \Altum\Date::get($data->visitor->date, 2) ?></span></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.last_date') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><span data-toggle="tooltip" title="<?= \Altum\Date::get($data->visitor->last_date, 1) ?>"><?= \Altum\Date::get($data->visitor->last_date, 2) ?></span></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('global.country') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div>
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($data->visitor->country_code ? mb_strtolower($data->visitor->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                <span class="align-middle"><?= $data->visitor->country_code ? get_country_from_country_code($data->visitor->country_code) :  l('global.unknown') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('global.city') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div>
                                <div><?= $data->visitor->city_name ? $data->visitor->city_name : l('global.unknown') ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.device_type') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><i class="fas fa-fw fa-<?= $data->visitor->device_type ?> fa-sm mr-1"></i> <?= l('global.device.' . $data->visitor->device_type) ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.operating_system') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= $data->visitor->os_name . ' ' . $data->visitor->os_version ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.browser') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= $data->visitor->browser_name . ' ' . $data->visitor->browser_version ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.browser_language') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= get_language_from_locale($data->visitor->browser_language) ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.screen_resolution') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= $data->visitor->screen_resolution ?></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5 col-lg-12">
                            <span class="text-muted"><?= l('visitor.visitor.average_time_per_session') ?></span>
                        </div>
                        <div class="col-7 col-lg-12">
                            <div><?= \Altum\Date::get_seconds_to_his($data->average_time_per_session) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <div class="d-flex flex-column flex-lg-row justify-content-between mt-4 mt-lg-0 mb-4">
                <h1 class="h3"><?= l('analytics.sessions') ?></h1>

                <div class="d-flex align-items-center">
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

                    <div class="ml-3">
                        <?= include_view(THEME_PATH . 'views/visitors/visitor_dropdown_button.php', ['id' => $data->visitor->visitor_id]) ?>
                    </div>
                </div>
            </div>

            <?php if(!$data->sessions_result->num_rows): ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'visitor.basic',
                    'has_secondary_text' => false,
                ]); ?>

            <?php else: ?>

                <?php while($row = $data->sessions_result->fetch_object()): ?>

                    <div class="card border-0 mb-3">
                        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">

                            <div class="d-flex flex-column mb-2 mb-md-0">
                                <span><?= \Altum\Date::get($row->date, 2) ?></span>
                                    <span class="text-muted">
                                    <?= \Altum\Date::get($row->date, 3) ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i> <?= \Altum\Date::get($row->last_date, 3) ?>
                                </span>
                            </div>

                            <a href="#" class="mb-2 mb-md-0 badge badge-primary" data-toggle="modal" data-target="#session_events_modal" data-session-id="<?= $row->session_id ?>">
                                <i class="fas fa-fw fa-eye fa-sm"></i> <?= sprintf(l('visitor.basic.pageviews'), '<strong>' . nr($row->pageviews) . '</strong>') ?>
                            </a>

                            <?php if($row->sessions_replays_session_id): ?>
                                <a class="mb-2 mb-md-0 badge badge-light" href="<?= url('replay/' . $row->sessions_replays_session_id) ?>">
                                    <i class="fas fa-fw fa-play-circle fa-sm"></i> <?= l('visitor.basic.replays') ?>
                                </a>
                            <?php endif ?>

                            <div class="d-flex flex-column text-muted mb-2 mb-md-0">
                                <?= sprintf(l('visitor.basic.time_spent'), (new \DateTime($row->last_date))->diff((new \DateTime($row->date)))->format('%H:%I:%S')) ?>
                            </div>

                        </div>
                    </div>

                <?php endwhile ?>

            <?php endif ?>

        </div>
    </div>

</section>


<input type="hidden" name="start_date" value="<?= \Altum\Date::get($data->datetime['start_date'], 1) ?>" />
<input type="hidden" name="end_date" value="<?= \Altum\Date::get($data->datetime['end_date'], 1) ?>" />
<input type="hidden" name="visitor_id" value="<?= $data->visitor->visitor_id ?>" />

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
        redirect(`<?= url('visitor/' . $data->visitor->visitor_id) ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
