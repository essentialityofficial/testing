<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('replays') ?>"><?= l('replays.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('replay.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-video mr-1"></i> <?= l('replay.header') ?></h1>

        <?php if(!$this->team): ?>
            <?= include_view(THEME_PATH . 'views/replays/replay_dropdown_button.php', ['id' => $data->replay->replay_id]) ?>
        <?php endif ?>
    </div>

    <?php
    /* Visitor */
    $icon = new \Jdenticon\Identicon([
        'value' => $data->visitor->visitor_uuid,
        'size' => 50
    ]);
    $data->visitor->icon = $icon->getImageDataUri();
    ?>

    <div class="mb-5 row justify-content-between">
        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column text-truncate">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replays.replay.visitor') ?></small>
                            <span class="h4 font-weight-bolder text-truncate">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . ($data->visitor->country_code ? mb_strtolower($data->visitor->country_code) : 'unknown') . '.svg' ?>" class="img-fluid icon-favicon mr-1" />

                                <span class="align-middle"><?= $data->visitor->country_code ? get_country_from_country_code($data->visitor->country_code) :  l('global.unknown') ?></span>
                            </span>
                        </div>

                        <?php if(($data->visitor->custom_parameters = json_decode($data->visitor->custom_parameters, true)) && count($data->visitor->custom_parameters)): ?>
                            <a href="<?= url('visitor/' . $data->visitor->visitor_id) ?>" class="mr-3" data-toggle="tooltip" title="<?= sprintf(l('visitors.visitor.custom_parameters'), count($data->visitor->custom_parameters)) ?>">
                                <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                                    <i class="fas fa-fw fa-lg fa-fingerprint"></i>
                                </span>
                            </a>
                        <?php else: ?>
                            <a href="<?= url('visitor/' . $data->visitor->visitor_id) ?>" class="mr-3">
                                <img src="<?= $data->visitor->icon ?>" class="visitor-avatar rounded-circle" alt="" />
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replays.replay.date') ?></small>
                            <span class="h4 font-weight-bolder" data-toggle="tooltip" title="<?= \Altum\Date::get($data->replay->datetime, 1) ?>"><?= \Altum\Date::get($data->replay->datetime, 2) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-lg fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replay.duration') ?></small>
                            <span class="h4 font-weight-bolder"><?= \Altum\Date::get_seconds_to_his((new \DateTime($data->replay->last_datetime))->getTimestamp() - (new \DateTime($data->replay->datetime))->getTimestamp()) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-lg fa-stopwatch"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replay.time_range') ?></small>
                            <span class="h4 font-weight-bolder"><?= \Altum\Date::get($data->replay->datetime, 3) ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i> <?= \Altum\Date::get($data->replay->last_datetime, 3) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-lg fa-clock"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replay.events') ?></small>
                            <span class="h4 font-weight-bolder"><a href="#" data-toggle="modal" data-target="#replay_events_modal"><?= nr($data->replay->events) ?></a></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-lg fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <small class="text-muted text-uppercase font-weight-bold"><?= l('replay.expiration_date') ?></small>
                            <span class="h4 font-weight-bolder"><?= \Altum\Date::get_time_until($data->replay->expiration_date) ?></span>
                        </div>

                        <span class="round-circle-md bg-gray-200 text-primary-700 p-3">
                            <i class="fas fa-fw fa-lg fa-hourglass-half"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="notification-container"></div>

    <div class="clearfix d-flex justify-content-center" id="replay"></div>
</div>


<?php ob_start() ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rrweb-player@2.0.0-alpha.11/dist/style.css" />
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="https://cdn.jsdelivr.net/npm/rrweb-player@2.0.0-alpha.11/dist/index.js"></script>

<script>
    /* Default loading state */
    let loading_html = document.querySelector('#loading').innerHTML;
    let notification_container = document.querySelector('.notification-container');
    document.querySelector('#replay').innerHTML = loading_html;

    let player = null;

    $.ajax({
        type: 'GET',
        url: <?= json_encode(url('replay/read/' . $data->visitor->session_id)) ?>,
        success: (result) => {

            document.querySelector('#replay').innerHTML = '';

            /* Start the replayer */
            player = new rrwebPlayer({
                target: document.querySelector('#replay'),
                data: {
                    events: result.rows,
                    autoPlay: false,
                },
            });

            /* Set the content for the replay events modal */
            $('#replay_events_result').html(result.replay_events_html);

        },
        error: (event) => {
            document.querySelector('#replay').innerHTML = '';
            display_notifications(<?= json_encode(l('replay.error_message')) ?>, 'error',  notification_container);
        },
        dataType: 'json'
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'replay',
    'resource_id' => 'session_id',
    'has_dynamic_resource_name' => false,
    'path' => 'replays/delete'
]), 'modals'); ?>
