<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-fire mr-1"></i> <?= l('heatmaps.header') ?></h1>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if(!$this->team): ?>
                    <?php if($this->user->plan_settings->websites_heatmaps_limit != -1 && $data->total_heatmaps >= $this->user->plan_settings->websites_heatmaps_limit): ?>
                        <button type="button" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>"  class="btn btn-primary disabled">
                            <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('heatmaps.create') ?>
                        </button>
                    <?php else: ?>
                        <button type="button" data-toggle="modal" data-target="#heatmap_create" class="btn btn-primary" data-tooltip data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_heatmaps, $this->user->plan_settings->websites_heatmaps_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                            <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('heatmaps.create') ?>
                        </button>
                    <?php endif ?>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->heatmaps) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('heatmaps?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('heatmaps?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
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
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->heatmaps) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
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
                                <label for="search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="search_by" class="custom-select custom-select-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="path" <?= $data->filters->search_by == 'path' ? 'selected="selected"' : null ?>><?= l('heatmap_create_modal.input.path') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="is_enabled" class="small"><?= l('global.status') ?></label>
                                <select name="is_enabled" id="is_enabled" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="last_datetime" <?= $data->filters->search_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="path" <?= $data->filters->order_by == 'path' ? 'selected="selected"' : null ?>><?= l('heatmap_create_modal.input.path') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="results_per_page" class="custom-select custom-select-sm">
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

    <?php if(!$data->total_heatmaps): ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'heatmaps',
            'has_secondary_text' => true,
        ]); ?>

    <?php else: ?>

        <form id="table" action="<?= SITE_URL . 'heatmaps/bulk' ?>" method="post" role="form">
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
                        <th><?= l('heatmaps.heatmap') ?></th>
                        <th></th>
                        <th><?= l('global.status') ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data->heatmaps as $row): ?>
                        <tr data-heatmap-id="<?= $row->heatmap_id ?>">
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_heatmap_id_<?= $row->heatmap_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->heatmap_id ?>" />
                                    <label class="custom-control-label" for="selected_heatmap_id_<?= $row->heatmap_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <span><a href="<?= url('heatmap/' . $row->heatmap_id) ?>"><?= $row->name ?></a></span>
                                    <small class="text-muted"><?= $this->website->host . $this->website->path . $row->path ?></small>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex">
                                    <a href="<?= url('heatmap/' . $row->heatmap_id . '/desktop') ?>" class="mr-2 <?= ($row->snapshot_id_desktop ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_desktop ? l('heatmaps.snapshot_id_desktop') : l('heatmaps.snapshot_id_desktop_null')) ?>"><i class="fas fa-fw fa-lg fa-desktop"></i></a>
                                    <a href="<?= url('heatmap/' . $row->heatmap_id . '/tablet') ?>" class="mr-2 <?= ($row->snapshot_id_tablet ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_tablet ? l('heatmaps.snapshot_id_tablet') : l('heatmaps.snapshot_id_tablet_null')) ?>"><i class="fas fa-fw fa-lg fa-tablet"></i></a>
                                    <a href="<?= url('heatmap/' . $row->heatmap_id . '/mobile') ?>" class="mr-2 <?= ($row->snapshot_id_mobile ? 'text-primary' : 'text-muted') ?>" data-toggle="tooltip" title="<?= ($row->snapshot_id_mobile ? l('heatmaps.snapshot_id_mobile') : l('heatmaps.snapshot_id_mobile_null')) ?>"><i class="fas fa-fw fa-lg fa-mobile"></i></a>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <?php if($row->is_enabled): ?>
                                <span class="badge badge-success"><i class="fas fa-fw fa-check"></i> <?= l('heatmaps.is_enabled_true') ?>
                                    <?php else: ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash"></i> <?= l('heatmaps.is_enabled_false') ?>
                            <?php endif ?>
                            </td>

                            <td class="text-nowrap text-muted">
                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->date, 2) . '<br /><small>' . \Altum\Date::get($row->date, 3) . '</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-muted"></i>
                            </span>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex justify-content-end">
                                    <?php if(!$this->team): ?>
                                        <?= include_view(THEME_PATH . 'views/heatmaps/heatmap_dropdown_button.php', ['id' => $row->heatmap_id, 'name' => $row->name, 'is_enabled' => $row->is_enabled]) ?>
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

    <?php endif ?>
</div>

<input type="hidden" name="website_id" value="<?= $this->website->website_id ?>" />

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
