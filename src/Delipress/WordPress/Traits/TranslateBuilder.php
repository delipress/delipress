<?php

namespace Delipress\WordPress\Traits;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait TranslateBuilder {

    public function getTranslationBuilder(){
        return array (
            'view_online'          => __("View in browser", "delipress"),
            'component_generals'   => __('General','delipress'),
            'component_wordpress'  => __('WordPress','delipress'),
            'component_woocommerce'=> __('WooCommerce','delipress'),
            'premium_only'         => __('Premium','delipress'),
            'premium_woocommerce'  => __('Easily import your WooCommerce product(s) in one click by upgrading to the premium version.','delipress'),
            'view_pricing'         => __('View pricing','delipress'),
            'step'                 => __('Step','delipress'),
            'btn_step'             => __('Step %{number} : %{title}','delipress'),
            'activate'             => __('Display link','delipress'),
            'text_color'           => __('Text color','delipress'),
            'align'                => __('Alignment','delipress'),
            'animation'            => __('Animation effect','delipress'),
            'link'                 => __('Link','delipress'),
            'text'                 => __('Text','delipress'),
            'font_size'            => __('Font size','delipress'),
            'border_radius'        => __('Border radius','delipress'),
            'font_family'          => __('Font family','delipress'),
            'font_size_icon'       => __('Icon size','delipress'),
            'font_weight'          => __('Font weight','delipress'),
            'background_color'     => __('Background color','delipress'),
            'max_width'            => __('Max width','delipress'),
            'column_number'        => __('Column %{number}','delipress'),
            'columns'              => __('Layout','delipress'),
            'wp_media_button_text' => __('Select','delipress'),
            'wp_media_title'       => __('Choose an image','delipress'),
            'close_settings'       => __("Close settings", "delipress"),
            'success'              => __("Success", "delipress"),
            'default'              => __("Default", "delipress"),
            'saving'               => __("Saving...", "delipress"),
            'saved'                => __("Saved", "delipress"),
            'read_more'            => __("Read more", "delipress"),
            'drag'                 => __("Drop something here!", "delipress"),
            'color'                => __("Color", "delipress"),
            'cancel'               => __("Cancel", "delipress"),
            'delete'               => __("Delete", "delipress"),
            'margin'               => __("Margin", "delipress"),
            'remove_confirm'       => __("<strong>Are you sure ?</strong><span data-action='true'>Yes</span><span data-action='false'>No</span>", "delipress"),
            'orientations'         => array(
                'left' => __('Left', 'delipress'),
                'right' => __('Right', 'delipress'),
                'top' => __('Top', 'delipress'),
                'bottom' => __('Bottom', 'delipress'),
                'center' => __('Center', 'delipress'),
            ),
            'general' => array (
                'now' => __('Now','delipress'),
                'later'          => __('Later','delipress'),
                'btn_save_close' => __('Save and close','delipress'),
                'send_test_title' => __('Test email sent!', 'delipress'),
                'send_test_text' => __('Check your inbox to see your campaign', 'delipress'),
                'send_test_title_fail' => __("Oops! Something went wrong", "delipress"),
                'send_test_text_fail' => __("The following error occured : %{s} <br/> Please try again or contact DeliPress support", "delipress")
            ),
            'Optin' => array (
                'naked'                => __("Naked", "delipress"),
                'Settings' => array(
                    'tab_first'  => __("Design", "delipress"),
                    'tab_second' => __("Template", "delipress"),
                    'tab_third'  => __("Custom CSS", "delipress"),
                ),
                "shortcode_settings" => array(
                    "success_settings" => array(
                        "title_email_form"         => __("Email form", "delipress"),
                        "disable_email_input_form" => __("Disable email form", "delipress")
                    )
                ),
                "wrapper_image" => array(
                    "active" => __("Enable image", "delipress"),
                    'image_orientation' => __('Image orientation','delipress'),
                ),
                'fonts' => array(
                    'system_font'    => __('System font','delipress'),
                    'website_font'   => __('Website font','delipress'),
                    'standard_fonts' => __('Standard Fonts','delipress'),
                    'google_fonts'   => __('Google Fonts','delipress'),
                ),
                'animations' => array(
                    "none" => __('No animation', 'delipress'),
                    "fadeIn" => __('Fade in', 'delipress'),
                    "bounceIn" => __('Bounce', 'delipress'),
                    "zoomIn" => __('Zoom In', 'delipress'),
                    "lightSpeedIn" => __('Light Speed', 'delipress'),
                    "slideInUp" => __('Slide Up', 'delipress'),
                    "slideInLeft" => __('Slide Left', 'delipress'),
                    "slideInRight" => __('Slide Right', 'delipress'),
                    "flipInX" => __('Flip', 'delipress'),
                    "shake" => __('Shake', 'delipress'),
                    "swing" => __('Swing', 'delipress'),
                    "tada" => __('Tada!', 'delipress')
                ),
            ),
            'Preview' => array (
                "desktop_preview" => __("Fullscreen preview", 'delipress'),
                "smartphone" => __('Smartphone', 'delipress'),
                "tablet" => __('Tablet', 'delipress'),
                "desktop" => __('Desktop', 'delipress'),
            ),
            'Builder' => array (
                'ui' => array(
                    'section' => __('Section', 'delipress'),
                    'component' => __('Component', 'delipress'),
                ),
                'default' => array(
                    'text'      => __('I am a text. Click me and edit me!', 'delipress'),
                    'title'     => __('Title', 'delipress'),
                    'share'     => __('Share', 'delipress'),
                    'pin'       => __('Pin it', 'delipress'),
                    'subscribe' => __('Subscribe', 'delipress'),
                    'plus'      => __('+1', 'delipress'),
                ),
                'section' => array (
                    'delete' => __('Delete Section', 'delipress'),
                    'delete_warning' => __('Do you really want to delete this section?', 'delipress'),
                ),
                'actions' => array (
                    'move'      => __('Move','delipress'),
                    'delete'    => __('Delete','delipress'),
                    'duplicate' => __('Duplicate','delipress'),
                    'configure' => __('Configure','delipress'),
                    'clear_warning' => __('Do you really want to delete everything and start from scratch?', 'delipress'),
                ),
                'component' => array (
                    'header'  => __('Header Section','delipress'),
                    'footer'  => __('Footer Section','delipress'),
                    'divider' => __('Divider','delipress'),
                    'image'   => __('Image','delipress'),
                    'button'  => __('Button','delipress'),
                    'spacer'  => __('Spacer','delipress'),
                    'social'  => __('Social icons','delipress'),
                    'text'    => __('Text','delipress'),
                    'title'   => __('Title','delipress'),
                    'title_alt'   => __('Heading','delipress'),
                    'unsubscribe'   => __('Unsubscribe','delipress'),
                    'empty'   => __('Drag component from the left panel over here','delipress'),
                    'wp_article' => __('1 Post','delipress'),
                    'wp_post' => __('1 Post Type', 'delipress'),
                    'wp_product' => __('1 Product','delipress'),
                    'wp_archive_post' => __('X Posts','delipress'),
                    'wp_archive_post_woo' => __('X Products','delipress'),
                    'wp_archive_post_type' => __('X Posts Type','delipress'),

                    'delete' => __('Delete','delipress'),
                    'delete_warning' => __('Do you really want to delete this component?', 'delipress'),
                    'contents'   => array (
                        'empty_component' => array (
                            'text' => __('Add a component','delipress'),
                        ),
                    ),
                    'dnd' => array (
                        'empty_section' => array (
                            'text' => __('Choose a layout to start with','delipress'),
                        ),
                    ),
                    'email_online' => array (
                        'title' => __('View email online','delipress'),
                    ),
                    'theme' => array (
                        'text' => array (
                            'main_title_button'   => __('Main title','delipress'),
                            'second_title_button' => __('Subtitle','delipress'),
                            'standard_text'       => __('Standard text','delipress'),
                            'link_color'          => __('Link color','delipress'),
                            ),
                        ),
                ),
                'containers' => array (
                    'actions' => array (
                        'clear'            => __('Clear','delipress'),
                        'fullscreen'       => __('Fullscreen','delipress'),
                        'exit_fullscreen'  => __('Exit Fullscreen','delipress'),
                        'preview_campaign' => __('Preview campaign','delipress'),
                        'save_template' => __('Save Template','delipress'),
                    ),
                ),
                'header_settings' => array (
                    'content_tab'  => __('Components','delipress'),
                    'settings_tab' => __('Settings','delipress'),
                    'template_tab' => __('Templates','delipress'),
                    'editor_tab'   => __('Styles','delipress'),
                    'style_tab'    => __('Advanced','delipress'),
                ),
                'template_settings' => array (
                    'template_name'  => __('Template name: ','delipress'),
                    'template_name_placeholder'  => __('My template','delipress'),
                    'template_choose' => __('Choose template to update: ','delipress'),
                    'template_save_btn' => __('Save template','delipress'),
                    'template_update_btn' => __('Update template','delipress'),
                    'template_save_title'   => __('Save your template','delipress'),
                    'template_save_text'   => __('You can save your template as a new template or update an existing one','delipress'),
                    'template_library_title'    => __('Template library','delipress'),
                    'template_new'    => __('New template','delipress'),
                    'template_update'    => __('Update','delipress'),
                    'template_saved_success' => __('Template saved successfully','delipress'),
                    'template_updated_success' => __('Template updated successfully','delipress'),
                    'template_saved_error' => __('An error occured while saving your template. Please try again and contact our support if the problem persists','delipress'),
                ),
                'component_settings' => array (
                    'size_title' => array(
                        "title" => __("Heading level", "delipress")
                    ),
                    'apply_all' => __('Apply this style to all %{s}', 'delipress'),
                    'apply_all_button' => __('Apply', 'delipress'),
                    'optin' => array (
                        'state' => __("Opt-In state", "delipress"),
                        'rgpd' =>  array(
                            'information' => __('By providing your email address, you agree to receive information from us via email and you acknowledge our Privacy Policy. You can unsubscribe at any time using the unsubscribe links or by contacting us at :', 'delipress'),
                            'view_privacy_policy' => __('View Privacy Policy', 'delipress'),
                        ),
                        'generals' => array (
                            'title_image'  => __('Image','delipress'),
                            'title_bloc'   => __('Block','delipress'),
                            'title_button' => __('Subscribe button','delipress'),
                            'title_button_redirect' => __('Redirect button','delipress'),
                            'orientation_fly_in' => __('FlyIn orientation', 'delipress'),
                            'title_form'   => __('Form Setup','delipress'),
                            'title_form_rgpd'   => __('GRPD compliancy','delipress'),
                            'title_form_design'   => __('Form Design','delipress'),
                        ),
                        'form' => array(
                            'active_rgpd' => __('Enable GDPR', 'delirpess'),
                            'background_form' => __('Background form', 'delipress'),
                            'background_input' => __('Background input', 'delipress'),
                            'color_input' => __('Color input', 'delipress'),
                            'border_input' => __('Border input', 'delipress'),
                            'border_radius_input' => __('Border radius input', 'delipress'),
                            'name_fields' => __('Name fields', 'delipress'),
                            'name_fields_default' => __('No name field', 'delipress'),
                            'name_fields_single' => __('Single name field', 'delipress'),
                            'name_fields_double' => __('First and last name', 'delipress'),
                            'email_placeholder' => __('Email text', 'delipress'),
                            'form_size' => __('Fields size', 'delipress'),
                            'form_space' => __('Fields margin', 'delipress'),
                            'name_text' => __('Name text', 'delipress'),
                            'name_placeholder' => __('Your name', 'delipress'),
                            'firstname_text' => __('Firstname text', 'delipress'),
                            'firstname_placeholder' => __('Your firstname', 'delipress'),
                            'lastname_text' => __('Lastname text', 'delipress'),
                            'lastname_placeholder' => __('Your lastname', 'delipress'),
                            'form_size_default' => __('Default', 'delipress'),
                            'form_size_large' => __('Large', 'delipress'),
                            'form_size_full' => __('Full width', 'delipress'),
                            'form_size_inline' => __('Inline', 'delipress'),
                            'enable_fields' => __('Enable form fields', 'delipress'),
                            'url_privacy' => __('Privacy Page', 'delipress'),
                            'manage_your' => __('Manage your', 'delipress'),
                            'privacy_page' => __('privacy page', 'delipress'),
                            'redirect_url' => __('Redirect/Download url', 'delipress'),
                            'redirect_url_not_valid' => __('Please enter a valid redirect/download url', 'delipress'),
                            'redirect_url_warning' => __('You disabled your form field. You need to provide a redirect or download url for this Opt-In to work.', 'delipress'),
                        ),
                        'custom_css' => array(
                            "title" => __("Custom CSS", "delipress")
                        ),
                        'models' => array(
                            "title" => __("Select a Template", "delipress")
                        )
                    ),
                    'spacer' => array(
                        'title' => __("Spacer", "delipress"),
                        'height' => __("Height", "delipress")
                    ),
                    'email_online_settings' => array (
                        'title_default' => __('Top section','delipress'),
                    ),
                    'base_general' => array (
                        'title_default'    => __('Generic    colors','delipress'),
                        'background_color' => __('Background color','delipress'),
                        'text_color'       => __('Text       color','delipress'),
                        'link_color'       => __('Link color','delipress'),
                        'title_background_campaign' => __("Campaign background", "delipress"),
                        'title_text_component' => __("Default colors", "delipress")
                    ),
                    'section' => array (
                        'title_settings'   => __("Section", "delipress"),
                        'background_image' => __("Background image", "delipress"),
                        'background_color' => __('Background color','delipress'),
                        'column'           => __('Columns width','delipress'),
                        'remove_image'     => __('Remove image','delipress'),
                        'vertical_align'   => __('Vertical align', 'delipress')
                    ),
                    'style' => array (
                        'attributes_default' => array (
                            'innerPadding'  => __('Inner padding','delipress'),
                            'background'    => __('Background color','delipress'),
                            'padding'       => __('Padding','delipress'),
                            'paddingTop'    => __('Top','delipress'),
                            'paddingBottom' => __('Bottom','delipress'),
                            'paddingLeft'   => __('Left','delipress'),
                            'paddingRight'  => __('Right','delipress'),
                        ),
                    ),
                    'title' => array(
                        'title_settings' => __("Title component", "delipress")
                    ),
                    'wp_post' => array (
                        'title'          => __('Import Post','delipress'),
                        'settings_title' => array   (
                            'title' => __('Title','delipress'),
                        ),
                        'settings_content' => array (
                            'title'   => __('Content','delipress'),
                            'full'    => __('All','delipress'),
                            'excerpt' => __('Excerpt','delipress'),
                        ),
                        'settings_choose_article' => array(
                            'title' => __("Choose post", "delipress"),
                        ),
                        'settings_image' => array (
                            'title' => __('Post Thumbnail','delipress'),
                        ),
                        'button_import' => __('Import Post','delipress'),
                        'placeholder'   => __('Search post','delipress'),
                    ),
                    'wp_archive_post' => array(
                        'title' => __("Import Posts", "delipress"),
                        'settings_choose_type' => array(
                            'placeholder' => __("Select Post Type", "delipress")
                        ),
                        'settings_choose_article' => array(
                            'title'     => __("Choose posts", "delipress"),
                            'post_type' => __("Post Type", "delipress"),
                            'posts'      => __("Posts", "delipress"),
                        ),
                        'add_post' => __("Add post to selection", "delipress"),
                        'import_posts' => __("Import posts", "delipress")
                    ),
                    'divider' => array (
                        'title_settings'  => __("Divider Component", "delipress"),
                        'borderColor'     => __('Border color','delipress'),
                        'borderStyle'     => __('Border style','delipress'),
                        'borderPx'        => __('Border height','delipress'),
                        'borderWidth'     => __('Divider width','delipress'),
                    ),
                    'button' => array (
                        'title_settings'   => __("Button Component", "delipress"),
                        'color'            => __('Color','delipress'),
                        'background_color' => __('Background color','delipress'),
                        'border_radius'    => __('Border radius','delipress'),
                        'border'           => __('Border','delipress'),
                        'border_settings'  => __('Border Settings','delipress'),
                        'width'            => __('Width','delipress'),
                        'height'           => __('Height','delipress'),
                    ),
                    'text' => array (
                        'title_settings'   => __("Text Component", "delipress"),
                        'line_height' => __('Line height','delipress'),
                    ),
                    'social' => array (
                        'title_settings'   => __("Social Component", "delipress"),
                        'colorSelector'   => __('Text color', 'delipress'),
                        'url'             => __('URL','delipress'),
                        'text'            => __('Text','delipress'),
                        'backgroundColor' => __('Monochrome','delipress'),
                        'activate'        => __('Activate','delipress'),
                    ),
                    'image' => array (
                        'title_settings'          => __("Image Component", "delipress"),
                        'size'                    => __('Size','delipress'),
                        'link'                    => __('Link','delipress'),
                        'url_src'                 => __('Image source url','delipress'),
                        'borderRadius'            => __('Border radius', 'delipress'),
                        'action'                  => __('Image','delipress'),
                        'wp_library_src'          => __('Add an image','delipress'),
                        'wp_library_src_have_src' => __('Change image','delipress'),
                        'sizes'                   => __("Thumbnail size", "delipress"),
                    )
                )
            )
        );
    }

}
