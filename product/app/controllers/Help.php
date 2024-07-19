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

class Help extends Controller {

    public function index() {

        $page = isset($this->params[0]) ? query_clean(get_slug($this->params[0],'_')) : 'introduction';
        $page = preg_replace('/' . '-' . '+/', '_', $page);

        /* Check if page exists */
        if(!file_exists(THEME_PATH . 'views/help/' . $page . '.php')) {
            redirect('help');
        }

        $view = new \Altum\View('help/' . $page, (array) $this);
        $this->add_view_content('page', $view->run());

        /* Set a custom title */
        Title::set(sprintf(l('help.title'), l('help.' . $page . '.title')));

        /* Prepare the View */
        $data = [
            'page' => $page
        ];

        $view = new \Altum\View('help/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
