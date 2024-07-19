<?php defined('ALTUMCODE') || die() ?>

<div class="container">

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-pager mr-1"></i> <?= l('websites.header') ?></h1>

            <div class="ml-2">
                    <span data-toggle="tooltip" title="<?= l('websites.subheader') ?>">
                        <i class="fas fa-fw fa-info-circle text-muted"></i>
                    </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(!$this->team): ?>
                    <?php if($this->user->plan_settings->websites_limit != -1 && count($this->websites) >= $this->user->plan_settings->websites_limit): ?>
                        <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit')  ?>">
                            <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('websites.create') ?>
                        </button>
                    <?php else: ?>
                        <button type="button" data-toggle="modal" data-target="#website_create_modal" class="btn btn-primary" data-tooltip data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info(count($this->websites), $this->user->plan_settings->websites_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                            <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('websites.create') ?>
                        </button>
                    <?php endif ?>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->domains) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('domains?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('domains?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" onclick="window.print();return false;" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->websites) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="host" <?= $data->filters->search_by == 'host' ? 'selected="selected"' : null ?>><?= l('websites.input.host') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_enabled" class="small"><?= l('global.status') ?></label>
                                <select name="is_enabled" id="filters_is_enabled" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_tracking_type" class="small"><?= l('websites.tracking_type') ?></label>
                                <select name="tracking_type" id="filters_tracking_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="normal" <?= isset($data->filters->filters['tracking_type']) && $data->filters->filters['tracking_type'] == 'normal' ? 'selected="selected"' : null ?>><?= l('websites.tracking_type_normal') ?></option>
                                    <option value="lightweight" <?= isset($data->filters->filters['tracking_type']) && $data->filters->filters['tracking_type'] == 'lightweight' ? 'selected="selected"' : null ?>><?= l('websites.tracking_type_lightweight') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <div class="d-flex justify-content-between">
                                    <label for="filters_domain_id" class="small"><?= l('domains.domain_id') ?></label>
                                    <a href="<?= url('domain-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
                                </div>
                                <select name="domain_id" id="filters_domain_id" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <?php foreach($data->domains as $domain_id => $domain): ?>
                                        <option value="<?= $domain_id ?>" <?= isset($data->filters->filters['domain_id']) && $data->filters->filters['domain_id'] == $domain_id ? 'selected="selected"' : null ?>><?= $domain->host ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="host" <?= $data->filters->order_by == 'host' ? 'selected="selected"' : null ?>><?= l('websites.input.host') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-light" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(count($data->websites)): ?>
        <form id="table" action="<?= SITE_URL . 'websites/bulk' ?>" method="post" role="form">
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
                        <th><?= l('websites.website') ?></th>
                        <th><?= l('websites.usage') ?></th>
                        <th><?= l('websites.is_enabled') ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->websites as $row): ?>
                        <tr data-website-id="<?= $row->website_id ?>">
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_website_id_<?= $row->website_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->website_id ?>" />
                                    <label class="custom-control-label" for="selected_website_id_<?= $row->website_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#website_update_modal"
                                            data-website-id="<?= $row->website_id ?>"
                                            data-domain-id="<?= $row->domain_id ?>"
                                            data-name="<?= $row->name ?>"
                                            data-scheme="<?= $row->scheme ?>"
                                            data-host="<?= $row->host . $row->path ?>"
                                            data-tracking-type="<?= $row->tracking_type ?>"
                                            data-events-children-is-enabled="<?= (bool) $row->events_children_is_enabled ?>"
                                            data-sessions-replays-is-enabled="<?= (bool) $row->sessions_replays_is_enabled ?>"
                                            data-excluded-ips="<?= $row->excluded_ips ?>"
                                            data-email-reports-is-enabled="<?= $row->email_reports_is_enabled ?>"
                                            data-bot-exclusion-is-enabled="<?= (bool) $row->bot_exclusion_is_enabled ?>"
                                            data-query-parameters-tracking-is-enabled="<?= (bool) $row->query_parameters_tracking_is_enabled ?>"
                                            data-is-enabled="<?= (bool) $row->is_enabled ?>"
                                    >
                                        <?= $row->name ?>
                                    </a>

                                    <div class="d-flex align-items-center text-muted">
                                        <img src="<?= get_favicon_url_from_domain($row->host) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                        <?= $row->host . $row->path ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-nowrap text-muted">
                                <?php ob_start() ?>
                                <div class='d-flex flex-column p-3'>
                                    <div class='d-flex justify-content-between my-1'>
                                        <div class='mr-3'><?= l('websites.sessions_events') ?></div>
                                        <strong><?= nr($row->current_month_sessions_events) . '/' . ($this->user->plan_settings->sessions_events_limit === -1 ? '∞' : nr($this->user->plan_settings->sessions_events_limit, 1, true)) ?></strong>
                                    </div>

                                    <?php if($row->tracking_type == 'normal'): ?>
                                        <?php if($this->user->plan_settings->events_children_limit != 0): ?>
                                            <div class='d-flex justify-content-between my-1'>
                                                <div class='mr-3 text-truncate'><?= l('websites.events_children') ?></div>
                                                <div class='font-weight-bold text-truncate'><?= nr($row->current_month_events_children) . '/' . ($this->user->plan_settings->events_children_limit === -1 ? '∞' : nr($this->user->plan_settings->events_children_limit, 1, true)) ?></div>
                                            </div>
                                        <?php endif ?>

                                        <?php if(settings()->analytics->sessions_replays_is_enabled && $this->user->plan_settings->sessions_replays_limit != 0): ?>
                                            <div class='d-flex justify-content-between my-1'>
                                                <div class='mr-3 text-truncate'><?= l('websites.sessions_replays') ?></div>
                                                <div class='font-weight-bold text-truncate'><?= nr($row->current_month_sessions_replays) . '/' . ($this->user->plan_settings->sessions_replays_limit === -1 ? '∞' : nr($this->user->plan_settings->sessions_replays_limit, 1, true)) ?></div>
                                            </div>
                                        <?php endif ?>

                                        <?php if(settings()->analytics->websites_heatmaps_is_enabled && $this->user->plan_settings->websites_heatmaps_limit != 0): ?>
                                            <div class='d-flex justify-content-between my-1'>
                                                <div class='mr-3 text-truncate'><?= l('websites.websites_heatmaps') ?></div>
                                                <div class='font-weight-bold text-truncate'><?= nr($row->heatmaps) . '/' . ($this->user->plan_settings->websites_heatmaps_limit === -1 ? '∞' : nr($this->user->plan_settings->websites_heatmaps_limit, 1, true)) ?></div>
                                            </div>
                                        <?php endif ?>
                                    <?php endif ?>

                                    <?php if($this->user->plan_settings->websites_goals_limit != 0): ?>
                                        <div class='d-flex justify-content-between my-1'>
                                            <div class='mr-3 text-truncate'><?= l('websites.websites_goals') ?></div>
                                            <div class='font-weight-bold text-truncate'><?= nr($row->goals) . '/' . ($this->user->plan_settings->websites_goals_limit === -1 ? '∞' : nr($this->user->plan_settings->websites_goals_limit, 1, true)) ?></div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <?php $html = ob_get_clean() ?>

                                <a
                                        href="<?= url('account-plan') ?>"
                                        data-toggle="tooltip"
                                        data-html="true"
                                        title="<?= $html ?>"
                                        class="text-muted"
                                >
                                    <i class="fas fa-fw fa-lg fa-info-circle"></i>
                                </a>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <div>
                                        <?php if($row->is_enabled == 1): ?>
                                            <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                                        <?php elseif($row->is_enabled == 0): ?>
                                            <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                                        <?php endif ?>
                                    </div>

                                    <div>
                                        <?php if($row->tracking_type == 'normal'): ?>
                                            <span class="badge badge-light"><i class="fas fa-fw fa-sm fa-brain mr-1"></i> <?= l('websites.tracking_type_normal') ?></span>
                                        <?php endif ?>

                                        <?php if($row->tracking_type == 'lightweight'): ?>
                                            <span class="badge badge-light"><i class="fas fa-fw fa-sm fa-rocket mr-1"></i> <?= l('websites.tracking_type_lightweight') ?></span>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div>
                                    <?php if($row->tracking_type == 'normal'): ?>
                                        <?php if($this->user->plan_settings->events_children_limit != 0 && $row->events_children_is_enabled): ?>
                                            <span class="badge badge-success mx-1" data-toggle="tooltip" title="<?= l('websites.events_children') ?>"><i class="fas fa-fw fa-mouse"></i></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning mx-1" data-toggle="tooltip" title="<?= l('websites.events_children') ?>"><i class="fas fa-fw fa-mouse"></i></span>
                                        <?php endif ?>

                                        <?php if(settings()->analytics->sessions_replays_is_enabled): ?>
                                            <?php if($this->user->plan_settings->sessions_replays_limit != 0 && $row->sessions_replays_is_enabled): ?>
                                                <span class="badge badge-success mx-1" data-toggle="tooltip" title="<?= l('websites.sessions_replays') ?>"><i class="fas fa-fw fa-video"></i></span>
                                            <?php else: ?>
                                                <span class="badge badge-warning mx-1" data-toggle="tooltip" title="<?= l('websites.sessions_replays') ?>"><i class="fas fa-fw fa-video"></i></span>
                                            <?php endif ?>
                                        <?php endif ?>
                                    <?php endif ?>

                                    <?php if(settings()->analytics->email_reports_is_enabled): ?>
                                        <?php if($this->user->plan_settings->email_reports_is_enabled && $row->email_reports_is_enabled): ?>
                                            <span class="badge badge-success mx-1" data-toggle="tooltip" title="<?= l('websites.email_reports') ?>"><i class="fas fa-fw fa-envelope"></i></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning mx-1" data-toggle="tooltip" title="<?= l('websites.email_reports') ?>"><i class="fas fa-fw fa-envelope"></i></span>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div data-toggle="tooltip" title="<?= l('websites.pixel_key') ?>">
                                        <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-toggle="modal"
                                                data-target="#website_pixel_key_modal"
                                                data-tracking-type="<?= $row->tracking_type ?>"
                                                data-pixel-key="<?= $row->pixel_key ?>"
                                                data-url="<?= $row->scheme . $row->host . $row->path ?>"
                                                data-base-url="<?= $row->domain_id ? $data->domains[$row->domain_id]->scheme . $data->domains[$row->domain_id]->host . '/' : SITE_URL ?>"
                                        ><i class="fas fa-fw fa-sm fa-code"></i></button>
                                    </div>

                                    <?php if(!$this->team): ?>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                                <i class="fas fa-fw fa-ellipsis-v"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#website_update_modal"
                                                        data-website-id="<?= $row->website_id ?>"
                                                        data-domain-id="<?= $row->domain_id ?>"
                                                        data-name="<?= $row->name ?>"
                                                        data-scheme="<?= $row->scheme ?>"
                                                        data-host="<?= $row->host . $row->path ?>"
                                                        data-tracking-type="<?= $row->tracking_type ?>"
                                                        data-events-children-is-enabled="<?= (bool) $row->events_children_is_enabled ?>"
                                                        data-sessions-replays-is-enabled="<?= (bool) $row->sessions_replays_is_enabled ?>"
                                                        data-excluded-ips="<?= $row->excluded_ips ?>"
                                                        data-email-reports-is-enabled="<?= $row->email_reports_is_enabled ?>"
                                                        data-bot-exclusion-is-enabled="<?= (bool) $row->bot_exclusion_is_enabled ?>"
                                                        data-query-parameters-tracking-is-enabled="<?= (bool) $row->query_parameters_tracking_is_enabled ?>"
                                                        data-query-parameters-tracking-is-enabled="<?= (bool) $row->query_parameters_tracking_is_enabled ?>"
                                                        data-is-enabled="<?= (bool) $row->is_enabled ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= l('global.edit') ?>
                                                </a>
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#website_delete_modal"
                                                        data-website-id="<?= $row->website_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-trash-alt mr-1"></i> <?= l('global.delete') ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php else: ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'websites',
            'has_secondary_text' => true,
        ]); ?>

    <?php endif ?>

</div>

<?php \Altum\Event::add_content((new \Altum\View('websites/website_create_modal', (array) $this))->run($data), 'modals'); ?>
<?php \Altum\Event::add_content((new \Altum\View('websites/website_update_modal', (array) $this))->run($data), 'modals'); ?>
<?php \Altum\Event::add_content((new \Altum\View('websites/website_pixel_key_modal', (array) $this))->run($data), 'modals'); ?>
<?php \Altum\Event::add_content((new \Altum\View('websites/website_delete_modal', (array) $this))->run($data), 'modals'); ?>
<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
