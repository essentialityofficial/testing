<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Title;

class AdminStatistics extends Controller {
    public $type;
    public $datetime;

    public function index() {

        $this->type = isset($this->params[0]) && method_exists($this, $this->params[0]) ? input_clean($this->params[0]) : 'growth';

        $this->datetime = \Altum\Date::get_start_end_dates_new();

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Set a custom title */
        $dynamic_title = l('admin_statistics.' . $this->type . '.header', null, true) ?? l('admin_' . $this->type . '.title');
        Title::set(sprintf(l('admin_statistics.title'), $dynamic_title));

        /* Main View */
        $data = [
            'type' => $this->type,
            'datetime' => $this->datetime
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function database() {
        //ALTUMCODE:DEMO if(DEMO) { \Altum\Alerts::add_error('This command is blocked on the demo.'); redirect('admin/statistics'); };

        /* Database details */
        $database_name = DATABASE_NAME;
        $tables = [];
        $result = database()->query("
            SELECT
                TABLE_NAME AS `table`,
                ROUND((DATA_LENGTH + INDEX_LENGTH)) AS `bytes`,
                TABLE_ROWS as 'rows'
            FROM
                information_schema.TABLES
            WHERE
                TABLE_SCHEMA = '{$database_name}'
            ORDER BY
                (DATA_LENGTH + INDEX_LENGTH)
            DESC;
        ");
        while($row = $result->fetch_object()) {

            $tables[] = $row;

        }

        return [
            'tables' => $tables,
        ];
    }

    protected function growth() {

        $total = ['users' => 0, 'users_logs' => 0];

        /* Users */
        $users_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];

            $total['users'] += $row->total;
        }

        $users_chart = get_chart_data($users_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(DISTINCT `user_id`) AS `total`,
                 DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];

            $total['users_logs'] += $row->total;
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        return [
            'total' => $total,
            'users_chart' => $users_chart,
            'users_logs_chart' => $users_logs_chart,
        ];
    }

    protected function users() {

        $total = ['continents' => 0, 'countries' => 0, 'sources' => 0, 'plans' => 0];

        /* Continents */
        $continents = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `continent_code`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `continent_code`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $continents[$row->continent_code] = $row->total;
            $total['continents'] += $row->total;
        }

        /* Countries */
        $countries_map = [];
        $countries = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `country`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `country`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $countries[$row->country] = $row->total;
            $countries_map[$row->country] = ['users' => $row->total];
            $total['countries'] += $row->total;
        }

