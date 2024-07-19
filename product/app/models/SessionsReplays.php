<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class SessionsReplays extends Model {

    public function delete($replay_id) {

        /* Database query */
        $replay = db()->where('replay_id', $replay_id)->getOne('sessions_replays');

        /* Clear cache */
        cache('store_adapter')->deleteItem('session_replay_' . $replay->session_id);

        /* Offload uploading */
        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url && $replay->is_offloaded) {
            $file_name = base64_encode($replay->session_id . $replay->date) . '.txt';

            try {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                /* Upload image */
                $s3_result = $s3->deleteObject([
                    'Bucket' => settings()->offload->storage_name,
                    'Key' => UPLOADS_URL_PATH . 'store/' . $file_name,
                ]);
            } catch (\Exception $exception) {
                dil($exception->getMessage());
            }
        }

        /* Database query */
        db()->where('replay_id', $replay_id)->delete('sessions_replays');

    }

}
