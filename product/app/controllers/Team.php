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

class Team extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if($this->team) {
            redirect('teams');
        }

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get the Visitor basic data and make sure it exists */
        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }
        $team->websites_ids = json_decode($team->websites_ids);

        /* Create Modal */
        $view = new \Altum\View('team/team_association_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(['team' => $team]), 'modals');

        $view = new \Altum\View('team/team_association_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Get the team members */
        $teams_associations_result = database()->query("SELECT `teams_associations`.*, `users`.`email`, `users`.`name` FROM `teams_associations` LEFT JOIN `users` ON `users`.`user_id` = `teams_associations`.`user_id` WHERE `teams_associations`.`team_id` = {$team->team_id}");

        /* Prepare the View */
        $data = [
            'team'                      => $team,
            'teams_associations_result' => $teams_associations_result,
        ];

        $view = new \Altum\View('team/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('team.title'), $team->name));
    }

}