        /* Sources */
        $sources = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `source`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `source`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $sources[$row->source] = $row->total;
            $total['sources'] += $row->total;
        }

        /* Plans */
        $plans = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `plan_id`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `plan_id`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $plans[$row->plan_id] = $row->total;
            $total['plans'] += $row->total;
        }

        return [
            'continents' => $continents,
            'countries' => $countries,
            'countries_map' => $countries_map,
            'sources' => $sources,
            'plans' => $plans,
            'total' => $total,
        ];
    }

    protected function payments() {

        $total = ['total_amount' => 0, 'total_payments' => 0];

        $payments_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount` FROM `payments` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

            $total['total_amount'] += $row->total_amount;
            $total['total_payments'] += $row->total_payments;
        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'total' => $total,
            'payments_chart' => $payments_chart
        ];

    }

    protected function redeemed_codes() {

        $total = ['discount_codes' => 0, 'redeemable_codes' => 0];

        $chart = [];
        $result = database()->query("SELECT `type`, COUNT(`type`) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `redeemed_codes` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`, `type`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            if(isset($chart[$row->formatted_date])) {
                $chart[$row->formatted_date] = [
                    'discount' => $row->type == 'discount' ? $chart[$row->formatted_date]['discount'] + $row->total : $chart[$row->formatted_date]['discount'],
                    'redeemable' => $row->type == 'redeemable' ? $chart[$row->formatted_date]['redeemable'] + $row->total : $chart[$row->formatted_date]['redeemable'],
                ];
            } else {
                $chart[$row->formatted_date] = [
                    'discount' => $row->type == 'discount' ? $row->total : 0,
                    'redeemable' => $row->type == 'redeemable' ? $row->total : 0,
                ];
            }

            $total['discount_codes'] += $row->type == 'discount' ? $row->total : 0;
            $total['redeemable_codes'] += $row->type == 'redeemable' ? $row->total : 0;
        }

        $chart = get_chart_data($chart);

        return [
            'total' => $total,
            'chart' => $chart,
        ];

    }

    protected function affiliates_commissions() {

        $total = ['amount' => 0, 'total_affiliates_commissions' => 0];

        $affiliates_commissions_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_commissions`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_commissions` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $affiliates_commissions_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_commissions' => $row->total_affiliates_commissions
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_commissions'] += $row->total_affiliates_commissions;
        }

        $affiliates_commissions_chart = get_chart_data($affiliates_commissions_chart);

        return [
            'total' => $total,
            'affiliates_commissions_chart' => $affiliates_commissions_chart
        ];

    }

    protected function affiliates_withdrawals() {

        $total = ['amount' => 0, 'total_affiliates_withdrawals' => 0];

        $affiliates_withdrawals_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_withdrawals`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_withdrawals` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $affiliates_withdrawals_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_withdrawals' => $row->total_affiliates_withdrawals
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_withdrawals'] += $row->total_affiliates_withdrawals;
        }

        $affiliates_withdrawals_chart = get_chart_data($affiliates_withdrawals_chart);

        return [
            'total' => $total,
            'affiliates_withdrawals_chart' => $affiliates_withdrawals_chart
        ];

    }

    protected function broadcasts() {

        $total = ['broadcasts' => 0, 'sent_emails' => 0];

        $broadcasts_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`sent_emails`) AS `sent_emails` FROM `broadcasts` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $broadcasts_chart[$row->formatted_date] = [
                'broadcasts' => $row->total,
                'sent_emails' => $row->sent_emails,
            ];

            $total['broadcasts'] += $row->total;
            $total['sent_emails'] += $row->sent_emails;
        }

        $broadcasts_chart = get_chart_data($broadcasts_chart);

        return [
            'total' => $total,
            'broadcasts_chart' => $broadcasts_chart,
        ];

    }

    protected function internal_notifications() {

        $total = ['internal_notifications' => 0, 'read_notifications' => 0];

        $internal_notifications_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`is_read`) AS `read_notifications` FROM `internal_notifications` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $internal_notifications_chart[$row->formatted_date] = [
                'internal_notifications' => $row->total,
                'read_notifications' => $row->read_notifications,
            ];

            $total['internal_notifications'] += $row->total;
            $total['read_notifications'] += $row->read_notifications;
        }

        $internal_notifications_chart = get_chart_data($internal_notifications_chart);

        return [
            'total' => $total,
            'internal_notifications_chart' => $internal_notifications_chart,
        ];

    }

    protected function push_notifications() {

        $total = ['push_notifications' => 0, 'sent_push_notifications' => 0];

        $push_notifications_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`sent_push_notifications`) AS `sent_push_notifications` FROM `push_notifications` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $push_notifications_chart[$row->formatted_date] = [
                'push_notifications' => $row->total,
                'sent_push_notifications' => $row->sent_push_notifications,
            ];

            $total['push_notifications'] += $row->total;
            $total['sent_push_notifications'] += $row->sent_push_notifications;
        }

        $push_notifications_chart = get_chart_data($push_notifications_chart);

        return [
            'total' => $total,
            'push_notifications_chart' => $push_notifications_chart,
        ];

    }

    protected function push_subscribers() {

        $total = ['push_subscribers' => 0];

        $push_subscribers_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `push_subscribers` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $push_subscribers_chart[$row->formatted_date] = [
                'push_subscribers' => $row->total,
            ];

            $total['push_subscribers'] += $row->total;
        }

        $push_subscribers_chart = get_chart_data($push_subscribers_chart);

        return [
            'total' => $total,
            'push_subscribers_chart' => $push_subscribers_chart,
        ];

    }

    protected function websites() {

        $total = ['websites' => 0];

        /* Monitors */
        $websites_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `websites`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $websites_chart[$row->formatted_date] = [
                'websites' => $row->total
            ];

            $total['websites'] += $row->total;
        }

        $websites_chart = get_chart_data($websites_chart);

        return [
            'total' => $total,
            'websites_chart' => $websites_chart,
        ];

    }

    protected function lightweight_events() {

        $total = ['lightweight_events' => 0];

        /* Monitors */
        $lightweight_events_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `lightweight_events`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $lightweight_events_chart[$row->formatted_date] = [
                'lightweight_events' => $row->total
            ];

            $total['lightweight_events'] += $row->total;
        }

        $lightweight_events_chart = get_chart_data($lightweight_events_chart);

        return [
            'total' => $total,
            'lightweight_events_chart' => $lightweight_events_chart,
        ];

    }

    protected function sessions_events() {

        $total = ['sessions_events' => 0];

        /* Monitors */
        $sessions_events_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `sessions_events`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $sessions_events_chart[$row->formatted_date] = [
                'sessions_events' => $row->total
            ];

            $total['sessions_events'] += $row->total;
        }

        $sessions_events_chart = get_chart_data($sessions_events_chart);

        return [
            'total' => $total,
            'sessions_events_chart' => $sessions_events_chart,
        ];

    }

    protected function events_children() {

        $total = ['click' => 0, 'form' => 0, 'scroll' => 0, 'resize' => 0];

        /* Track conversions */
        $events_children_chart = [];
        $result = database()->query("    
            SELECT
                 `type`,
                 COUNT(`id`) AS `total`,
                 DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `events_children`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $events_children_chart)) {
                $events_children_chart[$row->formatted_date] = [
                    'click' => 0,
                    'form' => 0,
                    'scroll' => 0,
                    'resize' => 0,
                ];
            }

            $events_children_chart[$row->formatted_date][$row->type] = $row->total;

            $total[$row->type] += $row->total;
        }

        $events_children_chart = get_chart_data($events_children_chart);


        return [
            'total' => $total,
            'events_children_chart' => $events_children_chart
        ];
    }

    protected function sessions_replays() {

        $total = ['sessions_replays' => 0];

        /* Monitors */
        $sessions_replays_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `sessions_replays`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $sessions_replays_chart[$row->formatted_date] = [
                'sessions_replays' => $row->total
            ];

            $total['sessions_replays'] += $row->total;
        }

        $sessions_replays_chart = get_chart_data($sessions_replays_chart);

        return [
            'total' => $total,
            'sessions_replays_chart' => $sessions_replays_chart,
        ];

    }

    protected function websites_heatmaps() {

        $total = ['websites_heatmaps' => 0];

        /* Monitors */
        $websites_heatmaps_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `websites_heatmaps`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $websites_heatmaps_chart[$row->formatted_date] = [
                'websites_heatmaps' => $row->total
            ];

            $total['websites_heatmaps'] += $row->total;
        }

        $websites_heatmaps_chart = get_chart_data($websites_heatmaps_chart);

        return [
            'total' => $total,
            'websites_heatmaps_chart' => $websites_heatmaps_chart,
        ];

    }

    protected function websites_goals() {

        $total = ['websites_goals' => 0];

        /* Monitors */
        $websites_goals_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `websites_goals`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $websites_goals_chart[$row->formatted_date] = [
                'websites_goals' => $row->total
            ];

            $total['websites_goals'] += $row->total;
        }

        $websites_goals_chart = get_chart_data($websites_goals_chart);

        return [
            'total' => $total,
            'websites_goals_chart' => $websites_goals_chart,
        ];

    }

    protected function goals_conversions() {

        $total = ['goals_conversions' => 0];

        /* Monitors */
        $goals_conversions_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `goals_conversions`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $goals_conversions_chart[$row->formatted_date] = [
                'goals_conversions' => $row->total
            ];

            $total['goals_conversions'] += $row->total;
        }

        $goals_conversions_chart = get_chart_data($goals_conversions_chart);

        return [
            'total' => $total,
            'goals_conversions_chart' => $goals_conversions_chart,
        ];

    }

    protected function teams() {

        $total = ['teams' => 0];

        /* Monitors */
        $teams_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `teams`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $teams_chart[$row->formatted_date] = [
                'teams' => $row->total
            ];

            $total['teams'] += $row->total;
        }

        $teams_chart = get_chart_data($teams_chart);

        return [
            'total' => $total,
            'teams_chart' => $teams_chart,
        ];

    }

    protected function email_reports() {

        $total = ['email_reports' => 0];

        $email_reports_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `email_reports`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $email_reports_chart[$row->formatted_date] = [
                'email_reports' => $row->total
            ];

            $total['email_reports'] += $row->total;
        }

        $email_reports_chart = get_chart_data($email_reports_chart);

        return [
            'total' => $total,
            'email_reports_chart' => $email_reports_chart
        ];
    }

    protected function domains() {

        $total = ['domains' => 0];

        $domains_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `domains` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $domains_chart[$row->formatted_date] = [
                'domains' => $row->total,
            ];

            $total['domains'] += $row->total;
        }

        $domains_chart = get_chart_data($domains_chart);

        return [
            'total' => $total,
            'domains_chart' => $domains_chart,
        ];

    }

}
