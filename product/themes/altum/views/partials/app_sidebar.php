<?php defined('ALTUMCODE') || die() ?>

<section class="app-sidebar d-print-none">
    <div class="app-sidebar-title" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= settings()->main->title ?>">
        <a href="<?= url() ?>"><?= mb_substr(settings()->main->title, 0, 1) ?></a>
    </div>


    <div class="app-sidebar-links-wrapper">
        <ul class="app-sidebar-links">
            <li class="<?= \Altum\Router::$controller == 'Dashboard' && !string_ends_with('dashboard/goals', $_GET['altum']) ? 'active' : null ?>">
                <a href="<?= url('dashboard') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('dashboard.menu') ?>"><i class="fas fa-fw fa-th"></i></a>
            </li>

            <?php if($this->user->plan_settings->websites_goals_limit != 0): ?>
                <li class="<?= \Altum\Router::$controller == 'Dashboard' && string_ends_with('dashboard/goals', $_GET['altum']) ? 'active' : null ?>">
                    <a href="<?= url('dashboard/goals') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('analytics.goals') ?>"><i class="fas fa-fw fa-bullseye"></i></a>
                </li>
            <?php endif ?>

            <li class="<?= \Altum\Router::$controller == 'Realtime' ? 'active' : null ?>">
                <a href="<?= url('realtime') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('realtime.menu') ?>"><i class="fas fa-fw fa-clock"></i></a>
            </li>

            <?php if(!$this->website || ($this->website && $this->website->tracking_type == 'normal')): ?>
                <li class="<?= \Altum\Router::$controller == 'Visitors' ? 'active' : null ?>">
                    <a href="<?= url('visitors') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('visitors.menu') ?>"><i class="fas fa-fw fa-user-friends"></i></a>
                </li>

                <?php if(settings()->analytics->websites_heatmaps_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Heatmaps', 'Heatmap']) ? 'active' : null ?>">
                        <a href="<?= url('heatmaps') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('heatmaps.menu') ?>"><i class="fas fa-fw fa-fire"></i></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->analytics->sessions_replays_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Replays', 'Replay']) ? 'active' : null ?>">
                        <a href="<?= url('replays') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('replays.menu') ?>"><i class="fas fa-fw fa-video"></i></a>
                    </li>
                <?php endif ?>
            <?php endif ?>

            <li class="<?= \Altum\Router::$controller == 'Websites' ? 'active' : null ?>">
                <a href="<?= url('websites') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('websites.menu') ?>"><i class="fas fa-fw fa-pager"></i></a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['Teams', 'Team']) ? 'active' : null ?>">
                <a href="<?= url('teams') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('teams.menu') ?>"><i class="fas fa-fw fa-user-shield"></i></a>
            </li>

            <?php if(settings()->analytics->domains_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['Domains', 'DomainUpdate', 'DomainCreate']) ? 'active' : null ?>">
                    <a href="<?= url('domains') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('domains.menu') ?>"><i class="fas fa-fw fa-globe"></i></a>
                </li>
            <?php endif ?>

            <li>
                <a href="<?= url('help') ?>" data-toggle="tooltip" data-boundary="viewport" data-placement="right" title="<?= l('help.menu') ?>"><i class="fas fa-fw fa-question"></i></a>
            </li>

            <?php if(settings()->internal_notifications->users_is_enabled): ?>
                <li id="internal_notifications" class="dropdown">
                    <a id="internal_notifications_link" href="#" class="nav-link dropdown-toggle dropdown-toggle-simple" data-internal-notifications="user" data-tooltip data-tooltip-hide-on-click data-placement="right" title="<?= l('internal_notifications.menu') ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="window">
                    <span class="fa-layers fa-fw">
                        <i class="fas fa-fw fa-bell"></i>
                        <?php if($this->user->has_pending_internal_notifications): ?>
                            <span class="fa-layers-counter text-danger internal-notification-icon">&nbsp;</span>
                        <?php endif ?>
                    </span>
                    </a>

                    <div id="internal_notifications_content" class="dropdown-menu dropdown-menu-right px-4 py-2" style="width: 550px;max-width: 550px;"></div>
                </li>

                <?php include_view(THEME_PATH . 'views/partials/internal_notifications_js.php', ['has_pending_internal_notifications' => $this->user->has_pending_internal_notifications]) ?>
            <?php endif ?>
        </ul>
    </div>
</section>
