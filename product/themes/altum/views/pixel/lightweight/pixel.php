<?php defined('ALTUMCODE') || die() ?>

(() => {
    let pixel_url_base = <?= json_encode(isset(\Altum\Router::$data['domain']) ? \Altum\Router::$data['domain']->url : url()) ?>;
    let pixel_key = <?= json_encode($data->pixel_key) ?>;
    let pixel_exposed_identifier = <?= json_encode(settings()->analytics->pixel_exposed_identifier) ?>;
    let pixel_goals = <?= json_encode($data->pixel_goals) ?>;
    let pixel_query_parameters_tracking_is_enabled = <?= json_encode($data->pixel_query_parameters_tracking_is_enabled) ?>;

    /* Helper messages */
    let pixel_key_dnt_message = <?= json_encode(l('pixel.info_message.dnt')) ?>;

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-helpers.js' ?>

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-header.js' ?>

    <?php require_once ASSETS_PATH . 'js/pixel/lightweight/pixel-footer.js' ?>
})();
