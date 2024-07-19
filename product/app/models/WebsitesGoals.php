<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class WebsitesGoals extends Model {

    public function get_website_goals_by_website_id($website_id) {

        $cache_instance = cache()->getItem('website_goals?website_id=' . $website_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $result = database()->query("SELECT * FROM `websites_goals` WHERE `website_id` = {$website_id}");
            $data = [];

            while($row = $result->fetch_object()) {

                $data[] = $row;

            }

            cache()->save(
                $cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('website_id=' . $website_id)
            );

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

}
