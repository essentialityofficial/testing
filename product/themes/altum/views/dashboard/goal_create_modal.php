<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="goal_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-circle-plus text-dark mr-2"></i>
                        <?= l('goal_create_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('goal_create_modal.subheader') ?></p>

                <form name="goal_create" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <select name="type" class="custom-select form-control-lg">
                            <option value="pageview"><?= l('goal_create_modal.input.type_pageview') ?></option>
                            <option value="custom"><?= l('goal_create_modal.input.type_custom') ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="goal_create_name"><i class="fas fa-fw fa-sm fa-signature text-gray-700 mr-1"></i> <?= l('global.name') ?></label>
                        <input id="goal_create_name" type="text" class="form-control" name="name" required="required" />
                    </div>

                    <div class="d-none" id="goal_create_type_pageview">

                        <div class="form-group">
                            <label for="goal_create_path"><i class="fas fa-fw fa-sm fa-link text-gray-700 mr-1"></i> <?= l('goal_create_modal.input.path') ?></label>
                            <div class="input-group">
                                <div id="path_prepend" class="input-group-prepend">
                                    <span class="input-group-text"><?= $this->website->host . $this->website->path . '/' ?></span>
                                </div>

                                <input id="goal_create_path" type="text" name="path" class="form-control" placeholder="<?= l('goal_create_modal.input.path_placeholder') ?>" />
                            </div>
                        </div>

                    </div>

                    <div class="d-none" id="goal_create_type_custom">

                        <div class="form-group">
                            <label for="goal_create_key"><i class="fas fa-fw fa-sm fa-fingerprint text-gray-700 mr-1"></i> <?= l('goal_create_modal.input.key') ?></label>
                            <input id="goal_create_key" type="text" class="form-control" name="key" value="<?= get_slug(string_generate(16)) ?>" placeholder="<?= l('goal_create_modal.input.key_placeholder') ?>" />
                        </div>

                        <div class="form-group">
                            <label for="goal_create_code"><i class="fas fa-fw fa-sm fa-code text-gray-700 mr-1"></i> <?= l('goal_create_modal.input.code') ?></label>
                            <input id="goal_create_code" type="text" class="form-control" name="code" value="" readonly="readonly" />
                            <small class="form-text text-muted"><?= l('goal_create_modal.input.code_help') ?></small>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.create') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>

    /* Tab switcher */
    let goal_create_input_type_handler = () => {
        let type = document.querySelector('#goal_create_modal select[name="type"]').value;

        switch(type) {
            case 'pageview':

                document.querySelector('#goal_create_type_pageview').classList.remove('d-none');
                document.querySelector('#goal_create_type_custom').classList.add('d-none');

            break;

            case 'custom':

                document.querySelector('#goal_create_type_pageview').classList.add('d-none');
                document.querySelector('#goal_create_type_custom').classList.remove('d-none');

            break;
        }

    }

    document.querySelector('#goal_create_modal select[name="type"]').addEventListener('change', goal_create_input_type_handler);

    goal_create_input_type_handler();


    let goal_create_update_code = () => {
        let key = get_slug($('#goal_create_modal input[name="key"]').val());

        let code = `<?= settings()->analytics->pixel_exposed_identifier ?>.goal('${key}')`;

        $('#goal_create_modal input[name="key"]').val(key);
        $('#goal_create_modal input[name="code"]').val(code);
    };

    $('#goal_create_modal input[name="key"]').on('change paste keyup', goal_create_update_code);

    goal_create_update_code();


    $('form[name="goal_create"]').on('submit', event => {
        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            url: `${url}goals-ajax/create`,
            data: $(event.currentTarget).serialize(),
            dataType: 'json',
            success: (data) => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    display_notifications(data.message, 'success', notification_container);

                    setTimeout(() => {

                        /* Hide modal */
                        $('#goal_create').modal('hide');

                        /* Clear input values */
                        $('form[name="goal_create"] input').val('');

                        /* Refresh */
                        redirect('dashboard/goals');

                        /* Remove the notification */
                        notification_container.innerHTML = '';

                    }, 1000);

                }
            },
            error: () => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

        event.preventDefault();

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
