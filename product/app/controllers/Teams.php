<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class Teams extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Create Modal */
        $view = new \Altum\View('teams/team_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Modal */
        $view = new \Altum\View('teams/team_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\View('teams/team_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\View('teams/team_association_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Get the teams list for of the owner user */
        $teams_result = database()->query("SELECT `teams`.*, COUNT(`teams_associations`.`team_association_id`) AS `users` FROM `teams` LEFT JOIN `teams_associations` ON `teams_associations`.`team_id` = `teams`.`team_id` WHERE `teams`.`user_id` = {$this->user->user_id} GROUP BY `teams`.`team_id`");

        /* Get the teams that the current user is enrolled into */
        $teams_associations_result = database()->query("SELECT `teams`.`team_id`, `teams`.`name`, `teams`.`websites_ids`, `teams_associations`.* FROM `teams_associations` LEFT JOIN `teams` ON `teams_associations`.`team_id` = `teams`.`team_id` WHERE `teams_associations`.`user_id` = {$this->user->user_id} OR `teams_associations`.`user_email` = '{$this->user->email}'");

        /* Prepare the View */
        $data = [
            'teams_result'              => $teams_result,
            'teams_associations_result' => $teams_associations_result,
        ];

        $view = new \Altum\View('teams/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
