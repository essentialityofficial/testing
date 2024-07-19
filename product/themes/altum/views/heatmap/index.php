<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('heatmaps') ?>"><?= l('heatmaps.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('heatmap.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-fire mr-1"></i> <?= $data->heatmap->name ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <div>
                <div class="btn-group dropdown" role="group">
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/desktop') ?>" class="btn btn-sm <?= $data->snapshot_type == 'desktop' ? 'btn-primary' : 'btn-secondary' ?>" data-toggle="tooltip" title="<?= l('heatmap_retake_snapshots_modal.input.snapshot_id_desktop') ?>">
                        <i class="fas fa-fw fa-sm fa-desktop mr-1"></i> <?= $data->snapshot_type == 'desktop' ? sprintf(l('heatmap.click'), '<span id="heatmap_data_count"><div class="spinner-grow spinner-grow-sm text-light" role="status"></div></span>') : null ?>
                    </a>
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/tablet') ?>" class="btn btn-sm <?= $data->snapshot_type == 'tablet' ? 'btn-primary' : 'btn-secondary' ?>" data-toggle="tooltip" title="<?= l('heatmap_retake_snapshots_modal.input.snapshot_id_tablet') ?>">
                        <i class="fas fa-fw fa-sm fa-tablet mr-1"></i> <?= $data->snapshot_type == 'tablet' ? sprintf(l('heatmap.click'), '<span id="heatmap_data_count"><div class="spinner-grow spinner-grow-sm text-light" role="status"></div></span>') : null ?>
                    </a>
                    <a href="<?= url('heatmap/' . $data->heatmap->heatmap_id . '/mobile') ?>" class="btn btn-sm <?= $data->snapshot_type == 'mobile' ? 'btn-primary' : 'btn-secondary' ?>" data-toggle="tooltip" title="<?= l('heatmap_retake_snapshots_modal.input.snapshot_id_mobile') ?>">
                        <i class="fas fa-fw fa-sm fa-mobile mr-1"></i> <?= $data->snapshot_type == 'mobile' ? sprintf(l('heatmap.click'), '<span id="heatmap_data_count"><div class="spinner-grow spinner-grow-sm text-light" role="status"></div></span>') : null ?>
                    </a>
                </div>
            </div>

            <?php if(!$this->team): ?>
                <?= include_view(THEME_PATH . 'views/heatmaps/heatmap_dropdown_button.php', ['id' => $data->heatmap->heatmap_id, 'name' => $data->heatmap->name, 'is_enabled' => $data->heatmap->is_enabled]) ?>
            <?php endif ?>
        </div>
    </div>

    <div class="mb-4">
        <?php if($data->heatmap->is_enabled): ?>
            <span class="badge badge-success"><i class="fas fa-fw fa-check"></i> <?= l('heatmaps.is_enabled_true') ?></span>
        <?php else: ?>
            <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash"></i> <?= l('heatmaps.is_enabled_false') ?></span>
        <?php endif ?>

        <small class="ml-3 text-muted"><?= $this->website->host . $this->website->path . $data->heatmap->path ?></small>
    </div>


    <div class="notification-container mb-3"></div>

    <?php if(!$data->snapshot): ?>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-4">
                    <img src="<?= ASSETS_FULL_URL . 'images/collecting.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= l('heatmap.no_data') ?>" />
                    <h2 class="h4 text-muted"><?= l('heatmap.no_data') ?></h2>
                    <p class="text-muted m-0"><?= l('heatmap.no_data_help') ?></a></p>
                </div>
            </div>
        </div>

    <?php else: ?>

        <div class="position-relative">
            <div id="heatmap-loading"></div>

            <div id="heatmap-container" class="heatmap-container shadow-md rounded" style="display: none;">
                <div id="heatmap-inner" class="position-relative">
                    <canvas id="heatmap-canvas" class="heatmap-canvas"></canvas>
                </div>
            </div>
        </div>

    <?php endif ?>
</div>


<?php if($data->snapshot): ?>
    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/simpleheat.js' ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/rrweb.mod.js' ?>"></script>

    <script>
        /* Default loading state */
        let loading_html = $('#loading').html();
        $('#heatmap-loading').html(loading_html);

        let player = null;
        let simpleheatdata = null;

        /* Request the data */
        $.ajax({
            type: 'GET',
            url: `${url}heatmap/read/<?= $data->heatmap->heatmap_id ?>/<?= $data->snapshot->type ?>`,
            success: (result) => {

                /* Generate the heatmap */
                player = new rrweb.Replayer(result.snapshot_data, {
                    root: document.querySelector('#heatmap-inner'),
                });

                player.play();

                /* Save the data for the heatmap */
                simpleheatdata = result.heatmap_data;

                /* Count */
                $('#heatmap_data_count').text(result.heatmap_data_count);

                /* Remove the loading state */
                $('#heatmap-loading').html('');

                /* Display it */
                $('#heatmap-container').fadeIn();

                /* Draw the heatmap after x time */
                setTimeout(() => {
                    heatmap_draw();
                }, 1000);

                /* Timeout loading after 5 seconds */
                setTimeout(() => {
                    window.stop();
                }, 5000);

            },
            dataType: 'json'
        });

        /* Prepare the heatmap */
        let heatmap_resize = () => {

            /* Default iframe height */
            let width = $('#heatmap-inner iframe').data('width');

            /* Full iframe height */
            let height = document.querySelector('#heatmap-inner iframe').contentWindow.document.querySelector('body').scrollHeight;

            $('#heatmap-container').css('width', width);
            $('#heatmap-canvas').attr('width', width).attr('height', height);
            $('#heatmap-container iframe').attr('width', width).attr('height', height);
            $('#heatmap-inner').css('width', width).css('height', height);

            heatmap_proper_scale()
        };

        let heatmap_proper_scale = () => {
            let container_width = $('div[class="container"]').width();
            let heatmap_container_width = $('#heatmap-container').width();

            if(heatmap_container_width > container_width) {
                let transform_scale = Math.round(container_width / heatmap_container_width * 100);
                $('#heatmap-container').css('transform', `scale(0.${transform_scale})`);

                let margin_bottom = (1 - transform_scale / 100) * parseInt($('#heatmap-container').css('height'));
                let margin_bottom_px = `-${margin_bottom}px`;
                $('#heatmap-container').css('margin-bottom', margin_bottom_px);
            }

        };

        $(window).on('resize', heatmap_proper_scale);

        let heatmap_draw = () => {
            heatmap_resize();

            simpleheat('heatmap-canvas').data(simpleheatdata).draw();
        };
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php else: ?>

    <?php ob_start() ?>
    <script>
        /* Count */
        $('#heatmap_data_count').html('0');
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php endif ?>
