<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;

class Heatmap extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!$this->website || !settings()->analytics->websites_heatmaps_is_enabled) {
            redirect('websites');
        }

        $heatmap_id = (isset($this->params[0])) ? (int) query_clean($this->params[0]) : 0;
        $snapshot_type = (isset($this->params[1])) && in_array($this->params[1], ['desktop', 'tablet', 'mobile']) ? query_clean($this->params[1]) : 'desktop';
        $heatmap_data_type = (isset($this->params[2])) && in_array($this->params[2], ['click', 'scroll']) ? query_clean($this->params[2]) : 'click';

        /* Get the Visitor basic data and make sure it exists */
        $heatmap = database()->query("SELECT * FROM `websites_heatmaps` WHERE `heatmap_id` = {$heatmap_id} AND `website_id` = {$this->website->website_id}")->fetch_object() ?? null;

        if(!$heatmap) redirect('heatmaps');

        /* Get snapshot data */
        $snapshot = database()->query("SELECT `snapshot_id`, `type` FROM `heatmaps_snapshots` WHERE `heatmap_id` = {$heatmap->heatmap_id} AND `type` = '{$snapshot_type}'")->fetch_object() ?? null;

        /* Update Modal */
        $view = new \Altum\View('heatmap/heatmap_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Modal */
        $view = new \Altum\View('heatmap/heatmap_retake_snapshots_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'heatmap'   => $heatmap,
            'snapshot'  => $snapshot,
            'snapshot_type' => $snapshot_type,
        ];

        $view = new \Altum\View('heatmap/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function read() {

        \Altum\Authentication::guard();

        $heatmap_id = (isset($this->params[0])) ? (int) query_clean($this->params[0]) : 0;
        $snapshot_type = (isset($this->params[1])) && in_array($this->params[1], ['desktop', 'tablet', 'mobile']) ? query_clean($this->params[1]) : 'desktop';
        $heatmap_data_type = (isset($this->params[2])) && in_array($this->params[2], ['click', 'scroll']) ? query_clean($this->params[2]) : 'click';

        /* Get snapshot data */
        $snapshot = database()->query("SELECT * FROM `heatmaps_snapshots` WHERE `heatmap_id` = {$heatmap_id} AND `website_id` = {$this->website->website_id} AND `type` = '{$snapshot_type}'")->fetch_object() ?? null;

        if($snapshot) {
            /* Decode the snapshot */
            $snapshot->data = json_decode(gzdecode($snapshot->data));

            /* Get all the data needed for the heatmap */
            $heatmap_data = [];

            $result = database()->query("SELECT `data`, `count`, `type` FROM `events_children` WHERE `snapshot_id` = {$snapshot->snapshot_id} AND `type` = '{$heatmap_data_type}' AND `website_id` = {$this->website->website_id}");

            while($row = $result->fetch_object()) {
                $row->data = json_decode($row->data);

                /* Initial processing to prepare for the heatmap */
                for($i = 0; $i < (int) $row->count; $i++) {
                    switch ($row->type) {
                        case 'click':

                            $event = [
                                (int) $row->data->mouse->x,
                                (int) $row->data->mouse->y
                            ];

                            break;
                    }

                    $heatmap_data[] = $event;
                }

            }

            /* Clear the cache */
            cache()->deleteItemsByTag('website_id=' . $this->website->website_id);

            Response::simple_json([
                'snapshot_data' => $snapshot->data,
                'heatmap_data' => $heatmap_data,
                'heatmap_data_count' => nr(count($heatmap_data))
            ]);
        }

    }

}
