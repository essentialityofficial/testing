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

class TeamsAssociationsAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Make sure its not a request from a team member */
        if($this->team) {
            die();
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(!empty($_POST) && (\Altum\Csrf::check() || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        $_POST['team_id'] = (int) $_POST['team_id'];
        $_POST['email'] = trim(query_clean($_POST['email']));

        /* Check for possible errors */
        if(empty($_POST['email'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        if(!$team = db()->where('team_id', $_POST['team_id'])->getOne('teams')) {
            die();
        }

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            Response::json(l('global.error_message.invalid_email'), 'error');
        }

        if(db()->where('team_id', $_POST['team_id'])->where('user_email', $_POST['email'])->getOne('teams_associations', ['team_association_id'])) {
            Response::json(l('team_association_create_modal.error_message.email_exists'), 'error');
        }

        /* Check if the email exists as an user */
        $user_exists = db()->where('email', $_POST['email'])->getOne('users', ['user_id']);

        /* Database query */
        db()->insert('teams_associations', [
            'team_id' => $_POST['team_id'],
            'user_email' => $_POST['email'],
            'date' => Date::$date
        ]);

        /* Send out an email notification */
        /* Prepare the email */
        $email_template = get_email_template(
            [
                '{{TEAM_NAME}}' => $team->name,
            ],
            l('global.emails.teams_association_create.subject'),
            [
                '{{TEAM_NAME}}' => $team->name,
                '{{USER_NAME}}' => str_replace('.', '. ', $this->user->name),
                '{{USER_EMAIL}}' => $this->user->email,
                '{{LOGIN_LINK}}' => url('login?redirect=teams&email=' . $_POST['email']),
                '{{REGISTER_LINK}}' => url('register?redirect=teams&email=' . $_POST['email']) . '&unique_registration_identifier=' . md5($_POST['email'] . $_POST['email']),
            ],
            $user_exists ? l('global.emails.teams_association_create.body_login') : l('global.emails.teams_association_create.body_register'));

        send_mail($_POST['email'], $email_template->subject, $email_template->body);

        Response::json(l('team_association_create_modal.success_message'), 'success', ['team_id' => $team->team_id]);
    }

    /* Accepting the invitation of the team association */
    private function update() {
        $_POST['team_association_id'] = (int) $_POST['team_association_id'];

        if(!$team_association = db()->where('team_association_id', $_POST['team_association_id'])->getOne('teams_associations')) {
            die();
        }

        /* Make sure the invitation is not yet accepted and that it belongs to the actual logged in user */
        if($team_association->is_accepted || $team_association->user_email != $this->user->email) {
            die();
        }

        /* Update the database */
        db()->where('team_association_id', $team_association->team_association_id)->update('teams_associations', [
            'user_id' => $this->user->user_id,
            'is_accepted' => 1,
            'accepted_date' => Date::$date
        ]);

        Response::json('', 'success');
    }

    /* Team Association Delete */
    private function delete() {
        $_POST['team_association_id'] = (int) $_POST['team_association_id'];

        if(!$team_association = db()->where('team_association_id', $_POST['team_association_id'])->getOne('teams_associations')) {
            die();
        }

        /* Check if the user is the owner, so that he can remove anyone from the team */
        if(!db()->where('team_id', $team_association->team_id)->where('user_id', $this->user->user_id)->getOne('teams', ['team_id'])) {

            if(
                (
                    $team_association->is_accepted &&
                    $team_association->user_id != $this->user->user_id
                )

                ||

                (
                    !$team_association->is_accepted &&
                    $team_association->user_email != $this->user->email
                )
            ) {
                die();
            }

        }

        /* Database query */
        db()->where('team_association_id', $_POST['team_association_id'])->delete('teams_associations');

        Response::json(l('team_association_delete_modal.success_message'), 'success');

    }

}
