<?php defined('ALTUMCODE') || die() ?>

<div class="altum-animate altum-animate-fill-none altum-animate-fade-in">
<?php $i = 1; ?>
<?php foreach($data->events as $event): ?>
    <?php
    $event['type'] = 'pageview';
    $event['date'] = (new \DateTime())->setTimestamp((int) ($event['timestamp'] / 1000))->format('Y-m-d H:i:s');
    ?>
    <div class="card bg-gray-200 border-0">
        <div class="card-body">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 mb-md-0">
                <span class=""><i class="<?= l('analytics.' . $event['type'] . '.icon') ?> text-muted"></i> <?= l('analytics.' . $event['type'] . '.name') ?></span>
                <small class="text-muted"><?= \Altum\Date::get($event['date'], 3) ?></small>
            </div>

            <div class="d-flex flex-column">
                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <span class="mb-2 mb-md-0"><small><?= $event['data']->href ?></small></span>

                    <small class="text-muted" data-toggle="tooltip" title="<?= l('analytics.viewport') ?>">
                        <i class="fas fa-window-maximize"></i> <?= $event['data']->width . 'x' . $event['data']->height ?>
                    </small>
                </div>
            </div>

        </div>
    </div>

    <?php if($i++ != count($data->events)): ?>
        <div class="text-center"><i class="fas fa-fw fa-arrow-down fa-sm text-muted"></i></div>
    <?php endif ?>
<?php endforeach ?>
</div>
