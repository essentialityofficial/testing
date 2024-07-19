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

class AdminPlanUpdate extends Controller {

    public function index() {

        $plan_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Make sure it is either the trial / free plan or normal plans */
        switch($plan_id) {

            case 'free':
            case 'custom':

                /* Get the current settings for the free plan */
                $plan = settings()->{'plan_' . $plan_id};

                break;

            default:

                $plan_id = (int) $plan_id;

                /* Check if plan exists */
                if(!$plan = db()->where('plan_id', $plan_id)->getOne('plans')) {
                    redirect('admin/plans');
                }

                /* Parse the settings of the plan */
                $plan->settings = json_decode($plan->settings ?? '');
                $plan->translations = json_decode($plan->translations ?? '');
                $plan->prices = json_decode($plan->prices);

                /* Parse codes & taxes */
                $plan->taxes_ids = json_decode($plan->taxes_ids);

                if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
                    /* Get the available taxes from the system */
                    $taxes = db()->get('taxes');
                }

                break;

        }

        if(!empty($_POST)) {

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            /* Filter variables */
            $_POST['color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['color']) ? null : $_POST['color'];

            $_POST['settings'] = [
                'no_ads'                      => isset($_POST['no_ads']),
                'email_reports_is_enabled'    => isset($_POST['email_reports_is_enabled']),
                'teams_is_enabled'            => isset($_POST['teams_is_enabled']),
                'websites_limit'              => (int) $_POST['websites_limit'],
                'sessions_events_limit'       => (int) $_POST['sessions_events_limit'],
                'sessions_events_retention'   => $_POST['sessions_events_retention'] > 0 ? (int) $_POST['sessions_events_retention'] : 365,
                'events_children_limit'       => (int) $_POST['events_children_limit'],
                'events_children_retention'   => $_POST['events_children_retention'] > 0 ? (int) $_POST['events_children_retention'] : 30,
                'sessions_replays_limit'      => (int) $_POST['sessions_replays_limit'],
                'sessions_replays_retention'  => $_POST['sessions_replays_retention'] > 0 ? (int) $_POST['sessions_replays_retention'] : 30,
                'sessions_replays_time_limit' => $_POST['sessions_replays_time_limit'] >= 1 ? (int) $_POST['sessions_replays_time_limit'] : 10,
                'websites_heatmaps_limit'     => (int) $_POST['websites_heatmaps_limit'],
                'websites_goals_limit'        => (int) $_POST['websites_goals_limit'],
                'domains_limit'               => (int) $_POST['domains_limit'],
                'api_is_enabled' => isset($_POST['api_is_enabled']),
                'affiliate_commission_percentage'   => (int) $_POST['affiliate_commission_percentage'],
            ];

            switch($plan_id) {

                case 'free':

                    $_POST['name'] = input_clean($_POST['name']);
                    $_POST['description'] = input_clean($_POST['description']);
                    $_POST['price'] = input_clean($_POST['price']);
                    $_POST['status'] = (int) $_POST['status'];

                    /* Check for any errors */
                    $required_fields = ['name', 'price'];
                    foreach($required_fields as $field) {
                        if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                            Alerts::add_field_error($field, l('global.error_message.empty_field'));
                        }
                    }

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) settings()->payment->is_enabled ? database()->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` <> 0")->fetch_object()->total ?? 0 : 0;

                        if(!$enabled_plans) {
                            Alerts::add_error(l('admin_plan_update.error_message.disabled_plans'));
                        }
                    }

                    $setting_key = 'plan_free';
                    $setting_value = json_encode([
                        'plan_id' => 'free',
                        'name' => $_POST['name'],
                        'description' => $_POST['description'],
                        'translations' => $_POST['translations'],
                        'price' => $_POST['price'],
                        'color' => $_POST['color'],
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                case 'custom':

                    $_POST['name'] = input_clean($_POST['name']);
                    $_POST['description'] = input_clean($_POST['description']);
                    $_POST['price'] = input_clean($_POST['price']);
                    $_POST['custom_button_url'] = input_clean($_POST['custom_button_url']);
                    $_POST['status'] = (int) $_POST['status'];

                    /* Check for any errors */
                    $required_fields = ['name', 'price', 'custom_button_url'];
                    foreach($required_fields as $field) {
                        if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                            Alerts::add_field_error($field, l('global.error_message.empty_field'));
                        }
                    }

                    $setting_key = 'plan_custom';
                    $setting_value = json_encode([
                        'plan_id' => 'custom',
                        'name' => $_POST['name'],
                        'description' => $_POST['description'],
                        'translations' => $_POST['translations'],
                        'price' => $_POST['price'],
                        'custom_button_url' => $_POST['custom_button_url'],
                        'color' => $_POST['color'],
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                default:

                    $_POST['name'] = input_clean($_POST['name']);
                    $_POST['description'] = input_clean($_POST['description']);
                    $_POST['trial_days'] = (int) $_POST['trial_days'];
                    $_POST['status'] = (int) $_POST['status'];
                    $_POST['order'] = (int) $_POST['order'];
                    $_POST['taxes_ids'] = json_encode($_POST['taxes_ids'] ?? []);

                    /* Prices */
                    $prices = [
                        'monthly' => [],
                        'annual' => [],
                        'lifetime' => [],
                    ];

                    foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                        $prices['monthly'][$currency] = (float) $_POST['monthly_price'][$currency];
                        $prices['annual'][$currency] = (float) $_POST['annual_price'][$currency];
                        $prices['lifetime'][$currency] = (float) $_POST['lifetime_price'][$currency];
                    }

                    $prices = json_encode($prices);

                    /* Check for any errors */
                    $required_fields = ['name'];
                    foreach($required_fields as $field) {
                        if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                            Alerts::add_field_error($field, l('global.error_message.empty_field'));
                        }
                    }

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) database()->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` <> 0")->fetch_object()->total ?? 0;

                        if(
                            (
                                !$enabled_plans ||
                                ($enabled_plans == 1 && $plan->status))
                            && !settings()->plan_free->status
                        ) {
                            Alerts::add_error(l('admin_plan_update.error_message.disabled_plans'));
                        }
                    }

