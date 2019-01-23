<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptionHelper;

$this->currentTab = "subscribers";
$disabledForm = apply_filters(DELIPRESS_SLUG . "_disabled_form_options", false);


wp_enqueue_media();

?>

<h1><?php _e("Subscriptions", "delipress"); ?></h1>

<form action="<?php echo $this->optionServices->getUrlFormAdminPost($this->currentTab); ?>" method="post" id="form_page">

    <?php settings_fields( OptionHelper::OPTIONS_GROUP ); ?>

    <h2><?php _e("Options", "delipress"); ?></h2>

    <div class="delipress__settings">
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label for="delipress_double_optin"><?php _e('Double Opt-In', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    type="checkbox"
                    id="delipress_double_optin"
                    class="delipress__checkbox__input"
                    name="<?php echo sprintf("%s[%s][double_optin]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>"
                    value="1"
                    <?php checked( $this->options[$this->currentTab]["double_optin"], 1 ); ?>
                />
                <label for="delipress_double_optin" class="delipress__checkbox"><?php _e('Enable double Opt-In', 'delipress'); ?></label>
            </div>
            <div class="delipress__settings__item__help delipress__f4">
                <?php _e("This option is mandatory in some countries", "delipress"); ?>
            </div>
        </div>

        <?php if(!$disabledForm): ?>
            <button type="submit" class="delipress__button delipress__button--save"><?php _e('Save settings', 'delipress'); ?></button>
        <?php else: ?>
            <div class="delipress__button delipress__button--save delipress__button--demo-disabled"><?php _e('Save settings', 'delipress'); ?> <?php  _e("(Disabled)", "delipress"); ?></div>
        <?php endif; ?>

    </div> <!-- delipress__settings -->


    <h2><?php _e("Confirmation email", "delipress"); ?></h2>

    <p><?php _e("Customize the email your users will receive to confirm their subscription. This email is only sent if the double Opt-In option above is enabled.", "delipress"); ?></p>

    <div class="delipress__settings">
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label
                    for="delipress_logo_subscription"
                >
                    <?php _e('Logo', 'delipress'); ?>
                </label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <img src="<?php echo $this->options[$this->currentTab]["logo_subscription"] ?>" alt="<?php _e('Logo', 'delipress'); ?>" width="150" class="delipress__imageinput js-delipress-logo-subscription">
                <div class="delipress__button delipress__button--soft js-delipress-change-pic">
                    <?php _e("Change image", "delipress"); ?>
                </div>
                <input type="hidden" class="js-delipress-media" name="<?php echo sprintf("%s[%s][logo_subscription]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>" value="<?php echo $this->options[$this->currentTab]["logo_subscription"] ?>">
            </div>
            <div class="delipress__settings__item__help delipress__f4">

            </div>
        </div>
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label
                    for="delipress_title_subscription"
                >
                    <?php _e('Title', 'delipress'); ?>
                </label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    id="delipress_title_subscription"
                    name="<?php echo sprintf("%s[%s][title_subscription]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>"
                    type="text"
                    class="delipress__input"
                    value="<?php echo esc_attr( $this->options[$this->currentTab]["title_subscription"] ); ?>"
                />
            </div>
            <div class="delipress__settings__item__help delipress__f4">

            </div>
        </div>
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label
                    for="delipress_textarea_subscription"
                >
                    <?php _e('Text', 'delipress'); ?>
                </label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <textarea
                    id="delipress_textarea_subscription"
                    name="<?php echo sprintf("%s[%s][text_subscription]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>" rows="5"
                    class="delipress__input"
                ><?php echo esc_html($this->options[$this->currentTab]["text_subscription"]) ?></textarea>
            </div>
            <div class="delipress__settings__item__help delipress__f4">

            </div>
        </div>
        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label
                    for="delipress_button_subscription"
                >
                    <?php _e('Button label', 'delipress'); ?>
                </label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    id="delipress_button_subscription"
                    name="<?php echo sprintf("%s[%s][button_subscription]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>"
                    type="text"
                    class="delipress__input"
                    value="<?php echo esc_attr( $this->options[$this->currentTab]["button_subscription"] ); ?>"
                />
            </div>
            <div class="delipress__settings__item__help delipress__f4">

            </div>
        </div>

        <div class="delipress__settings__item delipress__flex">
            <div class="delipress__settings__item__label delipress__f2">
                <label
                    for="delipress_subscription_redirect"
                >
                    <?php _e('Redirection URL', 'delipress'); ?>
                </label>
            </div>
            <div class="delipress__settings__item__field delipress__f4">
                <input
                    id="delipress_subscription_redirect"
                    name="<?php echo sprintf("%s[%s][subscription_redirect]", OptionHelper::OPTIONS_NAME, $this->currentTab); ?>"
                    type="url"
                    class="delipress__input"
                    value="<?php echo esc_attr( $this->options[$this->currentTab]["subscription_redirect"] ); ?>"
                />
            </div>
            <div class="delipress__settings__item__help delipress__f4">
              <p>
                <?php _e('Redirect to a specific URL after confirmation.<br />Leave empty to redirect to homepage.', 'delipress'); ?>
              </p>
            </div>
        </div>

        <?php if(!$disabledForm): ?>
            <button type="submit" class="delipress__button delipress__button--save"><?php _e('Save settings', 'delipress'); ?></button>
        <?php else: ?>
            <div class="delipress__button delipress__button--save delipress__button--demo-disabled"><?php _e('Save settings', 'delipress'); ?> <?php  _e("(Disabled)", "delipress"); ?></div>
        <?php endif; ?>

    </div> <!-- delipress__settings -->
</form>


<script>
    var $ = jQuery
    $(document).ready(function(){

        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id;

        var $valInput = $(this).find('.js-delipress-media');
        var $img      = $(this).find('.js-delipress-logo-subscription');

        $('.js-delipress-change-pic').on("click", function(e){
            e.preventDefault();

            if(file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                multiple: false
            });

            // Callback when media library is opened
            file_frame.on('open',function() {
                let selection = file_frame.state().get('selection');
                let id = $valInput.val();
                let attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

            // Callback when image is selected
            file_frame.on('select', function() {
                let attachment = file_frame.state().get('selection').first().toJSON();

                $img.attr('src', attachment.url);
                $valInput.val(attachment.url);

                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Open the modal
            file_frame.open();
        });

    })
</script>
