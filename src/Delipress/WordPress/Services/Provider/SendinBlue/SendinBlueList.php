<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\ListModel;

class SendinBlueList extends ListModel {

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
    public function getListParent(){
        return $this->object["list_parent"];
    }

    /**
     * @return int
     */
    public function countSubscribers(){
        if(!$this->object["total_subscribers"]){
            return 0;
        }

        return $this->object["total_subscribers"];
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
