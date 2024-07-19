<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('help.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row">
        <div class="col-12 col-lg-4 mb-5 mb-lg-0">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="<?= url('help') ?>" class="nav-link <?= $data->page == 'introduction' ? 'active' : null ?>">
                                <i class="fas fa-fw fa-sm fa-file mr-1"></i> <?= l('help.introduction.menu') ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= url('help/lightweight-tracking') ?>" class="nav-link <?= $data->page == 'lightweight_tracking' ? 'active' : null ?>">
                                <i class="fas fa-fw fa-sm fa-chart-bar mr-1"></i> <?= l('help.lightweight_tracking.menu') ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= url('help/advanced-tracking') ?>" class="nav-link <?= $data->page == 'advanced_tracking' ? 'active' : null ?>">
                                <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= l('help.advanced_tracking.menu') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col col-lg-8">
            <div class="card">
                <div class="card-body">
                    <?= $this->views['page'] ?>
                </div>
            </div>
        </div>
    </div>
</div>
