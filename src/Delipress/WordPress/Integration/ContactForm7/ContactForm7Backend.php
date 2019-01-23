<?php

namespace Delipress\WordPress\Integration\ContactForm7;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;

use Delipress\WordPress\Integration\AbstractIntegration;

use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;


class ContactForm7Backend extends AbstractIntegration implements HooksAdminInterface {


    /**
     * @return boolean
     */
    protected function isAuthorize(){
        return parent::isAuthorize() && function_exists( 'wpcf7_add_form_tag' );
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action( 'wpcf7_init', array( $this, 'init') );
    }

    public function init(){

        if(!$this->isAuthorize()){
            return;
        }

        wpcf7_add_tag_generator( 'delipress-optin', __("DeliPress Opt-In", "delipress"), "delipress-optin", array($this, "addOptinLink") );

    }


	public function addOptinLink( $contact_form, $args = '' ) {
            $args = wp_parse_args( $args, array() );
            $type = $this->getShortcode();

            $description = __( "Select an Opt-In to link with this form", 'delipress' );
            $optins      = $this->optinServices->getOptins( array(
                "meta_query"  => array(
                    array(
                        'key'     => PostTypeHelper::META_OPTIN_IS_ACTIVE,
                        'value'   => true,
                    ),
                    array(
                        'key' => PostTypeHelper::META_OPTIN_TYPE,
                        'value' => OptinHelper::CONTACT_FORM_7
                    )
                ),
                "status" => "publish"
            ));
        ?>
        <div class="control-box">
            <fieldset>
                <legend><?php echo sprintf( esc_html( $description )); ?></legend>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>">
                                    <?php echo esc_html( __( 'Choose opt-in', 'delipress' ) ); ?>
                                </label>
                            </th>
                            <td>
                                <?php foreach($optins as $key => $optin): ?>
                                    <label for="dp-<?php echo $optin->getId(); ?>">
                                        <?php echo $optin->getTitle(); ?>
                                    </label>
                                    <input
                                        name="delipress-optin"
                                        class="option"
                                        type="radio"
                                        id="dp-<?php echo $optin->getId(); ?>"
                                        value="<?php echo $optin->getId(); ?>">
                                    <br />
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <div class="insert-box">
            <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

            <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'delipress' ) ); ?>" />
            </div>
        </div>
        <?php
    }


}
