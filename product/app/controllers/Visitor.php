<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class Visitor extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $visitor_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get the Visitor basic data and make sure it exists */
        if(!$visitor = db()->where('visitor_id', $visitor_id)->where('website_id', $this->website->website_id)->getOne('websites_visitors')) {
            redirect('visitors');
        }
        $datetime = \Altum\Date::get_start_end_dates_new();

        /* Get session data */
        $sessions_result = database()->query("
            SELECT
                `visitors_sessions`.*,
                `sessions_replays`.`session_id` AS `sessions_replays_session_id`,
                COUNT(DISTINCT  `sessions_events`.`event_id`) AS `pageviews`,
	       		MAX(`sessions_events`.`date`) AS `last_date`
            FROM
                `visitors_sessions`
            LEFT JOIN
            	`sessions_events` ON `sessions_events`.`session_id` = `visitors_sessions`.`session_id`
            LEFT JOIN
                `sessions_replays` ON `sessions_replays`.`session_id` = `visitors_sessions`.`session_id`
            WHERE
			     `visitors_sessions`.`website_id` = {$this->website->website_id}
			     AND `visitors_sessions`.`visitor_id` = {$visitor->visitor_id}
			     AND (`visitors_sessions`.`date` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
			GROUP BY
				`visitors_sessions`.`session_id`
			ORDER BY
				`visitors_sessions`.`session_id` DESC
        ");

        /* Average time per session */
        $average_time_per_session = database()->query("
            SELECT 
                   AVG(`seconds`) AS `average` 
            FROM 
                 (
                     SELECT 
                            TIMESTAMPDIFF(SECOND, MIN(date), MAX(date)) AS `seconds` 
                     FROM 
                          `sessions_events`
                     WHERE 
                           `website_id` = {$this->website->website_id}
                            AND `visitor_id` = {$visitor->visitor_id}
                     GROUP BY `session_id`
                 ) AS `seconds`
        ")->fetch_object()->average ?? 0;

        /* Session Events Modal */
        $view = new \Altum\View('session/session_events_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'datetime' => $datetime,
            'visitor' => $visitor,
            'average_time_per_session' => $average_time_per_session,
            'sessions_result' => $sessions_result
        ];

        $view = new \Altum\View('visitor/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
