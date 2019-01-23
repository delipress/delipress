<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractProviderExport;

use Delipress\WordPress\Helpers\ProviderHelper;

/**
 * MailchimpExport
 */
class MailchimpExport extends AbstractProviderExport {

    protected $provider = ProviderHelper::MAILCHIMP;
    

}


