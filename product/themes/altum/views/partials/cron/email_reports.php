<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf(l('cron.email_reports.p1', $data->row->language), $data->row->host . $data->row->path) ?></p>

<div>
    <table>
        <tbody>
        <tr>
            <td></td>
            <td><strong><?= l('analytics.pageviews', $data->row->language) ?></strong></td>
            <td></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;">
                <?= \Altum\Date::get($data->previous_start_date, 5) . ' - ' . \Altum\Date::get($data->start_date, 5) ?>
            </td>

            <td>
                <span style="color: #808080 !important;">
                    <?= $data->previous_basic_analytics->pageviews ?>
                </span>
            </td>
            <td></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;">
                <?= \Altum\Date::get($data->start_date, 5) . ' - ' . \Altum\Date::get($data->date, 5) ?>
            </td>

            <td>
                <h3 style="margin-bottom: 0">
                    <?= $data->basic_analytics->pageviews ?>
                </h3>
            </td>
            <td style="vertical-align: middle;">
                <?php $percentage = get_percentage_change($data->previous_basic_analytics->pageviews, $data->basic_analytics->pageviews) ?>

                <?php if(round($percentage) != 0): ?>
                    <?= round($percentage) > 0 ? '<span style="color: #28a745 !important;">+' . round($percentage, 0) . '%</span>' : '<span style="color: #dc3545 !important;">' . round($percentage, 0) . '%</span>'; ?>
                <?php endif ?>
            </td>
        </tr>

        <?php if($data->row->tracking_type == 'normal'): ?>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td></td>
                <td><strong><?= l('analytics.sessions', $data->row->language) ?></strong></td>
                <td></td>
            </tr>

            <tr>
                <td style="vertical-align: middle;">
                    <?= \Altum\Date::get($data->previous_start_date, 5) . ' - ' . \Altum\Date::get($data->start_date, 5) ?>
                </td>

                <td>
                    <span style="color: #808080 !important;">
                        <?= $data->previous_basic_analytics->sessions ?>
                    </span>
                </td>

                <td></td>
            </tr>

            <tr>
                <td style="vertical-align: middle;">
                    <?= \Altum\Date::get($data->start_date, 5) . ' - ' . \Altum\Date::get($data->date, 5) ?>
                </td>

                <td>
                    <h3 style="margin-bottom: 0">
                        <?= $data->basic_analytics->sessions ?>
                    </h3>
                </td>

                <td style="vertical-align: middle;">
                    <?php $percentage = get_percentage_change($data->previous_basic_analytics->sessions, $data->basic_analytics->sessions) ?>

                    <?php if(round($percentage) != 0): ?>
                        <?= round($percentage) > 0 ? '<span style="color: #28a745 !important;">+' . round($percentage, 0) . '%</span>' : '<span style="color: #dc3545 !important;">' . round($percentage, 0) . '%</span>'; ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endif ?>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td></td>
            <td><strong><?= l('analytics.visitors', $data->row->language) ?></strong></td>
            <td></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;">
                <?= \Altum\Date::get($data->previous_start_date, 5) . ' - ' . \Altum\Date::get($data->start_date, 5) ?>
            </td>

            <td>
            <span style="color: #808080 !important;">
                <?= $data->previous_basic_analytics->visitors ?>
            </span>
            </td>
            <td></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;">
                <?= \Altum\Date::get($data->start_date, 5) . ' - ' . \Altum\Date::get($data->date, 5) ?>
            </td>

            <td>
                <h3 style="margin-bottom: 0">
                    <?= $data->basic_analytics->visitors ?>
                </h3>
            </td>
            <td style="vertical-align: middle;">
                <?php $percentage = get_percentage_change($data->previous_basic_analytics->visitors, $data->basic_analytics->visitors) ?>

                <?php if(round($percentage) != 0): ?>
                    <?= round($percentage) > 0 ? '<span style="color: #28a745 !important;">+' . round($percentage, 0) . '%</span>' : '<span style="color: #dc3545 !important;">' . round($percentage, 0) . '%</span>'; ?>
                <?php endif ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>


<div style="margin-top: 30px">
    <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>
                            <a href="<?= url('dashboard?website_id=' . $data->row->website_id) ?>">
                                <?= l('cron.email_reports.button', $data->row->language) ?>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<p style="text-align: center;">
    <small style="color: #808080 !important;"><?= sprintf(l('cron.email_reports.notice', $data->row->language), '<a href="' . url('websites') . '">', '</a>') ?></small>
</p>
