<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('replay/' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('global.view') ?></a>
        <a href="#" data-toggle="modal" data-target="#replay_delete_modal" data-replay-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'replay',
    'resource_id' => 'replay_id',
    'has_dynamic_resource_name' => false,
    'path' => 'replays/delete'
]), 'modals', 'replay_delete_modal'); ?>
