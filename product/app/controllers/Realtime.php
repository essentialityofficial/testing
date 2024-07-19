<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class Realtime extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!$this->website) {
            redirect('websites');
        }

        /* Prepare the View */
        $data = [];

        $view = new \Altum\View('realtime/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
