<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractProviderExport;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;

/**
 * SendinBlueExport
 */
class SendinBlueExport extends AbstractProviderExport {

    protected $provider = ProviderHelper::SENDINBLUE;

}


