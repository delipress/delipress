<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<div class="delipress__wizard__slider">
    <div class="delipress__wizard__slider__head">
        <div>
            <h2><?php esc_html_e("Get started", "delipress"); ?></h2>
            <p><?php echo sprintf(esc_html__("DeliPress uses %s to synchronize your subscribers and send your campaigns. We need you to connect an account to continue.", "delipress"), "Mailjet"); ?></p>
        </div>
        <div>
            <h2><?php esc_html_e("Get your API Key", "delipress"); ?></h2>
            <p>
                <?php echo sprintf(esc_html__("Now simply connect to your %s account and copy and paste the API and secret key here.", "delipress"), "Mailjet"); ?> <br>
                <a class="delipress__button delipress__button--link" href="https://app.mailjet.com/account/api_keys" target="_blank">
                    <?php esc_html_e("Dashboard > Account > API KEYS", 'delipress'); ?>
                    <span class="dashicons dashicons-external"></span>
                </a>
            </p>
        </div>
    </div>

    <div class="delipress__wizard__slider__content">
        <div class="delipress__wizard__slide">
            <p class="delipress__center">
                <a href="#" class="delipress__button delipress__button--save js-wizard-slide-next">
                    <?php echo sprintf(esc_html__("I have a %s account", "delipress"), 'Mailjet'); ?>
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
                <a href="<?php echo $provider["url_register"]; ?>" class="delipress__button delipress__button--main js-wizard-slide-next" target="_blank">
                    <?php echo sprintf(esc_html__("Create a %s account", "delipress"), 'Mailjet'); ?>
                    <span class="dashicons dashicons-external"></span>
                </a>
            </p>
        </div>
        <div class="delipress__wizard__slide">
            <div class="delipress__settings">

                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="client_id"><?php esc_html_e('API Key', 'delipress'); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <input id="client_id" name="client_id" type="text" class="delipress__input" value="">
                    </div>
                </div>

                <div class="delipress__settings__item delipress__flex">
                    <div class="delipress__settings__item__label delipress__f1">
                        <label for="client_secret"><?php esc_html_e('Secret Key', 'delipress'); ?></label>
                    </div>
                    <div class="delipress__settings__item__field delipress__f4">
                        <input id="client_secret" name="client_secret" type="text" class="delipress__input" value="">
                    </div>
                </div>

            </div>

            <p class="delipress__center"><button type="submit" class="delipress__button delipress__button--save"><?php esc_html_e("Validate Keys", "delipress"); ?></button>
                <small> <a href="#" class="js-wizard-slide-prev"><?php esc_html_e('Go back', 'delipress'); ?></a></small>
            </p>
        </div>

    </div>
</div>
