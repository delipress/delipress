<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;

class MailchimpList extends ListModel {

    /**
     * @return int
     */
    public function getId(){
        if(property_exists($this, "object")){
            return $this->object["id"];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName(){
        if(property_exists($this, "object")){
            return $this->object["name"];
        }

        return "";
    }

    /**
     * @return int
     */
    public function countSubscribers(){
        if(property_exists($this, "object")){
            return $this->object["stats"]["member_count"];
        }

        return 0;
    }

    /**
     *
     * @param int $paged
     * @param int $limit
     * @param array $args
     * @return array
     */
    public function getSubscribers($paged, $limit, $args = array()){
        global $delipressPlugin;

        $params = array(
            "offset" => ($paged * $limit) - $limit,
            "limit"  => $limit
        );

        $subscribers     = $delipressPlugin->getService("SubscriberServices")
                                           ->getSubscribersByList($this->getId(), $params);

        return $subscribers;                                 
    }
    
}
