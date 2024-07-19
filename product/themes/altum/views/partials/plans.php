<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->payment->is_enabled): ?>

    <?php
    $plans = [];
    $available_payment_frequencies = [];

    $plans = (new \Altum\Models\Plan())->get_plans();

    foreach($plans as $plan) {
        if($plan->status != 1) continue;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->prices->{$value}->{currency()}) {
                $available_payment_frequencies[$value] = true;
            }
        }
    }
    ?>

    <?php if(count($plans)): ?>
        <div class="mb-5 text-center">
            <div class="btn-group btn-group-toggle btn-group-custom" data-toggle="buttons">

                <?php if(isset($available_payment_frequencies['monthly'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'monthly' ? 'active' : null ?>" data-payment-frequency="monthly">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'monthly' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.monthly') ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['annual'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'annual' ? 'active' : null ?>" data-payment-frequency="annual">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'annual' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.annual') ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['lifetime'])): ?>
                    <label class="btn <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'active' : null ?>" data-payment-frequency="lifetime">
                        <input type="radio" name="payment_frequency" <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'checked="checked"' : null ?>> <?= l('plan.custom_plan.lifetime') ?>
                    </label>
                <?php endif ?>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>


<div class="pricing pricing-palden d-flex flex-wrap justify-content-center">

    <?php if(settings()->plan_free->status == 1): ?>

        <div class="pricing-item">
            <div class="pricing-deco" style="<?= settings()->plan_free->color ? 'background-color: ' . settings()->plan_free->color : null ?>">
                <div class="mb-5 d-flex align-items-center justify-content-center">
                    <h3 class="pricing-title"><?= settings()->plan_free->translations->{\Altum\Language::$name}->name ?: settings()->plan_free->name ?></h3>
                </div>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer-1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" opacity="0.6"></path>
                    <path class="deco-layer deco-layer-2" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z"></path>
                </svg>

                <div class="pricing-price">
                    <?= settings()->plan_free->translations->{\Altum\Language::$name}->price ?: settings()->plan_free->price ?>
                </div>

                <div class="pricing-sub"><?= settings()->plan_free->translations->{\Altum\Language::$name}->description ?: settings()->plan_free->description ?></div>
            </div>

            <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_free->settings]) ?>

            <a href="<?= url('register') ?>" class="btn btn-primary btn-lg rounded-pill pricing-action <?= \Altum\Authentication::check() && $this->user->plan_id != 'free' ? 'disabled' : null ?>"><?= l('plan.button.choose') ?></a>
        </div>

    <?php endif ?>

    <?php if(settings()->payment->is_enabled): ?>

        <?php foreach($plans as $plan): ?>
        <?php if($plan->status != 1) continue; ?>

        <?php $annual_price_savings = ceil(($plan->prices->monthly->{currency()} * 12) - $plan->prices->annual->{currency()}); ?>

        <div
                class="pricing-item"
                data-plan-monthly="<?= json_encode((bool) $plan->prices->monthly->{currency()}) ?>"
                data-plan-annual="<?= json_encode((bool) $plan->prices->annual->{currency()}) ?>"
                data-plan-lifetime="<?= json_encode((bool) $plan->prices->lifetime->{currency()}) ?>"
        >
            <div class="pricing-deco" style="<?= $plan->color ? 'background-color: ' . $plan->color : null ?>">
                <div class="mb-5 d-flex align-items-center justify-content-center">
                    <h3 class="pricing-title"><?= $plan->translations->{\Altum\Language::$name}->name ?: $plan->name ?></h3>

                    <?php if($plan->prices->monthly->{currency()} && $annual_price_savings > 0): ?>
                        <span class="badge badge-success mx-1 d-none" data-plan-payment-frequency="annual" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.annual_price_savings'), $annual_price_savings . ' ' . currency()) ?>">
                            <i class="fas fa-fw fa-sm fa-percentage"></i>
                        </span>
                    <?php endif ?>
                </div>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer-1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" opacity="0.6"></path>
                    <path class="deco-layer deco-layer-2" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z"></path>
                </svg>

                <div class="pricing-price">
                    <span class="d-none" data-plan-payment-frequency="monthly"><?= nr($plan->prices->monthly->{currency()}, 2, false) ?></span>
                    <span class="d-none" data-plan-payment-frequency="annual"><?= nr($plan->prices->annual->{currency()}, 2, false) ?></span>
                    <span class="d-none" data-plan-payment-frequency="lifetime"><?= nr($plan->prices->lifetime->{currency()}, 2, false) ?></span>
                    <span class="pricing-currency"><?= currency() ?></span>
                </div>

                <div class="pricing-sub">
                    <span><?= $plan->translations->{\Altum\Language::$name}->description ?: $plan->description ?></span>
                </div>
            </div>

            <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => $plan->settings]) ?>

            <a href="<?= url('register?redirect=pay/' . $plan->plan_id) ?>" class="btn btn-primary btn-lg rounded-pill pricing-action">
                <?php if(\Altum\Authentication::check()): ?>
                    <?php if(!$this->user->plan_trial_done && $plan->trial_days): ?>
                        <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                    <?php elseif($this->user->plan_id == $plan->plan_id): ?>
                        <?= l('plan.button.renew') ?>
                    <?php else: ?>
                        <?= l('plan.button.choose') ?>
                    <?php endif ?>
                <?php else: ?>
                    <?php if($plan->trial_days): ?>
                        <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                    <?php else: ?>
                        <?= l('plan.button.choose') ?>
                    <?php endif ?>
                <?php endif ?>
            </a>
        </div>

    <?php endforeach ?>

    <?php ob_start() ?>
        <script>
            'use strict';

            let payment_frequency_handler = (event = null) => {

                let payment_frequency = null;

                if(event) {
                    payment_frequency = $(event.currentTarget).data('payment-frequency');
                } else {
                    payment_frequency = $('[name="payment_frequency"]:checked').closest('label').data('payment-frequency');
                }

                switch(payment_frequency) {
                    case 'monthly':
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break;

                    case 'annual':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break

                    case 'lifetime':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');

                        break
                }

                $(`[data-plan-payment-frequency="${payment_frequency}"]`).addClass('d-inline-block');

                $(`[data-plan-${payment_frequency}="true"]`).removeClass('d-none').addClass('');
                $(`[data-plan-${payment_frequency}="false"]`).addClass('d-none').removeClass('');

            };

            $('[data-payment-frequency]').on('click', payment_frequency_handler);

            payment_frequency_handler();
        </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php if(settings()->plan_custom->status == 1): ?>
        <div class="pricing-item">
            <div class="pricing-deco" style="<?= settings()->plan_custom->color ? 'background-color: ' . settings()->plan_custom->color : null ?>">
                <div class="mb-5 d-flex align-items-center justify-content-center">
                    <h3 class="pricing-title"><?= settings()->plan_custom->translations->{\Altum\Language::$name}->name ?: settings()->plan_custom->name ?></h3>
                </div>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer-1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" opacity="0.6"></path>
                    <path class="deco-layer deco-layer-2" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z"></path>
                </svg>

                <div class="pricing-price">
                    <?= settings()->plan_custom->translations->{\Altum\Language::$name}->price ?: settings()->plan_custom->price ?>
                </div>

                <div class="pricing-sub"><?= settings()->plan_custom->translations->{\Altum\Language::$name}->description ?: settings()->plan_custom->description ?></div>
            </div>

            <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_custom->settings]) ?>

            <a href="<?= settings()->plan_custom->custom_button_url ?>" class="btn btn-primary btn-lg rounded-pill pricing-action"><?= l('plan.button.contact') ?></a>
        </div>
    <?php endif ?>

    <?php endif ?>
</div>
