<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

function delipress_is_local(){
    $host = null;
    if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $host = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else if(isset($_SERVER["REMOTE_ADDR"])){
        $host = $_SERVER["REMOTE_ADDR"];
    }

    if($host === null){
        return;
    }

    if(isset($_SERVER["REMOTE_USER"])){
        return true;
    }

    return ( substr($host,0,8) == "192.168." || substr($host,0,6) == "127.0." || substr($host,0,4) == "172." || $host == "::1");

}


function delipress_full_url( $s )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = (isset($s['SERVER_PORT']) ) ? $s['SERVER_PORT'] : "";
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( isset( $s['HTTP_HOST'] ) ) ? $s['HTTP_HOST'] : null ;
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host . $s['REQUEST_URI'];
}

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function delipress_get_image_sizes_thumbnail_id($thumbnailId) {
	global $_wp_additional_image_sizes;

	$sizes = array();
    $intermediates = get_intermediate_image_sizes();
	foreach (  $intermediates as $_size ) {
        $srcImage = wp_get_attachment_image_src($thumbnailId, $_size);
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			$sizes[ $_size ]['url']    = $srcImage[0];

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				'url'    => $srcImage[0],
			);
		}
	}

    $srcImage = wp_get_attachment_image_src($thumbnailId, "full");

    $sizes["full"] = array(
        'width'  => $srcImage[1],
        'height' => $srcImage[2],
        'crop'   => false,
        'url'    => $srcImage[0],
    );

	return $sizes;
}


function delipress_js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function delipress_js_array($array)
{
    $temp = array_map('delipress_js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

function delipress_get_url_premium(){
    $locale = get_locale();

    switch($locale){
        case "fr_FR":
            return "https://delipress.io/fr/tarifs/";
        default:
            return "https://delipress.io/pricing";
    }
}

function delipress_get_documentation_url(){
    $locale = get_locale();

    switch($locale){
        case "fr_FR":
            return "http://delipress.io/fr/documentation/accueil";
        default:
            return "http://delipress.io/documentation/home";
    }
}



function delipress_array_flatten(array $array)
{
    $flat = array(); // initialize return array
    $stack = array_values($array); // initialize stack
    while($stack) // process stack until done
    {
        $value = array_shift($stack);
        if (is_array($value)) // a value to further process
        {
            $stack = array_merge(array_values($value), $stack);
        }
        else // a value to take
        {
           $flat[] = $value;
        }
    }
    return $flat;
}