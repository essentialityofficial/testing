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

class TeamsAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Make sure its not a request from a team member */
        if($this->team || !$this->user->plan_settings->teams_is_enabled) {
            die();
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(!empty($_POST) && (\Altum\Csrf::check() || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        $_POST['name'] = trim(query_clean($_POST['name']));
        $websites_ids = [];

        /* Check for possible errors */
        if(empty($_POST['name']) || !isset($_POST['websites_ids'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        foreach($_POST['websites_ids'] as $website_id) {
            if(array_key_exists($website_id, $this->websites)) {
                $websites_ids[] = (int) $website_id;
            }
        }

        if(!count($websites_ids)) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $websites_ids = json_encode($websites_ids);

        /* Database query */
        $team_id = db()->insert('teams', [
            'user_id' => $this->user->user_id,
            'name' => $_POST['name'],
            'websites_ids' => $websites_ids,
            'datetime' => \Altum\Date::$date,
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['team_id' => $team_id]);
    }

    private function update() {
        $_POST['team_id'] = (int) $_POST['team_id'];
        $_POST['name'] = trim(query_clean($_POST['name']));
        $websites_ids = [];

        /* Check for possible errors */
        if(empty($_POST['name']) || !isset($_POST['websites_ids'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        foreach($_POST['websites_ids'] as $website_id) {
            if(array_key_exists($website_id, $this->websites)) {
                $websites_ids[] = (int) $website_id;
            }
        }

        if(!count($websites_ids)) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $websites_ids = json_encode($websites_ids);

        /* Database query */
        db()->where('user_id', $this->user->user_id)->where('team_id', $_POST['team_id'])->update('teams', [
            'name' => $_POST['name'],
            'websites_ids' => $websites_ids,
            'last_datetime' => \Altum\Date::$date,
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.update1'), '<strong>' . filter_var($_POST['name']) . '</strong>'), 'success', ['team_id' => $_POST['team_id']]);
    }

    private function delete() {
        $_POST['team_id'] = (int) $_POST['team_id'];

        if(!$team = db()->where('team_id', $_POST['team_id'])->where('user_id', $this->user->user_id)->getOne('teams', ['team_id', 'name'])) {
            die();
        }

        /* Database query */
        db()->where('team_id', $team->team_id)->delete('teams');

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.delete1'), '<strong>' . $team->name . '</strong>'));
    }

}
