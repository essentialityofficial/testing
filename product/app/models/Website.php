<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Website extends Model {

    public function get_website_by_pixel_key($pixel_key) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = cache()->getItem('website?pixel_key=' . $pixel_key);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('pixel_key', $pixel_key)->getOne('websites');

            if($data) {
                /* Save to cache */
                cache()->save(
                    $cache_instance->set($data)->expiresAfter(43200)->addTag('users')->addTag('user_id=' . $data->user_id)->addTag('website_id=' . $data->website_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_websites_by_user_id($user_id) {

        $cache_instance = cache()->getItem('websites_' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $result = database()->query("SELECT * FROM `websites` WHERE `user_id` = {$user_id}");
            $data = [];

            while($row = $result->fetch_object()) {

                $data[$row->website_id] = $row;

            }

            cache()->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS));

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_websites_by_websites_ids(array $websites_ids = []) {

        $websites_ids_query = implode(',', $websites_ids);
        $result = database()->query("SELECT * FROM `websites` WHERE `website_id` IN ({$websites_ids_query}) ");
        $data = [];

        while($row = $result->fetch_object()) {

            $data[$row->website_id] = $row;

        }

        return $data;
    }
}
