<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

use Altum\Models\Plan;
use Altum\Models\User;
use Altum\Models\Website;


class App {

    public function __construct() {

        /* Connect to the database */
        //\Altum\Database::initialize();

        /* Initialize caching system */
        Cache::initialize();

        /* Initiate the plugin system */
        Plugin::initialize();

        /* Initiate the Language system */
        Language::initialize();

        /* Parse the URL parameters */
        \Altum\Router::parse_url();

        /* Parse the potential language url */
        \Altum\Router::parse_language();

        /* Handle the controller */
        \Altum\Router::parse_controller();

        /* Create a new instance of the controller */
        $controller = \Altum\Router::get_controller(\Altum\Router::$controller, \Altum\Router::$path);

        /* Process the method and get it */
        $method = \Altum\Router::parse_method($controller);

        /* Get the remaining params */
        $params = \Altum\Router::get_params();

        /* Check for Preflight requests for the tracking pixel */
        if(\Altum\Router::$controller == 'PixelTrack') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            header('Access-Control-Max-Age: 7200');

            /* Check if preflight request */
            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') die();
        }

        if(in_array(\Altum\Router::$controller, ['Cron', 'PixelTrack', 'Replay', 'Replays', 'WebsitesAjax', 'AdminWebsites', 'AdminUsers', 'AccountDelete', 'ApiWebsites'])) {
            /* Cache store must be enabled in situations when dealing with  */
            Cache::store_initialize();
        }

        /* Initiate the Language system with the default language */
        Language::set_default_by_name(settings()->main->default_language);

        /* Set the default theme style */
        ThemeStyle::set_default(settings()->main->default_theme_style);

        /* Initiate the Title system */
        Title::initialize(settings()->main->title);
        Meta::initialize();

        /* Set the date timezone */
        date_default_timezone_set(Date::$default_timezone);
        Date::$timezone = date_default_timezone_get();

        /* Setting the datetime for backend usages ( insertions in database..etc ) */
        Date::$date = Date::get();

        /* Affiliate check */
        Affiliate::initiate();

