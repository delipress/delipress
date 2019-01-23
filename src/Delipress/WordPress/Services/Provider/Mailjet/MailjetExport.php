<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\AbstractModel\AbstractProviderExport;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;

/**
 * MailjetExport
 */
class MailjetExport extends AbstractProviderExport {

    protected $provider = ProviderHelper::MAILJET;
    
    /**
     *
     * @param ListInterface $list
     * @param array $subscribers
     * @param array $args
     * @return void
     */
    public function exportSubscribers(ListInterface $list, $subscribers, $args = array()){
        
        if(!isset($args["safeError"])){
            $args["safeError"] = true;
        }
        
        foreach($subscribers as $subscriber){
            $this->providerApi
                 ->setSafeError($args["safeError"])
                 ->createSubscriberOnList($list->getId(), $subscriber);

        }

    }

}


