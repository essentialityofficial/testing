<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="session_events_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="p-3">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title"><?= l('session_events_modal.header') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('session_events_modal.subheader') ?></p>
            </div>

            <div class="modal-body">
                <div class="notification-container"></div>

                <div id="session_events_result"></div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#session_events_modal').on('show.bs.modal', event => {
        let session_id = $(event.relatedTarget).data('session-id');
        let loading_html = $('#loading').html();

        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';

        /* Place the loading html */
        $('#session_events_result').html(loading_html);

        $.ajax({
            type: 'GET',
            url: `${url}session-ajax/${session_id}`,
            dataType: 'json',
            success: (data) => {
                if(data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    $('#session_events_result').html(data.details.html);

                }
            },
            error: () => {
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
