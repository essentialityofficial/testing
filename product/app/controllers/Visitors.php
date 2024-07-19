<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\AnalyticsFilters;
use Altum\Models\Domain;

class Visitors extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!$this->website || ($this->website && $this->website->tracking_type == 'lightweight')) {
            redirect('websites');
        }

        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $datetime = \Altum\Date::get_start_end_dates_new($start_date, $end_date);

        /* Filters */
        $active_filters = AnalyticsFilters::get_filters('websites_visitors');
        $filters = AnalyticsFilters::get_filters_sql($active_filters);

        /* Average time per session */
        $average_time_per_session = database()->query("
            SELECT 
                AVG(`seconds`) AS `average` 
            FROM 
                (
                    SELECT 
                        TIMESTAMPDIFF(SECOND, MIN(`sessions_events`.`date`), MAX(`sessions_events`.`date`)) AS `seconds` 
                    FROM 
                        `sessions_events`
                    LEFT JOIN
                        `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE 
                        `sessions_events`.`website_id` = {$this->website->website_id} 
                        AND (`sessions_events`.`date` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                        AND {$filters}
                    GROUP BY `sessions_events`.`session_id`
                ) AS `seconds`
        ")->fetch_object()->average ?? 0;

        /* Prepare the paginator */
        $total_rows = database()->query("
            SELECT 
                COUNT(DISTINCT `visitors_sessions`.`visitor_id`) AS `total`
            FROM 
                `visitors_sessions`
            LEFT JOIN
                `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
            LEFT JOIN
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE 
                `visitors_sessions`.`website_id` = {$this->website->website_id} 
                AND (`visitors_sessions`.`date` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                AND {$filters}
        ")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, settings()->main->default_results_per_page, $_GET['page'] ?? 1, url('visitors?page=%d')));

        /* Determine the average sessions per user */
        $total_sessions = 0;

        /* Get the websites list for the user */
        $visitors = [];
        $visitors_result = database()->query("
            SELECT
                `websites_visitors`.*
            FROM
            	`visitors_sessions`
            LEFT JOIN
            	`websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			     `visitors_sessions`.`website_id` = {$this->website->website_id}
                AND (`visitors_sessions`.`date` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                AND {$filters}
			GROUP BY
				`visitor_id`
            ORDER BY
                `websites_visitors`.`last_date` DESC
            {$paginator->get_sql_limit()}
        ");
        while($row = $visitors_result->fetch_object()) {
            $visitors[] = $row;
            $total_sessions += $row->total_sessions;
        }

        $average_sessions_per_visitor = $total_sessions && count($visitors) ? $total_sessions / count($visitors) : 0;

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'datetime' => $datetime,
            'total_rows' => $total_rows,
            'average_time_per_session' => $average_time_per_session,
            'average_sessions_per_visitor' => $average_sessions_per_visitor,
            'pagination' => $pagination,
            'visitors' => $visitors
        ];

        $view = new \Altum\View('visitors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        if(!$this->website || ($this->website && $this->website->tracking_type == 'lightweight')) {
            redirect('websites');
        }

        if($this->team) {
            redirect('websites');
        }

        \Altum\Authentication::guard();

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('visitors');
        }

        if(empty($_POST['selected'])) {
            redirect('visitors');
        }

        if(!isset($_POST['type'])) {
            redirect('visitors');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $visitor_id) {
                        db()->where('visitor_id', $visitor_id)->where('website_id', $this->website->website_id)->delete('websites_visitors');
                    }

                    break;
            }

            /* Clear cache */
            cache()->deleteItem('website_visitors?website_id=' . $this->website->website_id);

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('visitors');
    }

    public function delete() {

        if(!$this->website || ($this->website && $this->website->tracking_type == 'lightweight')) {
            redirect('websites');
        }

        if($this->team) {
            redirect('websites');
        }

        \Altum\Authentication::guard();

        if(empty($_POST)) {
            redirect('visitors');
        }

        $visitor_id = (int) query_clean($_POST['visitor_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Database query */
            db()->where('visitor_id', $visitor_id)->where('website_id', $this->website->website_id)->delete('websites_visitors');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('visitors');
        }

        redirect('visitors');
    }

}
