<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="<?= url('heatmap/' . $data->id) ?>"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('global.view') ?></a>
        <a href="#" data-toggle="modal" data-target="#heatmap_update" data-heatmap-id="<?= $data->id ?>" data-name="<?= $data->name ?>" data-is-enabled="<?= (bool) $data->is_enabled ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.update') ?></a>
        <a href="#" data-toggle="modal" data-target="#heatmap_retake_snapshots" data-heatmap-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-camera mr-2"></i> <?= l('heatmaps.retake_snapshots') ?></a>
        <a href="#" data-toggle="modal" data-target="#heatmap_delete_modal" data-heatmap-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'heatmap',
    'resource_id' => 'heatmap_id',
    'has_dynamic_resource_name' => false,
    'path' => 'heatmaps/delete'
]), 'modals', 'heatmap_delete_modal'); ?>
