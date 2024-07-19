<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Domain extends Model {

    public function get_available_domains_by_user_id($user_id) {

        /* Get the domains */
        $domains = [];

        /* Try to check if the domain posts exists via the cache */
        $cache_instance = cache()->getItem('domains?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $domains_result = database()->query("
                SELECT 
                    *
                FROM 
                    `domains` 
                WHERE 
                    `user_id` = {$user_id}
                    AND `is_enabled` = 1
            ");
            while($row = $domains_result->fetch_object()) {
                $row->url = $row->scheme . $row->host . '/';
                $domains[$row->domain_id] = $row;
            }

            /* Properly tag the cache */
            $cache_instance->set($domains)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('domains?user_id=' . $user_id);

            foreach($domains as $domain) {
                $cache_instance->addTag('domain_id=' . $domain->domain_id);
            }

            cache()->save($cache_instance);

        } else {

            /* Get cache */
            $domains = $cache_instance->get();

        }

        return $domains;

    }

    public function get_domain_by_domain_id($domain_id) {

        /* Get the domain */
        $domain = null;

        /* Try to check if the domain posts exists via the cache */
        $cache_instance = cache()->getItem('domain?domain_id=' . $domain_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $domain = db()->where('domain_id', $domain_id)->getOne('domains');

            if($domain) {
                /* Build the url */
                $domain->url = $domain->scheme . $domain->host . '/';

                cache()->save(
                    $cache_instance->set($domain)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('domain_id=' . $domain->domain_id)
                );
            }

        } else {

            /* Get cache */
            $domain = $cache_instance->get();

        }

        return $domain;

    }

    public function get_domain_by_host($host) {

        /* Get the domain */
        $domain = null;

        /* Try to check if the domain posts exists via the cache */
        $cache_instance = cache()->getItem('domain?host=' . md5($host));

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $domain = db()->where('host', $host)->getOne('domains');

            if($domain) {
                /* Build the url */
                $domain->url = $domain->scheme . $domain->host . '/';

                cache()->save(
                    $cache_instance->set($domain)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('domain_id=' . $domain->domain_id)->addTag('domains?user_id=' . $domain->user_id)
                );
            }

        } else {

            /* Get cache */
            $domain = $cache_instance->get();

        }

        return $domain;

    }

    public function delete($domain_id) {

        /* Delete the domain */
        db()->where('domain_id', $domain_id)->delete('domains');

        /* Clear the cache */
        cache()->deleteItemsByTag('domain_id=' . $domain_id);

    }
}
