<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-pager text-primary-900 mr-2"></i> <?= l('admin_websites.header') ?></h1>

    <div class="d-flex position-relative d-print-none">
        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn btn-gray-300 dropdown-toggle-simple <?= count($data->websites) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/websites?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/websites?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
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
                <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-secondary' : 'btn-gray-300' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
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
            <button id="bulk_enable" type="button" class="btn btn-gray-300" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

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

<form id="table" action="<?= SITE_URL . 'admin/websites/bulk' ?>" method="post" role="form">
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
                <th><?= l('global.user') ?></th>
                <th><?= l('websites.website') ?></th>
                <th><?= l('websites.usage') ?></th>
                <th><?= l('global.status') ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->websites as $row): ?>
                <?php //ALTUMCODE:DEMO if(DEMO) {$row->user_email = 'hidden@demo.com'; $row->user_name = $row->name = $row->host = 'hidden on demo';} ?>
                <tr>
                    <td data-bulk-table class="d-none">
                        <div class="custom-control custom-checkbox">
                            <input id="selected_website_id_<?= $row->website_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->website_id ?>" />
                            <label class="custom-control-label" for="selected_website_id_<?= $row->website_id ?>"></label>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <div class="d-flex">
                            <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                                <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />
                            </a>

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                </div>

                                <span class="text-muted"><?= $row->user_email ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <span><?= $row->name ?></span>
                            <div class="d-flex align-items-center text-muted">
                                <img src="<?= get_favicon_url_from_domain($row->host) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                <?= $row->host . $row->path ?>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <small>
                            <div class="text-muted">
                                <?= l('websites.sessions_events') ?>
                                <strong><?= nr($row->current_month_sessions_events) ?></strong>
                            </div>

                            <?php if($row->tracking_type == 'normal'): ?>
                                <div class="text-muted">
                                    <?= l('websites.events_children') ?>
                                    <strong><?= nr($row->current_month_events_children) ?></strong>
                                </div>

                                <div class="text-muted">
                                    <?= l('websites.sessions_replays') ?>
                                    <strong><?= nr($row->current_month_sessions_replays) ?></strong>
                                </div>
                            <?php endif ?>
                        </small>
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
                        <a href="<?= url('admin/heatmaps?website_id=' . $row->website_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= l('admin_heatmaps.menu') ?>">
                            <i class="fas fa-fw fa-fire text-muted"></i>
                        </a>
                        <a href="<?= url('admin/replays?website_id=' . $row->website_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= l('admin_replays.menu') ?>">
                            <i class="fas fa-fw fa-video text-muted"></i>
                        </a>
                    </td>

                    <td class="text-nowrap">
                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>') ?>">
                            <i class="fas fa-fw fa-calendar text-muted"></i>
                        </span>

                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' : '-')) ?>">
                            <i class="fas fa-fw fa-history text-muted"></i>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/websites/admin_website_dropdown_button.php', ['id' => $row->website_id, 'resource_name' => $row->name]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</form>

<div class="mt-3"><?= $data->pagination ?></div>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'website',
    'resource_id' => 'website_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/websites/delete/'
]), 'modals'); ?>
