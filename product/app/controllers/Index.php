<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


use Altum\Meta;

class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty(settings()->main->index_url)) {
            header('Location: ' . settings()->main->index_url); die();
        }

        /* Check if the cache exists */
        $cache_instance = cache()->getItem('index_stats');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $total_websites = database()->query("SELECT MAX(`website_id`) AS `total` FROM `websites`")->fetch_object()->total ?? 0;
            $total_lightweight_pageviews = database()->query("SELECT MAX(`event_id`) AS `total` FROM `lightweight_events`")->fetch_object()->total ?? 0;
            $total_normal_pageviews = database()->query("SELECT MAX(`event_id`) AS `total` FROM `sessions_events`")->fetch_object()->total ?? 0;
            $total_pageviews = $total_lightweight_pageviews + $total_normal_pageviews;

            $stats = [
                'total_websites' => $total_websites,
                'total_pageviews' => $total_pageviews,
            ];

            /* Save to cache */
            cache()->save($cache_instance->set($stats)->expiresAfter(3600));

        } else {

            /* Get cache */
            $stats = $cache_instance->get();
            extract($stats);

        }

        /* Plans View */
        $data = [];

        $view = new \Altum\View('partials/plans', (array) $this);

        $this->add_view_content('plans', $view->run($data));

        /* Meta */
        Meta::set_canonical_url();

        /* Opengraph image */
        if(settings()->main->opengraph) {
            Meta::set_social_url(SITE_URL);
            Meta::set_social_description(l('index.meta_description'));
            Meta::set_social_image(\Altum\Uploads::get_full_url('opengraph') . settings()->main->opengraph);
        }

        if(settings()->main->display_index_latest_blog_posts) {
            $language = \Altum\Language::$name;

            /* Blog posts query */
            $blog_posts_result_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 
                ORDER BY `blog_post_id` DESC
                LIMIT 3
            ";

            $blog_posts = \Altum\Cache::cache_function_result('blog_posts?hash=' . md5($blog_posts_result_query), 'blog_posts', function() use ($blog_posts_result_query) {
                $blog_posts_result = database()->query($blog_posts_result_query);

                /* Iterate over the blog posts */
                $blog_posts = [];

                while($row = $blog_posts_result->fetch_object()) {
                    /* Transform content if needed */
                    $row->content = json_decode($row->content) ? convert_editorjs_json_to_html($row->content) : nl2br($row->content);

                    $blog_posts[] = $row;
                }

                return $blog_posts;
            });
        }

        /* Main View */
        $data = [
            'total_websites' => $total_websites,
            'total_pageviews' => $total_pageviews,
            'blog_posts' => $blog_posts ?? [],
        ];

        $view = new \Altum\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
