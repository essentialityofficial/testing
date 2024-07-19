<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="team_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-pen text-dark mr-2"></i>
                        <?= l('team_update_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="team_update" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="update" />
                    <input type="hidden" name="team_id" value="" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label><i class="fas fa-sm fa-fw fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                        <input type="text" class="form-control" name="name" required="required" />
                    </div>

                    <div class="form-group">
                        <label><?= l('team_create_modal.input.websites_ids') ?></label>
                        <select multiple="multiple" name="websites_ids[]" class="custom-select form-control-lg">
                            <?php foreach($this->websites as $key => $value) echo '<option value="' . $key . '">' . $value->name . ' - ' . $value->host . $value->path . '</option>' ?>
                        </select>
                        <small class="form-text text-muted"><?= l('team_create_modal.input.websites_ids_help') ?></small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    /* On modal show load new data */
    $('#team_update').on('show.bs.modal', event => {
        let team_id = $(event.relatedTarget).data('team-id');
        let name = $(event.relatedTarget).data('name');
        let websites_ids = $(event.relatedTarget).data('websites-ids');

        $(event.currentTarget).find('input[name="team_id"]').val(team_id);
        $(event.currentTarget).find('input[name="name"]').val(name);
        $(event.currentTarget).find(`select[name="websites_ids\[\]"] option`).removeAttr('selected');
        for(let website_id of websites_ids) {
            $(event.currentTarget).find(`select[name="websites_ids\[\]"] option[value="${website_id}"]`).attr('selected', 'selected');
        }
    });

    $('form[name="team_update"]').on('submit', event => {
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

                    /* Hide modal */
                    $('#team_update').modal('hide');

                    /* Clear input values */
                    $('form[name="team_update"] input').val('');

                    /* Refresh */
                    redirect(`teams`);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
