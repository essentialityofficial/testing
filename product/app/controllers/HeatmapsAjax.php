<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Date;
use Altum\Response;

class HeatmapsAjax extends Controller {

    public function index() {
        die();
    }

    private function verify() {
        \Altum\Authentication::guard();

        if(!\Altum\Csrf::check() && !\Altum\Csrf::check('global_token')) {
            die();
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');
    }

    public function create() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['name'] = trim(query_clean($_POST['name']));
        $_POST['path'] = '/' . trim(query_clean($_POST['path']));
        $is_enabled = 1;

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Get the count of already created goals */
        $total_websites_heatmaps = database()->query("SELECT COUNT(*) AS `total` FROM `websites_heatmaps` WHERE `website_id` = {$this->website->website_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->websites_heatmaps_limit != -1 && $total_websites_heatmaps >= $this->user->plan_settings->websites_heatmaps_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Database query */
        db()->insert('websites_heatmaps', [
            'user_id' => $this->user->user_id,
            'website_id' => $this->website->website_id,
            'name' => $_POST['name'],
            'path' => $_POST['path'],
            'is_enabled' => $is_enabled,
            'datetime' => Date::$date,
        ]);

        /* Clear cache */
        cache()->deleteItem('website_heatmaps?website_id=' . $this->website->website_id);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));
    }

    public function update() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['name'] = trim(query_clean($_POST['name']));
        $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
        $_POST['heatmap_id'] = (int) $_POST['heatmap_id'];

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Database query */
        db()->where('website_id', $this->website->website_id)->where('heatmap_id', $_POST['heatmap_id'])->update('websites_heatmaps', [
            'name' => $_POST['name'],
            'is_enabled' => $_POST['is_enabled'],
            'heatmap_id' => $_POST['heatmap_id'],
            'last_datetime' => Date::$date,
        ]);

        /* Clear cache */
        cache()->deleteItem('website_heatmaps?website_id=' . $this->website->website_id);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));
    }

    public function retake_snapshots() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['snapshot_id_desktop'] = (int) isset($_POST['snapshot_id_desktop']);
        $_POST['snapshot_id_tablet'] = (int) isset($_POST['snapshot_id_tablet']);
        $_POST['snapshot_id_mobile'] = (int) isset($_POST['snapshot_id_mobile']);
        $_POST['heatmap_id'] = (int) $_POST['heatmap_id'];

        foreach(['desktop', 'tablet', 'mobile'] as $key) {
            if($_POST['snapshot_id_' . $key]) {
                db()->where('website_id', $this->website->website_id)->where('heatmap_id', $_POST['heatmap_id'])->where('type', $key)->delete('heatmaps_snapshots');
            }
        }

        /* Clear cache */
        cache()->deleteItem('website_heatmaps?website_id=' . $this->website->website_id);

        Response::json(l('heatmap_retake_snapshots_modal.success_message'), 'success');
    }

}
