<?php

namespace Delipress\WordPress\Endpoints;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\ActionHelper;

/**
 * EndpointPostType
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class EndpointPostType extends AbstractHook implements HooksAdminInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){

    }


    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(current_user_can('manage_options' ) ){
            add_action( 'wp_ajax_delipress_get_post_types', array($this, 'getPostTypes') );
            add_action( 'wp_ajax_delipress_get_posts', array($this, 'getPosts') );
            add_action( 'wp_ajax_delipress_get_post', array($this, 'getPost') );
            add_action( 'wp_ajax_delipress_import_posts_wp', array($this, 'importPostsWP') );
        }
    }

    /**
     * @filter DELIPRESS_SLUG . "_endpoint_get_post_types
     *
     * @return JSON Response
     */
    public function getPostTypes(){
        if(
            !isset( $_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            !isset( $_POST["_wpnonce_ajax"] )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }
        $args      = apply_filters(DELIPRESS_SLUG . "_endpoint_get_post_types", array());
        $postTypes = get_post_types($args);

        $exclude = apply_filters(DELIPRESS_SLUG. "_endpoint_get_post_types_exclude",
             array(
                'acf-field',
                'acf-field-group',
                'revision',
                'nav_menu_item',
                'attachment',
                'post',
                'customize_changeset',
                'custom_css',
                PostTypeHelper::CPT_CAMPAIGN,
                PostTypeHelper::CPT_OPTINFORMS
            )
        );

        $exclude[] = "product";

        foreach( array_values($exclude) as $i ) {
		    unset( $postTypes[ $i ] );
	    }

        foreach($postTypes as $key => $value){
            $postTypes[$key] = get_post_type_object($value);
        }

        wp_send_json_success(
            array(
                "code"    => "get_post_types",
                "results" => $postTypes

            )
        );

    }

    /**
     * @filter DELIPRESS_SLUG . "_endpoint_get_posts"
     *
     * @return JSON Response
     */
    public function getPosts(){

        if(
            !isset( $_POST["_wpnonce_ajax"] ) ||
            !isset( $_POST["post_type"] )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $postType = sanitize_text_field($_POST["post_type"]);
        $search   = ( isset($_POST["s"] ) ) ? sanitize_text_field($_POST["s"]) : "";
        $offset   = ( isset($_POST["offset"] ) ) ? (int) $_POST["offset"] : 0;
        $lang     = ( isset($_POST["lang"] ) ) ? (int) $_POST["lang"] : "";

        $args  = array(
            "post_type"      => $postType,
            "s"              => $search,
            "offset"         => $offset,
            'lang'           => $lang,
            "posts_per_page" => 20
        );

        $args       = apply_filters(DELIPRESS_SLUG . "_endpoint_get_posts", $args);
        $posts      = get_posts($args);

        $stripShortcode = function($post){
            $post->post_content = strip_shortcodes($post->post_content);
            $post->post_excerpt = strip_shortcodes($post->post_excerpt);
            return $post;
        };

        if(!empty($posts)){
            $posts = array_map($stripShortcode, $posts);
        }

        $countPosts = wp_count_posts($postType);


        if(property_exists($countPosts, "publish")){
            $totalCountPublish = (int) $countPosts->publish;
        }
        else{
            $totalCountPublish = 0;
        }

		wp_send_json_success(
            array(
                "code"        => "get_posts",
                "results"     => $posts,
                "total_count" => $totalCountPublish
            )
        );

    }

    /**
     *
     * @return JSON Response
     */
    public function getPost(){
        if(
            !isset( $_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            !isset( $_POST["_wpnonce_ajax"] ) ||
            !isset( $_POST["post_id"] )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $postId = (int) $_POST["post_id"];

        $post = get_post($postId);
        if(!$post){
            wp_send_json_error(
                 array(
                    "code" => "post_not_found"
                )
            );
        }

        /* TODO Thomas :
            si "extrait" :
                vérifier si post->post_excerpt n'est pas vide
                si vide prendre get_extended de post_content et récupérer dans ce array juste ['main']
                striper le tout
                renvoyer juste la valeur du bon contenu stripé

            si "full" :
                renvoyer post_content stripé

            + vérifier sur le JS aussi
            + idem pour getPosts
        */
        $post->post_content = strip_shortcodes($post->post_content);
        $post->post_excerpt = strip_shortcodes($post->post_excerpt);

        $withImage   = ( isset($_POST["with_image"]) ) ? $_POST["with_image"] : false;
        $typeContent = ( isset($_POST["type_content"]) ) ? $_POST["type_content"] : array("full" => true, "excerpt" => false);

        $image     = false;
        $args      = array(
            "code"    => "get_post",
            "results" => array(
                "post"  => $post,
                "image" => $image
            )
        );

        $contentPost = $post->post_content;
        if($typeContent["excerpt"]){
            $contentPost = get_extended($post->post_content);
            $contentPost = $contentPost['main'];
        }

        $args["results"]["attrs_post"] = array(
            "content"      => $contentPost,
            "real_excerpt" => $post->post_excerpt
        );

        if($withImage === "true"){
            $postThumbnailId = get_post_thumbnail_id($postId);
            if($postThumbnailId){

                $sizes     = delipress_get_image_sizes_thumbnail_id($postThumbnailId);

                $width = $sizes["full"]["width"];
                if($sizes["full"]["width"] > 600){
                    $width  = 600;
                }

                $args["results"]["image"]       = $sizes["full"]["url"];
                $args["results"]["attrs_image"] = array(
                    "sizes"     => $sizes,
                    "srcWidth"  => $sizes["full"]["width"],
                    "srcHeight" => $sizes["full"]["height"],
                    "width"     => $width
                );

            }
        }

        if($post->post_type === "product"){

            $post = wc_get_product($post->ID);
            $args["results"]["woocommerce"] = array(
                "price"         => $post->get_price(),
                "sale_price"    => $post->get_sale_price(),
                "regular_price" => $post->get_regular_price(),
                "symbol"        => get_woocommerce_currency_symbol()
            );
        }

        wp_send_json_success($args);
    }

    /**
     *
     * @return JSON Response
     */
    public function importPostsWP(){

        if(
            !isset( $_SERVER["HTTP_FROM_REACT"]) || $_SERVER["HTTP_FROM_REACT"] !== "true" ||
            !isset( $_POST["_wpnonce_ajax"] ) ||
            !isset( $_POST["posts"] ) ||
            !isset( $_POST["config"] )
        ){
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce_ajax'], ActionHelper::REACT_AJAX ) ) {
            wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                )
            );
        }

        $posts  = $_POST["posts"];
        $config = $_POST["config"];

        $postsImport = array();
        foreach($posts as $key => $post){
            $getPost                   = get_post($post["value"]);
            $postsImport[$key]["post"] = $getPost;

            $contentPost = $getPost->post_content;
            if($config["type_content"]["excerpt"]){
                $contentPost = get_extended($getPost->post_content);
            }

            $postsImport[$key]["attrs_post"] = array(
                "content"      => strip_shortcodes($contentPost),
                "real_excerpt" => strip_shortcodes($getPost->post_excerpt)
            );

            if($config["image"]){
                $postThumbnailId = get_post_thumbnail_id($post["value"]);
                if($postThumbnailId){

                    $sizes     = delipress_get_image_sizes_thumbnail_id($postThumbnailId);

                    $width = $sizes["full"]["width"];
                    if($sizes["full"]["width"] > 600){
                        $width  = 600;
                    }

                    $postsImport[$key]["image"]       = $sizes["full"]["url"];
                    $postsImport[$key]["attrs_image"] = array(
                        "sizes"     => $sizes,
                        "srcWidth"  => $sizes["full"]["width"],
                        "srcHeight" => $sizes["full"]["height"],
                        "width"     => $width
                    );

                }
            }

            if($getPost->post_type === "product"){

                $post = wc_get_product($getPost->ID);
                $postsImport[$key]["woocommerce"] = array(
                    "price"         => $post->get_price(),
                    "sale_price"    => $post->get_sale_price(),
                    "regular_price" => $post->get_regular_price(),
                    "symbol"        => get_woocommerce_currency_symbol()
                );
            }
        }

        wp_send_json_success(
            array(
                "code"    => "import_posts_wp",
                "results" => array(
                    "config" => $config,
                    "posts"  => $postsImport
                )
            )
        );
    }


}
