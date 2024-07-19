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

class GoalsAjax extends Controller {

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

        $_POST['type'] = in_array($_POST['type'], ['pageview', 'custom']) ? query_clean($_POST['type']) : 'pageview';
        $_POST['name'] = trim(query_clean($_POST['name']));

        switch($_POST['type']) {
            case 'pageview':
                $_POST['path'] = '/' . trim(query_clean($_POST['path']));
                $_POST['key'] = string_generate(16);

                break;

            case 'custom':
                $_POST['key'] = empty(trim(get_slug(query_clean($_POST['key'])))) ? string_generate(16) : trim(get_slug(query_clean($_POST['key'])));
                $_POST['path'] = null;

                break;
        }


        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Get the count of already created goals */
        $total_websites_goals = database()->query("SELECT COUNT(*) AS `total` FROM `websites_goals` WHERE `website_id` = {$this->website->website_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->websites_goals_limit != -1 && $total_websites_goals >= $this->user->plan_settings->websites_goals_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Database query */
        db()->insert('websites_goals', [
            'website_id' => $this->website->website_id,
            'key' => $_POST['key'],
            'type' => $_POST['type'],
            'path' => $_POST['path'],
            'name' => $_POST['name'],
            'date' => Date::$date,
        ]);

        /* Clear cache */
        cache()->deleteItem('website_goals?website_id=' . $this->website->website_id);

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

        $_POST['goal_id'] = (int) $_POST['goal_id'];
        $_POST['name'] = trim(query_clean($_POST['name']));
        $_POST['type'] = in_array($_POST['type'], ['pageview', 'custom']) ? query_clean($_POST['type']) : 'pageview';

        switch($_POST['type']) {
            case 'pageview':
                $_POST['path'] = '/' . trim(query_clean($_POST['path']));
                $_POST['key'] = string_generate(16);

                break;

            case 'custom':
                $_POST['key'] = empty(trim(get_slug(query_clean($_POST['key'])))) ? string_generate(16) : trim(get_slug(query_clean($_POST['key'])));
                $_POST['path'] = null;

                break;
        }


        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Database query */
        db()->where('goal_id', $_POST['goal_id'])->where('website_id', $this->website->website_id)->update('websites_goals', [
            'key' => $_POST['key'],
            'path' => $_POST['path'],
            'name' => $_POST['name'],
        ]);

        /* Clear cache */
        cache()->deleteItem('website_goals?website_id=' . $this->website->website_id);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));
    }

    public function delete() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['goal_id'] = (int) $_POST['goal_id'];

        if(!$goal = db()->where('goal_id', $_POST['goal_id'])->where('website_id', $this->website->website_id)->getOne('websites_goals', ['goal_id', 'name'])) {
            die();
        }

        /* Database query */
        db()->where('goal_id', $_POST['goal_id'])->where('website_id', $this->website->website_id)->delete('websites_goals');

        /* Clear cache */
        cache()->deleteItem('website_goals?website_id=' . $this->website->website_id);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.delete1'), '<strong>' . $goal->name . '</strong>'));
    }
}
