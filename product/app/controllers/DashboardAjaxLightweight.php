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

class DashboardAjaxLightweight extends Controller {
    public $date;
    public $request_type;
    public $by = null;
    public $limit = null;

    public function index() {
        \Altum\Authentication::guard();

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

        if(
            \Altum\Csrf::check('global_token') &&
            isset($_GET['request_type']) &&
            method_exists($this, $_GET['request_type'])
        ) {
            $this->request_type = $_GET['request_type'];
            $this->limit = $_GET['limit'] == -1 ? 5000 : (int) $_GET['limit'];

            /* Date  */
            $start_date = isset($_GET['start_date']) ? query_clean($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
            $end_date = isset($_GET['end_date']) ? query_clean($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

            $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date);

            /* Check if realtime request */
            if(isset($_GET['request_subtype']) && $_GET['request_subtype'] == 'realtime' && $start_date == 'now' && $end_date == 'now') {
                $start_date = (new \DateTime())->modify('-5 minute')->format('Y-m-d H:i:s');
                $end_date = (new \DateTime())->format('Y-m-d H:i:s');

                $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date , \Altum\Date::$default_timezone, \Altum\Date::$default_timezone);
            }

            /* Run the proper method */
            $this->{$this->request_type}();

        }

        die();
    }

    private function countries() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `country_code`,
                COUNT(IFNULL(`country_code`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') 
            GROUP BY
                `country_code`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function cities() {
        /* Search by country_code */
        $_GET['country_code'] = query_clean($_GET['country_code']);

        /* Get the data */
        $result = database()->query("
            SELECT
                `city_name`,
                COUNT(IFNULL(`city_name`, 1)) AS `total`
            FROM
            	`lightweight_events`
			WHERE
			    `website_id` = {$this->website->website_id} 
			    AND `country_code` = '{$_GET['country_code']}'
			    AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
           GROUP BY
                `city_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function realtime_countries() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `country_code`,
                COUNT(IFNULL(`country_code`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') 
            GROUP BY
                `country_code`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'countries';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function operating_systems() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `os_name`,
                COUNT(`os_name`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') 
                 AND `os_name` IS NOT NULL
            GROUP BY
                `os_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function screen_resolutions() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `screen_resolution`,
                COUNT(`screen_resolution`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') 
                 AND `screen_resolution` IS NOT NULL
            GROUP BY
                `screen_resolution`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function browser_languages() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `browser_language`,
                COUNT(`browser_language`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `browser_language` IS NOT NULL 
            GROUP BY
                `browser_language`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function device_types() {

        /* Get the data */
        $result = database()->query("
           SELECT
                `device_type`,
                COUNT(`device_type`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `device_type` IS NOT NULL
            GROUP BY
                `device_type`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function realtime_device_types() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `device_type`,
                COUNT(`device_type`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `device_type` IS NOT NULL 
            GROUP BY
                `device_type`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'device_types';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function browser_names() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `browser_name`,
                COUNT(`browser_name`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `browser_name` IS NOT NULL 
            GROUP BY
                `browser_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function paths() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `path`,
                COUNT(IFNULL(`path`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') 
            GROUP BY
                `path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function landing_paths() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `path`,
                COUNT(IFNULL(`path`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `type` = 'landing_page' 
            GROUP BY
                `path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function realtime_paths() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `path`,
                COUNT(IFNULL(`path`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            GROUP BY
                `path`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'paths';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function referrers() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `referrer_host`,
                COUNT(IFNULL(`referrer_host`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            GROUP BY
                `referrer_host`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function referrer_paths() {
        $_GET['referrer_host'] = query_clean($_GET['referrer_host']);

        /* Get the data */
        $result = database()->query("
            SELECT
                `referrer_path`,
                `referrer_host`,
                COUNT(IFNULL(`referrer_path`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `referrer_host` = '{$_GET['referrer_host']}'
            GROUP BY
                `referrer_path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function social_media_referrers() {

        /* Get the data */
        $result = database()->query("
            SELECT
                CASE
                    WHEN `referrer_host` = 'l.facebook.com' then 'facebook.com'
                    WHEN `referrer_host` = 'l.instagram.com' then 'instagram.com'
                    WHEN `referrer_host` LIKE '%.pinterest.com' then 'pinterest.com'
                    WHEN `referrer_host` = 't.co' then 'twitter.com'
                    WHEN `referrer_host` = 'www.youtube.com' then 'youtube.com'
                END AS `referrer`,
                COUNT(IFNULL(`referrer_host`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND (
			        `referrer_host` IN ('l.facebook.com', 't.co', 'www.pinterest.com', 'l.instagram.com', 'www.youtube.com') 
			        OR `referrer_host` LIKE '%.pinterest.com'
			    )
            GROUP BY
                `referrer`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function search_engines_referrers() {

        /* Get the data */
        $result = database()->query("
            SELECT
                CASE
                    WHEN `referrer_host` = 'www.bing.com' then 'bing.com'
                    WHEN `referrer_host` = 'ecosia.org' then 'ecosia.org'
                    WHEN `referrer_host` LIKE 'www.google.%' then 'google.com'
                    WHEN `referrer_host` LIKE '%.yahoo.com' then 'yahoo.com'
                    WHEN `referrer_host` = 'yandex.com' then 'yandex.com'
                END AS `referrer`,
                COUNT(IFNULL(`referrer_host`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND (
			        `referrer_host` IN ('www.bing.com', 'ecosia.org', 'yandex.com') 
			        OR `referrer_host` LIKE 'www.google.%' 
			        OR `referrer_host` LIKE '%.yahoo.com'
			    )
            GROUP BY
                `referrer`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function utms_source() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `utm_source` AS `utm`,
                COUNT(`utm_source`) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                 AND `utm_source` IS NOT NULL
            GROUP BY
                `utm_source`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'utms';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function utms_medium_campaign() {
        $_GET['utm_source'] = query_clean($_GET['utm_source']);

        /* Get the data */
        $result = database()->query("
            SELECT
                `utm_medium`,
                `utm_campaign`,
                COUNT(IFNULL(`utm_medium`, 1)) AS `total`
            FROM
                `lightweight_events`
            WHERE
                `website_id` = {$this->website->website_id}
                 AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND `utm_source` = '{$_GET['utm_source']}'
            GROUP BY
                `utm_medium`,
                `utm_campaign`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    /* Goals */
    private function goals() {

        /* Get the data */
        $result = database()->query("
            SELECT
                `websites_goals`.`goal_id`,
                `websites_goals`.`key`,
                `websites_goals`.`type`,
                `websites_goals`.`path`,
                `websites_goals`.`name`,
                (
                	SELECT 
                		COUNT(*) 
                	FROM 
                		`goals_conversions`
            		WHERE
            			`goals_conversions`.`goal_id` = `websites_goals`.`goal_id`
                		AND `goals_conversions`.`website_id` = {$this->website->website_id} 
                		AND (`goals_conversions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                ) AS `total`
            FROM
                `websites_goals`
            WHERE
                `websites_goals`.`website_id` = {$this->website->website_id}  
            ORDER BY 
                `total` DESC;
        ");

        $this->by = 'conversions';

        $this->process_and_run($result);
    }

    /* Realtime specific requests */
    private function realtime_visitors() {
        $visitors = database()->query("
            SELECT 
                COUNT(*) AS `total`
            FROM 
                `lightweight_events` 
            WHERE
                `website_id` = {$this->website->website_id} 
			    AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            ORDER BY
                `date` DESC
        ")->fetch_object()->total ?? 0;

        Response::json('', 'success', ['html' => $visitors]);
    }

    private function realtime_chart_data() {
        $logs_chart = [];

        $result = database()->query("
            SELECT 
                COUNT(*) AS `pageviews`, 
                `date`
            FROM 
                `lightweight_events`
            WHERE 
                `website_id` = {$this->website->website_id} 
			    AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            GROUP BY
                `date`
        ");

        /* Generate the raw chart data and save logs for later usage */
        while($row = $result->fetch_object()) {

            /* Insert data for the chart */
            $formatted_date = Date::get($row->date, 'H:i');

            if(isset($logs_chart[$formatted_date])) {
                $logs_chart[$formatted_date] = [
                    'pageviews' => $logs_chart[$formatted_date]['pageviews'] + $row->pageviews
                ];
            } else {
                $logs_chart[$formatted_date] = [
                    'pageviews' => $row->pageviews
                ];
            }

        }

        $logs_chart = get_chart_data($logs_chart);

        Response::json('', 'success', ['logs_chart_labels' => $logs_chart['labels'] ?? [], 'logs_chart_pageviews' => $logs_chart['pageviews'] ?? 0]);
    }

    private function process($result) {
        /* Go over the result */
        $rows = [];
        $total_sum = 0;
        $total_rows = 0;
        $options = [];

        while($row = $result->fetch_object()) {
            $total_rows++;

            if(!$this->limit || ($this->limit && $total_rows <= $this->limit)) {
                $rows[] = $row;

                $total_sum += $row->total;
            }

        }

        /* Check for options in displayment */
        $options['bounce_rate'] = false;

        return [
            'by'        => $this->by,
            'rows'      => $rows,
            'options'   => $options,
            'total_sum' => $total_sum,
            'total_rows'=> $total_rows
        ];
    }

    private function process_and_run($result) {

        /* Prepare the View */
        $data = $this->process($result);

        $view = new \Altum\View('dashboard/ajaxed_partials/' . $this->request_type, (array) $this);

        Response::json('', 'success', ['html' => $view->run($data), 'data' => json_encode($data)]);

    }

}
