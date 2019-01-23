<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;

class SendGridList extends ListModel {

    /**
     * @return int
     */
    public function getId(){

        return $this->object["id"];
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->object["name"];
    }

    /**
     * @return int
     */
    public function countSubscribers(){
        return $this->object["recipient_count"];
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
