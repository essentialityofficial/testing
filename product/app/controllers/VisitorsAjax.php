<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;

class VisitorsAjax extends Controller {

    public function index() {
        die();
    }

    public function delete() {

        if(empty($_POST) || (!\Altum\Csrf::check() && !\Altum\Csrf::check('global_token'))) {
            die();
        }

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        $_POST['visitor_id'] = (int) $_POST['visitor_id'];

        /* Database query */
        db()->where('visitor_id', $_POST['visitor_id'])->where('website_id', $this->website->website_id)->delete('websites_visitors');

        /* Set a nice success message */
        Response::json(l('global.success_message.delete2'));

    }
}
