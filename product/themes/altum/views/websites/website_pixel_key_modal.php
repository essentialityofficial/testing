<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="website_pixel_key_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-code text-dark mr-2"></i>
                        <?= l('website_pixel_key_modal.install.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('website_pixel_key_modal.install.subheader') ?></p>

                <pre id="pixel_key_html" class="pre-custom rounded"></pre>

                <div class="mt-4">
                    <button type="button" class="btn btn-lg btn-block btn-primary" data-clipboard-target="#pixel_key_html" data-copied="<?= l('website_pixel_key_modal.install.copied') ?>"><?= l('website_pixel_key_modal.install.copy') ?></button>
                </div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL ?>js/libraries/clipboard.min.js?v=<?= PRODUCT_CODE ?>"></script>

<script>
    /* On modal show */
    $('#website_pixel_key_modal').on('show.bs.modal', event => {
        let pixel_key = $(event.relatedTarget).data('pixel-key');
        let base_url = $(event.relatedTarget).data('base-url');

        let pixel_key_html = `&lt;!-- Pixel Code - ${base_url} --&gt;
&lt;script defer src="${base_url}pixel/${pixel_key}"&gt;&lt;/script&gt;
&lt;!-- END Pixel Code --&gt;`;

        $(event.currentTarget).find('pre').html(pixel_key_html);

        new ClipboardJS('[data-clipboard-target]');

        /* Handle on click button */
        let copy_button = $(event.currentTarget).find('[data-clipboard-target]');
        let initial_text = copy_button.text();

        copy_button.on('click', () => {

            copy_button.text(copy_button.data('copied'));

            setTimeout(() => {
                copy_button.text(initial_text);
            }, 2500);
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
