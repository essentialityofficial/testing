<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="website_create_modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-circle-plus text-dark mr-2"></i>
                        <?= l('website_create_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="website_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="website_create_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                        <input id="website_create_name" type="text" class="form-control" name="name" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="website_create_host"><i class="fas fa-fw fa-sm fa-pager text-muted mr-1"></i> <?= l('websites.input.host') ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select name="scheme" class="appearance-none custom-select custom-select-lg form-control input-group-text">
                                    <option value="https://">https://</option>
                                    <option value="http://">http://</option>
                                </select>
                            </div>

                            <input id="website_create_host" type="text" class="form-control" name="host" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                        </div>
                        <small class="form-text text-muted"><?= l('websites.input.host_help') ?></small>
                    </div>

                    <?php if(count($data->domains) && settings()->analytics->domains_is_enabled): ?>
                        <div class="form-group">
                            <label for="website_create_domain_id"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('websites.input.domain_id') ?></label>
                            <select id="website_create_domain_id" name="domain_id" class="custom-select">
                                <option value=""><?= parse_url(SITE_URL, PHP_URL_HOST) ?></option>
                                <?php foreach($data->domains as $row): ?>
                                    <option value="<?= $row->domain_id ?>"><?= $row->host ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= l('websites.input.domain_id_help') ?></small>
                        </div>
                    <?php endif ?>

                    <div class="form-group">
                        <label for="website_create_tracking_type"><i class="fas fa-fw fa-sm fa-chart-bar text-muted mr-1"></i> <?= l('websites.tracking_type') ?></label>
                        <select id="website_create_tracking_type" name="tracking_type" class="custom-select form-control-lg">
                            <option value="lightweight"><?= l('websites.tracking_type_lightweight') ?></option>
                            <option value="normal"><?= l('websites.tracking_type_normal') ?></option>
                        </select>
                        <small data-tracking-type="lightweight" class="form-text text-muted d-none"><?= l('websites.tracking_type_lightweight_help') ?></small>
                        <small data-tracking-type="normal" class="form-text text-muted d-none"><?= l('websites.tracking_type_normal_help') ?></small>
                        <small class="form-text text-danger"><?= l('websites.tracking_type_help') ?></small>
                    </div>

                    <div data-tracking-type="normal" class="d-none">

                        <div <?= $this->user->plan_settings->events_children_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                            <div class="form-group custom-control custom-switch <?= $this->user->plan_settings->events_children_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="events_children_is_enabled"
                                        id="website_create_events_children_is_enabled"
                                        <?= $this->user->plan_settings->events_children_limit ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_events_children_is_enabled"><?= l('websites.input.events_children_is_enabled') ?></label>
                                <small class="form-text text-muted"><?= l('websites.input.events_children_is_enabled_help') ?></small>
                            </div>
                        </div>

                        <?php if(settings()->analytics->sessions_replays_is_enabled): ?>
                        <div <?= $this->user->plan_settings->sessions_replays_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                            <div class="form-group custom-control custom-switch <?= $this->user->plan_settings->sessions_replays_limit ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="sessions_replays_is_enabled"
                                        id="website_create_sessions_replays_is_enabled"
                                        <?= $this->user->plan_settings->sessions_replays_limit ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_sessions_replays_is_enabled"><?= l('websites.input.sessions_replays_is_enabled') ?></label>
                                <small class="form-text text-muted"><?= l('websites.input.sessions_replays_is_enabled_help') ?></small>
                            </div>
                        </div>
                        <?php endif ?>
                    </div>

                    <?php if(settings()->analytics->email_reports_is_enabled): ?>
                    <div <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="form-group custom-control custom-switch <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'container-disabled' ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="email_reports_is_enabled"
                                        id="website_create_email_reports_is_enabled"
                                        <?= $this->user->plan_settings->email_reports_is_enabled ? null : 'disabled="disabled"' ?>
                                >
                                <label class="custom-control-label clickable" for="website_create_email_reports_is_enabled"><?= l('global.plan_settings.email_reports_is_enabled_' . settings()->analytics->email_reports_is_enabled) ?></label>
                                <small class="form-text text-muted"><?= l('websites.input.email_reports_is_enabled_help') ?></small>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Tracking type handler */
    let tracking_type_handler = () => {
        let tracking_type = document.querySelector('#website_create_modal select[name="tracking_type"]').value;

        switch(tracking_type) {
            case 'lightweight':

                document.querySelectorAll('#website_create_modal [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                document.querySelectorAll('#website_create_modal [data-tracking-type="normal"]').forEach(element => {
                    element.classList.add('d-none');
                });

                break;

            case 'normal':

                document.querySelectorAll('#website_create_modal [data-tracking-type="lightweight"]').forEach(element => {
                    element.classList.add('d-none');
                });

                document.querySelectorAll('#website_create_modal [data-tracking-type="normal"]').forEach(element => {
                    element.classList.remove('d-none');
                });

                break;
        }

    };

    document.querySelector('#website_create_modal select[name="tracking_type"]').addEventListener('change', tracking_type_handler);

    tracking_type_handler();


    $('form[name="website_create"]').on('submit', event => {
        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            url: `${url}websites-ajax`,
            data: $(event.currentTarget).serialize(),
            dataType: 'json',
            success: (data) => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {

                        /* Hide modal */
                        $('#website_create_modal').modal('hide');

                        /* Clear input values */
                        $('form[name="website_create"] input').val('');

                        /* Refresh */
                        redirect('websites');

                        /* Remove the notification */
                        notification_container.innerHTML = '';

                    }, 1000);

                }
            },
            error: () => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
