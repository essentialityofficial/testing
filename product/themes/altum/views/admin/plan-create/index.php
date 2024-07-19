<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/plans') ?>"><?= l('admin_plans.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_plan_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= l('admin_plan_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" required="required" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#name_translate_container" aria-expanded="false" aria-controls="name_translate_container" data-tooltip title="<?= l('admin_plans.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="collapse" id="name_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_name' ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_name' ?>" name="<?= 'translations[' . $language_name . '][name]' ?>" value="" class="form-control" maxlength="64" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                <div class="input-group">
                    <input type="text" id="description" name="description" class="form-control <?= \Altum\Alerts::has_field_errors('description') ? 'is-invalid' : null ?>" value="" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#description_translate_container" aria-expanded="false" aria-controls="description_translate_container" data-tooltip title="<?= l('admin_plans.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('description') ?>
            </div>

            <div class="collapse" id="description_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_description' ?>"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_description' ?>" name="<?= 'translations[' . $language_name . '][description]' ?>" value="" class="form-control" maxlength="256" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input type="number" min="0" id="order" name="order" class="form-control" value="" />
            </div>

            <div class="form-group">
                <label for="trial_days"><i class="fas fa-fw fa-sm fa-calendar-check text-muted mr-1"></i> <?= l('admin_plans.main.trial_days') ?></label>
                <input id="trial_days" type="number" min="0" name="trial_days" class="form-control" value="0" />
                <div><small class="form-text text-muted"><?= l('admin_plans.main.trial_days_help') ?></small></div>
            </div>

            <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                <div class="row">
                    <div class="col-sm-12 col-xl-4">
                        <div class="form-group">
                            <label for="monthly_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar-alt text-muted mr-1"></i> <?= l('admin_plans.main.monthly_price') ?></label>
                            <div class="input-group">
                                <input type="text" id="monthly_price[<?= $currency ?>]" name="monthly_price[<?= $currency ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('monthly_price[' . $currency . ']') ? 'is-invalid' : null ?>" required="required" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><?= $currency ?></span>
                                </div>
                            </div>
                            <?= \Altum\Alerts::output_field_error('monthly_price[' . $currency . ']') ?>
                            <small class="form-text text-muted"><?= sprintf(l('admin_plans.main.price_help'), l('admin_plans.main.monthly_price')) ?></small>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-4">
                        <div class="form-group">
                            <label for="annual_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= l('admin_plans.main.annual_price') ?></label>
                            <div class="input-group">
                                <input type="text" id="annual_price[<?= $currency ?>]" name="annual_price[<?= $currency ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('annual_price[' . $currency . ']') ? 'is-invalid' : null ?>" required="required" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><?= $currency ?></span>
                                </div>
                            </div>
                            <?= \Altum\Alerts::output_field_error('annual_price[' . $currency . ']') ?>
                            <small class="form-text text-muted"><?= sprintf(l('admin_plans.main.price_help'), l('admin_plans.main.annual_price')) ?></small>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-4">
                        <div class="form-group">
                            <label for="lifetime_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-infinity text-muted mr-1"></i> <?= l('admin_plans.main.lifetime_price') ?></label>
                            <div class="input-group">
                                <input type="text" id="lifetime_price[<?= $currency ?>]" name="lifetime_price[<?= $currency ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('lifetime_price[' . $currency . ']') ? 'is-invalid' : null ?>" required="required" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><?= $currency ?></span>
                                </div>
                            </div>
                            <?= \Altum\Alerts::output_field_error('lifetime_price[' . $currency . ']') ?>
                            <small class="form-text text-muted"><?= sprintf(l('admin_plans.main.price_help'), l('admin_plans.main.lifetime_price')) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>

            <div class="form-group">
                <label for="taxes_ids"><i class="fas fa-fw fa-sm fa-paperclip text-muted mr-1"></i> <?= l('admin_plans.main.taxes_ids') ?></label>
                <select id="taxes_ids" name="taxes_ids[]" class="custom-select" multiple="multiple">
                    <?php if($data->taxes): ?>
                        <?php foreach($data->taxes as $tax): ?>
                            <option value="<?= $tax->tax_id ?>">
                                <?= $tax->name . ' - ' . $tax->description ?>
                            </option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
                <small class="form-text text-muted"><?= sprintf(l('admin_plans.main.taxes_ids_help'), '<a href="' . url('admin/taxes') .'">', '</a>') ?></small>
            </div>

            <div class="form-group">
                <label for="color"><i class="fas fa-fw fa-sm fa-palette text-muted mr-1"></i> <?= l('admin_plans.main.color') ?></label>
                <input type="text" id="color" name="color" class="form-control <?= \Altum\Alerts::has_field_errors('color') ? 'is-invalid' : null ?>" value="" />
                <?= \Altum\Alerts::output_field_error('color') ?>
                <small class="form-text text-muted"><?= l('admin_plans.main.color_help') ?></small>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-fw fa-sm fa-circle-dot text-muted mr-1"></i> <?= l('global.status') ?></label>
                <select id="status" name="status" class="custom-select">
                    <option value="1"><?= l('global.active') ?></option>
                    <option value="0"><?= l('global.disabled') ?></option>
                    <option value="2"><?= l('global.hidden') ?></option>
                </select>
            </div>

            <h2 class="h4 mt-5 mb-4"><?= l('admin_plans.plan.header') ?></h2>

            <div>
                <div class="form-group">
                    <label for="websites_limit"><?= l('admin_plans.plan.websites_limit') ?></label>
                    <input type="number" id="websites_limit" name="websites_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="sessions_events_limit"><?= l('admin_plans.plan.sessions_events_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                    <input type="number" id="sessions_events_limit" name="sessions_events_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="sessions_events_retention"><?= l('admin_plans.plan.sessions_events_retention') ?></label>
                    <div class="input-group">
                        <input type="number" id="sessions_events_retention" name="sessions_events_retention" min="-1" class="form-control" value="365" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.days') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('admin_plans.plan.retention_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="events_children_limit"><?= l('admin_plans.plan.events_children_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                    <input type="number" id="events_children_limit" name="events_children_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.events_children_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="events_children_retention"><?= l('admin_plans.plan.events_children_retention') ?></label>
                    <div class="input-group">
                        <input type="number" id="events_children_retention" name="events_children_retention" min="-1" class="form-control" value="0" required="required" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.days') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('admin_plans.plan.retention_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="sessions_replays_limit"><?= l('admin_plans.plan.sessions_replays_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                    <input type="number" id="sessions_replays_limit" name="sessions_replays_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.sessions_replays_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="sessions_replays_retention"><?= l('admin_plans.plan.sessions_replays_retention') ?></label>
                    <div class="input-group">
                        <input type="number" id="sessions_replays_retention" name="sessions_replays_retention" min="-1" class="form-control" value="0" required="required" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.days') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('admin_plans.plan.retention_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="sessions_replays_time_limit"><?= l('admin_plans.plan.sessions_replays_time_limit') ?> <small class="form-text text-muted"><?= l('global.date.minutes') ?></small></label>
                    <input type="number" id="sessions_replays_time_limit" name="sessions_replays_time_limit" min="1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.sessions_replays_time_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="websites_heatmaps_limit"><?= l('admin_plans.plan.websites_heatmaps_limit') ?></label>
                    <input type="number" id="websites_heatmaps_limit" name="websites_heatmaps_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.websites_heatmaps_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="websites_goals_limit"><?= l('admin_plans.plan.websites_goals_limit') ?></label>
                    <input type="number" id="websites_goals_limit" name="websites_goals_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.websites_goals_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="domains_limit"><?= l('admin_plans.plan.domains_limit') ?></label>
                    <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control" value="0" required="required" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                    <div class="form-group">
                        <label for="affiliate_commission_percentage"><?= l('admin_plans.plan.affiliate_commission_percentage') ?></label>
                        <input type="number" id="affiliate_commission_percentage" name="affiliate_commission_percentage" min="0" max="100" class="form-control" value="0" required="required" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.affiliate_commission_percentage_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="mb-3">
                    <div class="custom-control custom-switch">
                        <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="email_reports_is_enabled"><?= l('admin_plans.plan.email_reports_is_enabled') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.email_reports_is_enabled_help') ?></small></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="custom-control custom-switch">
                        <input id="teams_is_enabled" name="teams_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="teams_is_enabled"><?= l('admin_plans.plan.teams_is_enabled') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.teams_is_enabled_help') ?></small></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="custom-control custom-switch">
                        <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="no_ads"><?= l('admin_plans.plan.no_ads') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.no_ads_help') ?></small></div>
                    </div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="api_is_enabled"><?= l('admin_plans.plan.api_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.api_is_enabled_help') ?></small></div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>

        </form>

    </div>
</div>
