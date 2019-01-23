<?php 

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;

$provider = ProviderHelper::getProviderFromUrl();
$file = sprintf("%s/_%s.php", __DIR__,  $provider["key"] );

if(file_exists($file) ){
    include_once($file);
}
else{
    wp_redirect(admin_url());
    exit;
}

?>