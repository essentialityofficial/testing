<?php defined('ALTUMCODE') || die() ?>

<ul class="pricing-feature-list">
    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
        <div>
            <?= sprintf(l('global.plan_settings.websites_limit'), '<strong>' . ($data->plan_settings->websites_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->websites_limit)) . '</strong>') ?>
        </div>
    </li>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-check-circle fa-sm mr-3 text-success"></i>
        <div>
            <?= sprintf(l('global.plan_settings.sessions_events_limit'), '<strong>' . ($data->plan_settings->sessions_events_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->sessions_events_limit)) . '</strong>') . ' <small class="text-muted">' . l('global.plan_settings.per_month') . '</small>' ?>
        </div>
    </li>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
        <div>
            <?= sprintf(l('global.plan_settings.sessions_events_retention'), '<strong>' . ($data->plan_settings->sessions_events_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->sessions_events_retention)) . '</strong>') ?>
        </div>
    </li>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->events_children_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
        <div>
            <?= sprintf(l('global.plan_settings.events_children_limit'), '<strong>' . ($data->plan_settings->events_children_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->events_children_limit)) . '</strong>') . ' <small class="text-muted">' . l('global.plan_settings.per_month') . '</small>' ?>
        </div>
    </li>

    <?php if($data->plan_settings->events_children_limit != 0): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
            <div>
                <?= sprintf(l('global.plan_settings.events_children_retention'), '<strong>' . ($data->plan_settings->events_children_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->events_children_retention)) . '</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->analytics->sessions_replays_is_enabled): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->sessions_replays_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
            <div>
                <?= sprintf(l('global.plan_settings.sessions_replays_limit'), '<strong>' . ($data->plan_settings->sessions_replays_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->sessions_replays_limit)) . '</strong>') . ' <small class="text-muted">' . l('global.plan_settings.per_month') . '</small>' ?>
            </div>
        </li>

        <?php if($data->plan_settings->sessions_replays_limit != 0): ?>
            <li class="pricing-feature d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                <div>
                    <?= sprintf(l('global.plan_settings.sessions_replays_retention'), '<strong>' . ($data->plan_settings->sessions_replays_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->sessions_replays_retention)) . '</strong>') ?>
                </div>
            </li>
        <?php endif ?>
    <?php endif ?>

    <?php if(settings()->analytics->websites_heatmaps_is_enabled): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->websites_heatmaps_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
            <div>
                <?= sprintf(l('global.plan_settings.websites_heatmaps_limit'), '<strong>' . ($data->plan_settings->websites_heatmaps_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->websites_heatmaps_limit)) . '</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->websites_goals_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
        <div>
            <?= sprintf(l('global.plan_settings.websites_goals_limit'), '<strong>' . ($data->plan_settings->websites_goals_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->websites_goals_limit)) . '</strong>') ?>
        </div>
    </li>

    <?php if(settings()->analytics->domains_is_enabled): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->domains_limit ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
            <div>
                <?= sprintf(l('global.plan_settings.domains_limit'), '<strong>' . ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit)) . '</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= !$data->plan_settings->affiliate_commission_percentage ? 'fa-times-circle text-muted' : 'fa-check-circle text-success' ?>"></i>
            <div>
                <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), '<strong>' . nr($data->plan_settings->affiliate_commission_percentage) . '%</strong>') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->analytics->email_reports_is_enabled): ?>
        <li class="pricing-feature d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?=$data->plan_settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div>
                <?= settings()->analytics->email_reports_is_enabled ? l('global.plan_settings.email_reports_is_enabled_' . settings()->analytics->email_reports_is_enabled) : l('global.plan_settings.email_reports_is_enabled') ?>
            </div>
        </li>
    <?php endif ?>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?=$data->plan_settings->teams_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div>
            <?= l('global.plan_settings.teams_is_enabled') ?>
        </div>
    </li>

    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?=$data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div>
            <?= l('global.plan_settings.no_ads') ?>
        </div>
    </li>

    <?php if(settings()->main->api_is_enabled): ?>
    <li class="pricing-feature d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?=$data->plan_settings->api_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div>
            <?= l('global.plan_settings.api_is_enabled') ?>
        </div>
    </li>
    <?php endif ?>
</ul>
