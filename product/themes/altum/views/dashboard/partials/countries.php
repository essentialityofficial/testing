<?php defined('ALTUMCODE') || die() ?>

<div class="row mt-5">

    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 m-0"><?= l('global.countries') ?></h2>
                    </div>
                    <span class="round-circle-sm bg-gray-200 text-primary-700 p-3">
                        <i class="fas fa-fw fa-sm fa-globe"></i>
                    </span>
                </div>

                <div class="mt-4" id="countries_result" data-limit="-1"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0">
            <div class="card-body pt-4">
                <div id="countries_map"></div>
            </div>
        </div>
    </div>

</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/svgMap.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/svgMap.min.js' ?>"></script>
<script>
    'use strict';

    $(`#countries_map`).html($('#loading').html());

    /* Receive data */
    $('#countries_map').on('load', (event, data) => {

        data = JSON.parse(data);

        /* Prepare the data for the map */
        let values = {};

        for(let row of data.rows) {
            values[row.country_code] = {
                visitors: parseInt(row.total)
            }
        }

        /* Clear html of loading */
        $(`#countries_map`).html('');

        /* Get CSS */
        let css = window.getComputedStyle(document.body);

        /* Create the map */
        new svgMap({
            targetElementID: 'countries_map',
            data: {
                data: {
                    visitors: {
                        name: '',
                        format: '{0} <?= l('analytics.visitors') ?>',
                        thousandSeparator: thousands_separator,
                    },
                },
                applyData: 'visitors',
                values: values,
            },
            colorMin: css.getPropertyValue('--primary-100'),
            colorMax: css.getPropertyValue('--primary-800'),
            colorNoData: css.getPropertyValue('--gray-200'),
            flagType: 'emoji',
            noDataText: <?= json_encode(l('global.no_data')) ?>
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
