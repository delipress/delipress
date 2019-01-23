<?php

namespace Delipress\WordPress\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\MarkupIncentiveHelper;

class WidgetClassWP extends \WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			DELIPRESS_SLUG,
			esc_html__( 'DeliPress Widget', 'delipress'),
            array(
                'description' => esc_html__( 'Display your DeliPress Opt-In widget', 'delipress' )
            )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

        if(!isset($instance["optin"])){
            return;
        }

        global $delipressPlugin;

        $optionServices = $delipressPlugin->getService("OptionServices");
        $optinServices  = $delipressPlugin->getService("OptinServices");

        $licenseStatusValid  = $optionServices->isValidLicense();

        $widget = $optinServices->getWidgets(array(
            "where" => array(
                array(
                    "field" => "ID",
                    "value" => $instance["optin"]
                )
            )
        ));

        if(empty($widget)){
            return;
        }

        $isLoadedScript = OptinHelper::isOptinScriptLoaded();

        if(!$isLoadedScript){
            OptinHelper::loadOptinScript(true);
        }

        echo $args['before_widget'];
		echo sprintf(
            "<div
                id='DELI-%s-%s'
                class='delipress-optin'
                data-config='%s'
                data-id='%s'
                data-type='%s'
            ></div>",
            OptinHelper::WIDGET,
            $instance["optin"],
            esc_attr($widget[$instance["optin"]]["config"]),
            $instance["optin"],
            OptinHelper::WIDGET
        );
        if(!$licenseStatusValid){
            echo MarkupIncentiveHelper::printWhiteMarkupOptin();
        }
        echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
        global $delipressPlugin;

        $optinServices = $delipressPlugin->getService("OptinServices");

        $widgets = $optinServices->getWidgets(array(
            "selects" => array(
                "post_title"
            )
        ));

        $optin = (isset($instance['optin'])) ? $instance["optin"] : "";

		?>
        <p>
			<label for="<?php echo $this->get_field_id('optin'); ?>">
                <?php esc_html_e("Select your Opt-In form", "delipress") ?>
            </label>
			<select class="widefat" name="<?php echo $this->get_field_name('optin'); ?>" id="<?php echo $this->get_field_id('optin'); ?>">
                <option value="">—</option>
                <?php foreach($widgets as $key => $widget): ?>
                    <option value="<?php echo $key ?>" <?php selected($optin, $key); ?>>
                        <?php echo $widget["post_title"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
        $instance          = $old_instance;
        $instance['optin'] = (isset($new_instance['optin'])) ? $new_instance["optin"] : "";

        return $instance;
	}

}
