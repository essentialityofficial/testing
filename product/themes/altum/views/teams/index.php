<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-user-shield mr-1"></i> <?= l('teams.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('teams.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <?php if($this->user->plan_settings->teams_is_enabled): ?>
        <div class="mb-6">
            <div class="row mb-4">
                <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
                    <h2 class="h5 m-0 text-truncate"><?= l('teams.teams.header') ?></h2>
                </div>

                <?php if(!$this->team && $this->user->plan_settings->teams_is_enabled): ?>
                    <div class="col-12 col-lg-auto d-flex d-print-none">
                        <button type="button" data-toggle="modal" data-target="#team_create" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('teams.teams.create') ?></button>
                    </div>
                <?php endif ?>
            </div>

            <?php if($this->team): ?>
                <div class="alert alert-info" role="alert">
                    <?= l('global.info_message.team_not_allowed') ?>
                </div>
            <?php else: ?>

                <?php if($data->teams_result->num_rows): ?>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('teams.teams.team') ?></th>
                                <th><?= l('teams.teams.websites_ids') ?></th>
                                <th><?= l('teams.teams.users') ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php while($team = $data->teams_result->fetch_object()): ?>
                                <?php $team->websites_ids = json_decode($team->websites_ids) ?>
                                <tr data-team-id="<?= $team->team_id ?>">
                                    <td class="text-nowrap">
                                        <a href="<?= url('team/' . $team->team_id) ?>"><?= $team->name ?></a>
                                    </td>

                                    <td class="text-nowrap">
                                        <span class="badge badge-light">
                                            <i class="fas fa-fw fa-pager fa-sm mr-1"></i> <?= nr(count($team->websites_ids)) ?>
                                        </span>
                                    </td>

                                    <td class="text-nowrap text-muted">
                                        <span class="badge badge-dark">
                                            <i class="fas fa-fw fa-users fa-sm mr-1"></i> <?= nr($team->users) ?>
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        <div class="d-flex justify-content-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                                    <i class="fas fa-fw fa-ellipsis-v"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                            href="#"
                                                            class="dropdown-item"
                                                            data-toggle="modal"
                                                            data-target="#team_update"
                                                            data-team-id="<?= $team->team_id ?>"
                                                            data-name="<?= $team->name ?>"
                                                            data-websites-ids="<?= json_encode($team->websites_ids) ?>"
                                                    >
                                                        <i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?>
                                                    </a>
                                                    <a
                                                            href="#"
                                                            class="dropdown-item"
                                                            data-toggle="modal"
                                                            data-target="#team_delete"
                                                            data-team-id="<?= $team->team_id ?>"
                                                    >
                                                        <i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile ?>

                            </tbody>
                        </table>
                    </div>

                <?php else: ?>

                    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                        'filters_get' => $data->filters->get ?? [],
                        'name' => 'teams.teams',
                        'has_secondary_text' => true,
                    ]); ?>

                <?php endif ?>

            <?php endif ?>
        </div>
    <?php endif ?>

    <div>
        <div class="row mb-4">
            <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
                <h2 class="h5 m-0 text-truncate"><?= l('teams.teams_associations.header') ?></h2>
            </div>
        </div>

        <?php if($data->teams_associations_result->num_rows): ?>

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th><?= l('teams.teams_associations.team') ?></th>
                        <th><?= l('teams.teams_associations.websites_ids') ?></th>
                        <th><?= l('teams.teams_associations.is_accepted') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php while($team = $data->teams_associations_result->fetch_object()): ?>
                        <?php $team->websites_ids = json_decode($team->websites_ids) ?>
                        <tr data-team-association-id="<?= $team->team_association_id ?>">
                            <td class="text-nowrap">
                                <span><?= $team->name ?></span>
                            </td>

                            <td class="text-nowrap">
                                <span class="badge badge-light">
                                    <i class="fas fa-fw fa-pager fa-sm mr-1"></i> <?= nr(count($team->websites_ids)) ?>
                                </span>
                            </td>

                            <td class="text-nowrap">
                                <?php if($team->is_accepted): ?>
                                    <span class="badge badge-pill badge-success">
                                        <i class="fas fa-fw fa-check"></i> <?= l('teams.teams_associations.accepted_date') ?>
                                    </span>

                                    <small class="text-muted"><?= \Altum\Date::get($team->date, 2) ?></small>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-warning">
                                    <?= l('team.teams_associations.is_accepted_invited') ?>
                                </span>
                                <?php endif ?>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                            <i class="fas fa-fw fa-ellipsis-v"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if($team->is_accepted): ?>
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-team-login="true"
                                                        data-team-id="<?= $team->team_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-sign-in-alt mr-2"></i> <?= l('teams.teams_associations.login') ?>
                                                </a>
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#team_association_delete"
                                                        data-team-association-id="<?= $team->team_association_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('teams.teams_associations.delete') ?>
                                                </a>
                                            <?php else: ?>
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-team-association-accept="true"
                                                        data-team-association-id="<?= $team->team_association_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-sign-in-alt mr-2"></i> <?= l('teams.teams_associations.accept') ?>
                                                </a>
                                                <a
                                                        href="#"
                                                        class="dropdown-item"
                                                        data-toggle="modal"
                                                        data-target="#team_association_delete"
                                                        data-team-association-id="<?= $team->team_association_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('teams.teams_associations.decline') ?>
                                                </a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile ?>

                    </tbody>
                </table>
            </div>

        <?php else: ?>

            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                'filters_get' => $data->filters->get ?? [],
                'name' => 'teams.teams_associations',
                'has_secondary_text' => true,
            ]); ?>

        <?php endif ?>

    </div>

</div>

<?php ob_start() ?>
<script>
    /* Login for the team */
    $('[data-team-login]').on('click', event => {
        let team_id = $(event.currentTarget).data('team-id');

        /* Set the cookie */
        set_cookie('selected_team_id', team_id, 30, <?= json_encode(COOKIE_PATH) ?>);

        redirect('dashboard');

        event.preventDefault();
    });

    /* Accept request for the team association */
    $('[data-team-association-accept]').on('click', event => {
        let team_association_id = $(event.currentTarget).data('team-association-id');

        $.ajax({
            type: 'POST',
            url: `${url}teams-associations-ajax`,
            data: {team_association_id, global_token, request_type: 'update'},
            success: (data) => {
                if(data.status == 'error') {
                    /* Nothing */
                }

                else if(data.status == 'success') {

                    redirect('teams');

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