                    break;

            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Update the plan in database */
                switch ($plan_id) {

                    case 'free':
                    case 'custom':

                        db()->where('`key`', $setting_key)->update('settings', ['value' => $setting_value]);

                        /* Clear the cache */
                        cache()->deleteItem('settings');

                        break;

                    default:

                        $settings = json_encode($_POST['settings']);
                        $translations = json_encode($_POST['translations']);

                        db()->where('plan_id', $plan_id)->update('plans', [
                            'name' => $_POST['name'],
                            'description' => $_POST['description'],
                            'translations' => $translations,
                            'prices' => $prices,
                            'trial_days' => $_POST['trial_days'],
                            'settings' => $settings,
                            'taxes_ids' => $_POST['taxes_ids'],
                            'color' => $_POST['color'],
                            'status' => $_POST['status'],
                            'order' => $_POST['order'],
                        ]);

                        /* Clear the cache */
                        cache()->deleteItem('plans');

                        break;

                }

                /* Update all users plan settings with these ones */
                if(isset($_POST['submit_update_users_plan_settings'])) {

                    $plan_settings = json_encode($_POST['settings']);

                    db()->where('plan_id', $plan_id)->update('users', ['plan_settings' => $plan_settings]);

                    /* Clear the cache */
                    cache()->deleteItemsByTag('users');

                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $plan->name . '</strong>'));

                /* Refresh the page */
                redirect('admin/plan-update/' . $plan_id);

            }

        }

        /* Main View */
        $data = [
            'plan_id' => $plan_id,
            'plan' => $plan,
            'taxes' => $taxes ?? null,
        ];

        $view = new \Altum\View('admin/plan-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
