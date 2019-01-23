<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractProviderExport;

use Delipress\WordPress\Helpers\ProviderHelper;

/**
 * SendGridExport
 */
class SendGridExport extends AbstractProviderExport {

    protected $provider = ProviderHelper::SENDGRID;

}


