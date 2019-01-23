<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;

class MailjetList extends ListModel {

    public function getId(){
        return $this->object["ID"];
    }

    public function getName(){
        return $this->object["Name"];
    }

    public function countSubscribers(){
        return $this->object["SubscriberCount"];
    }

  

    public function getColor(){
        return "#000";
    }

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
