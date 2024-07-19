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
use Altum\Models\SessionsReplays;

class AdminReplays extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['website_id', 'user_id', 'is_offloaded'], [], ['events', 'expiration_date', 'last_datetime', 'datetime']));
        $filters->set_default_order_by('session_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `sessions_replays` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/replays?' . $filters->get_get() . '&page=%d')));

        /* Get the users */
        $replays = [];
        $replays_result = database()->query("
            SELECT
                `sessions_replays`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `sessions_replays`
            LEFT JOIN
                `users` ON `sessions_replays`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('sessions_replays')}
                {$filters->get_sql_order_by('sessions_replays')}
            
            {$paginator->get_sql_limit()}
        ");
        while($row = $replays_result->fetch_object()) {
            $row->duration = (new \DateTime($row->last_datetime))->getTimestamp() - (new \DateTime($row->datetime))->getTimestamp();
            $replays[] = $row;
        }

        /* Export handler */
        process_export_csv($replays, 'include', ['replay_id', 'session_id', 'visitor_id', 'website_id', 'user_id', 'events', 'is_offloaded', 'last_datetime', 'datetime', 'expiration_date'], sprintf(l('replays.title')));
        process_export_json($replays, 'include', ['replay_id', 'session_id', 'visitor_id', 'website_id', 'user_id', 'events', 'is_offloaded', 'last_datetime', 'datetime', 'expiration_date'], sprintf(l('replays.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'replays' => $replays,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/replays/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/replays');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/replays');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/replays');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $replay_id) {
                        (new SessionsReplays())->delete($replay_id);
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/replays');
    }

    public function delete() {

        $replay_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$replay = db()->where('replay_id', $replay_id)->has('sessions_replays')) {
            redirect('admin/replays');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new SessionsReplays())->delete($replay_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $replay->name . '</strong>'));

        }

        redirect('admin/replays');
    }

}