        /* Check for a potential logged in account and do some extra checks */
        if(\Altum\Authentication::check()) {

            $user = \Altum\Authentication::$user;

            if(!$user) {
                \Altum\Authentication::logout();
            }

            /* Determine if the current plan is expired or disabled */
            $user->plan_is_expired = false;

            /* Get current plan proper details */
            $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

            if(!$user->plan || ($user->plan && ((new \DateTime()) > (new \DateTime($user->plan_expiration_date)) && $user->plan_id != 'free') || !$user->plan->status)) {
                $user->plan_is_expired = true;

                /* Switch the user to the default plan */
                db()->where('user_id', $user->user_id)->update('users', [
                    'plan_id' => 'free',
                    'plan_settings' => json_encode(settings()->plan_free->settings),
                    'payment_subscription_id' => ''
                ]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' .  \Altum\Authentication::$user_id);
            }

            /* Update last activity */
            if(!$user->last_activity || (new \DateTime($user->last_activity))->modify('+15 minutes') < (new \DateTime())) {
                (new User())->update_last_activity(\Altum\Authentication::$user_id);
            }

            if(!isset($_COOKIE['set_language'])) {
                /* Update the language of the site for next page use if the current language (default) is different than the one the user has */
                if(Language::$name != $user->language) {
                    /* Make sure the language of the user still exists & is active */
                    if(array_key_exists($user->language, Language::$active_languages)) {
                        Language::set_by_name($user->language);
                    } else {
                        db()->where('user_id', \Altum\Authentication::$user_id)->update('users', ['language' => Language::$default_name]);

                        /* Clear the cache */
                        cache()->deleteItemsByTag('user_id=' . \Altum\Authentication::$user_id);
                    }
                }
            }

            /* Update the language of the user if needed */
            if(isset($_COOKIE['set_language']) && array_key_exists($_COOKIE['set_language'], Language::$active_languages) && Language::$name != $user->language) {
                db()->where('user_id', \Altum\Authentication::$user_id)->update('users', ['language' => $_COOKIE['set_language']]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . \Altum\Authentication::$user_id);

                /* Remove cookie */
                setcookie('set_language', '', time()-30, COOKIE_PATH);

                /* Set the language */
                Language::set_by_name($_COOKIE['set_language']);
            }

            /* Update the currency of the user if needed */
            if(isset($_COOKIE['set_currency']) && array_key_exists($_COOKIE['set_currency'], (array) settings()->payment->currencies) && $_COOKIE['set_currency'] != $user->currency) {
                db()->where('user_id', \Altum\Authentication::$user_id)->update('users', ['currency' => $_COOKIE['set_currency']]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . \Altum\Authentication::$user_id);

                /* Remove cookie */
                setcookie('set_currency', '', time()-30, COOKIE_PATH);

                /* Set the currency */
                \Altum\Currency::$currency = $_COOKIE['set_currency'];
            }

            /* Set the timezone to be used for displaying */
            Date::$timezone = $user->timezone;

            /* Store all the details of the user in the Authentication static class as well */
            \Altum\Authentication::$user = $user;

            /* Check if team login */
            $team = null;

            if(isset($_COOKIE['selected_team_id'])) {
                $_COOKIE['selected_team_id'] = (int) $_COOKIE['selected_team_id'];

                $team = database()->query("SELECT `teams`.* FROM `teams` LEFT JOIN `teams_associations` ON `teams_associations`.`team_id` = `teams`.`team_id` WHERE `teams`.`team_id` = {$_COOKIE['selected_team_id']} AND `teams_associations`.`user_id` = {$user->user_id}")->fetch_object() ?? null;

                if($team) {
                    $team->websites_ids = json_decode($team->websites_ids);
                }
            }

            /* Extra if needed */
            if($team) {
                $websites = (new Website())->get_websites_by_websites_ids($team->websites_ids);
            } else {
                $websites = (new Website())->get_websites_by_user_id(\Altum\Authentication::$user->user_id);
            }

            /* Detect which is the default shown website */
            $website = !empty($_COOKIE['selected_website_id']) && array_key_exists($_COOKIE['selected_website_id'], $websites) ? $websites[$_COOKIE['selected_website_id']] : reset($websites);

            /* Add the data to the main controller */
            $controller->add_params([
                'websites' => $websites,
                'website' => $website,
                'team' => $team
            ]);

            /* Make sure to redirect the person to the payment page and only let the person access the following pages */
            if(
                $user->plan_is_expired
                && !in_array(\Altum\Router::$controller_key, ['index', 'blog', 'affiliate', 'contact', 'page', 'pages', 'plan', 'pay', 'pay-billing', 'pay-thank-you', 'account', 'account-plan', 'account-payments', 'invoice', 'account-logs', 'account-preferences',  'account-delete', 'referrals', 'account-api', 'account-redeem-code', 'logout', 'register', 'teams-system', 'teams-member', 'teams-members', 'teams', 'team', 'teams-ajax', 'teams-associations-ajax', 'register'])
                && \Altum\Router::$path != 'admin'
                && (\Altum\Router::$controller_settings['wrapper'] == 'app_wrapper' && !$team)
            )
            {
                redirect('plan/new');
            }
        }

        /* Set a CSRF Token */
        Csrf::set('token');
        Csrf::set('global_token');

        /* If the language code is the default one, redirect to index */
        if(\Altum\Router::$language_code == Language::$default_code) {
            redirect(\Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null));
        }

        /* Redirect based on browser language if needed */
        $browser_language_code = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        if(settings()->main->auto_language_detection_is_enabled && \Altum\Router::$controller_settings['no_browser_language_detection'] == false && !\Altum\Router::$language_code && !\Altum\Authentication::check() && $browser_language_code && Language::$default_code != $browser_language_code && array_search($browser_language_code, Language::$active_languages)) {
            if(!isset($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'])['host'] != parse_url(SITE_URL)['host'])) {
                header('Location: ' . SITE_URL . $browser_language_code . '/' . \Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null));
            }
        }

        /* Force HTTPS is needed */
        if(settings()->main->force_https_is_enabled && ($_SERVER['HTTPS'] ?? '') != 'on' && php_sapi_name() != 'cli' && string_starts_with('https://', SITE_URL)) {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301); die();
        }

        /* Add main vars inside of the controller */
        $controller->add_params([
            /* Extra params available from the URL */
            'params' => $params,

            /* Potential logged in user */
            'user' => \Altum\Authentication::$user
        ]);

        /* Check for authentication checks */
        if(!is_null(\Altum\Router::$controller_settings['authentication'])) {
            \Altum\Authentication::guard(\Altum\Router::$controller_settings['authentication']);
        }

        /* Call the controller method */
        call_user_func_array([ $controller, $method ], []);

        /* Render and output everything */
        $controller->run();

        /* Close database */
        Database::close();
    }

}
