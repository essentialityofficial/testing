<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="team_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-trash-alt text-dark mr-2"></i>
                        <?= l('team_delete_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="team_delete" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="delete" />
                    <input type="hidden" name="team_id" value="" />

                    <div class="notification-container"></div>

                    <p class="text-muted"><?= l('team_delete_modal.subheader') ?></p>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-danger" data-is-ajax><?= l('global.delete') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#team_delete').on('show.bs.modal', event => {
        let team_id = $(event.relatedTarget).data('team-id');

        $(event.currentTarget).find('input[name="team_id"]').val(team_id);
    });

    $('form[name="team_delete"]').on('submit', event => {
        let team_id = $(event.currentTarget).find('input[name="team_id"]').val();
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            url: `${url}teams-ajax`,
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                let notification_container = event.currentTarget.querySelector('.notification-container');
                notification_container.innerHTML = '';
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Clear input values */
                    $(event.currentTarget).find('input[name="team_id"]').val('');

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {
                        /* Hide modal */
                        $('#team_delete').modal('hide');

                        /* Remove the html row */
                        redirect('teams');

                    }, 1000);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
