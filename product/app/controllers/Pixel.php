<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\User;


class Pixel extends Controller {

    public function index() {
        $seconds_to_cache = settings()->analytics->pixel_cache;
        header('Content-Type: application/javascript');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds_to_cache) . ' GMT');
        header('Pragma: cache');
        header('Cache-Control: max-age=' . $seconds_to_cache);

        /* Clean the pixel key */
        $pixel_key = isset($this->params[0]) ? input_clean($this->params[0]) : null;

        /* Get the details of the website from the database */
        $website = (new \Altum\Models\Website())->get_website_by_pixel_key($pixel_key);

        if(!$website) {
            die(json_encode(l('pixel_track.error_message.no_website')));
        }

        if(!$website->is_enabled) {
            die('1');
        }

        /* Check against bots */
        if($website->bot_exclusion_is_enabled) {
            $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

            if($CrawlerDetect->isCrawler()) {
                die(json_encode(l('pixel_track.error_message.excluded_bot')));
            }
        }

        /* Make sure to get the user data and confirm the user is ok */
        $user = (new \Altum\Models\User())->get_user_by_user_id($website->user_id);

        if(!$user) {
            die('2');
        }

        if($user->status != 1) {
            die('3');
        }

        /* Check for a custom domain */
        if(isset(\Altum\Router::$data['domain']) && $website->domain_id != \Altum\Router::$data['domain']->domain_id) {
            die('4');
        }

        /* Process the plan of the user */
        (new User())->process_user_plan_expiration_by_user($user);

        /* Make sure that the user didn't exceed the current plan */
        if($user->plan_settings->sessions_events_limit != -1 && $website->current_month_sessions_events >= $user->plan_settings->sessions_events_limit) {
            die(json_encode(l('pixel_track.error_message.plan_limit')));
        }

        $pixel_track_events_children = (bool) $website->events_children_is_enabled && ($user->plan_settings->events_children_limit == -1 || $website->current_month_events_children < $user->plan_settings->events_children_limit);
        $pixel_track_sessions_replays = (bool) settings()->analytics->sessions_replays_is_enabled && $website->sessions_replays_is_enabled && ($user->plan_settings->sessions_replays_limit == -1 || $website->current_month_sessions_replays < $user->plan_settings->sessions_replays_limit);

        /* Get heatmaps if any and if the user has rights */
        $pixel_heatmaps = [];

        if($website->tracking_type == 'normal' && $user->plan_settings->websites_heatmaps_limit != 0) {
            $pixel_heatmaps = (new \Altum\Models\WebsitesHeatmaps())->get_website_heatmaps_by_website_id($website->website_id);

            foreach($pixel_heatmaps as $key => $pixel_heatmap) {
                /* Make sure the heatmap is active */
                if(!$pixel_heatmap->is_enabled) {
                    unset($pixel_heatmaps[$key]);
                    continue;
                }

                /* Generate the full url needed to match for the heatmap */
                $pixel_heatmap->url = $website->host . $website->path . $pixel_heatmap->path;
            }
        }

        /* Get available goals for the website */
        if($user->plan_settings->websites_goals_limit != 0) {
            $pixel_goals = (new \Altum\Models\WebsitesGoals())->get_website_goals_by_website_id($website->website_id);

            foreach($pixel_goals as $pixel_goal) {
                /* Generate the full url needed to match */
                $pixel_goal->url = $website->host . $website->path . $pixel_goal->path;
            }
        }

        /* Main View */
        $data = [];

        switch($website->tracking_type) {
            case 'lightweight':

                /* :) */

                break;

            case 'normal':
                $data = [
                    'pixel_heatmaps'                => $pixel_heatmaps,
                    'pixel_track_events_children'   => $pixel_track_events_children,
                    'pixel_track_sessions_replays'  => $pixel_track_sessions_replays
                ];

                break;
        }

        $data['pixel_key'] = $pixel_key;
        $data['pixel_goals'] = $pixel_goals ?? [];
        $data['pixel_query_parameters_tracking_is_enabled'] = $website->query_parameters_tracking_is_enabled;

        $view = new \Altum\View('pixel/' . $website->tracking_type . '/pixel', (array) $this);

        echo $view->run($data);

    }
}
