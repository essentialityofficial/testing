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

class SessionAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $session_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get the Visitor basic data and make sure it exists */
        if(!$session = db()->where('session_id', $session_id)->where('website_id', $this->website->website_id)->getOne('visitors_sessions')) {
            die();
        }

        /* Get session events */
        $session_events_result = database()->query("SELECT * FROM `sessions_events` WHERE `session_id` = {$session->session_id} ORDER BY `event_id` ASC");

        $events = [];

        while($row = $session_events_result->fetch_object()) {
            $events[] = $row;
        }

        /* Get the child events */
        $session_events_children_result = database()->query("SELECT * FROM `events_children` WHERE `session_id` = {$session->session_id} ORDER BY `id` ASC");

        $events_children = [];

        while($row = $session_events_children_result->fetch_object()) {

            if(!isset($events_children[$row->event_id])) {
                $events_children[$row->event_id] = [];
            }

            $row->data = json_decode($row->data);

            $events_children[$row->event_id][] = $row;
        }

        /* Prepare the View */
        $data = [
            'session'           => $session,
            'events'            => $events,
            'events_children'   => $events_children
        ];

        $view = new \Altum\View('session/ajaxed_partials/events', (array) $this);

        Response::json('', 'success', ['html' => $view->run($data)]);

    }

}
