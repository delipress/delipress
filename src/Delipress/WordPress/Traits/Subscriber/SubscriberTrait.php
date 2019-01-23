<?php

namespace Delipress\WordPress\Traits\Subscriber;

use Delipress\WordPress\Helpers\PostTypeHelper;

use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Services\Table\TableServices;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait SubscriberTrait {

    /**
     * @param string $value
     * @param string $typeReturn
     * @return string|SubscriberModel
     */
    public function checkSubscriberExistByEmail($value, $typeReturn = "str"){
        
        $table = TableServices::getTable("SubscriberTableServices");

        $subscriber = $table->getSubscriber(
            array(
                "query" => array(
                    array(
                        "operator" => "=",
                        "field"    => "email",
                        "value"    => $value
                    )
                )
            )
        );

        if(empty($subscriber)){
            return $value;
        }

        $subscriberModel = new SubscriberModel();
        $subscriberModel->setSubscriber($subscriber);
        $this->subscriberExist = $subscriberModel;

        if($typeReturn === "str"){
            return $value;
        }

        return $subscriberModel;
    }

    /**
     * 
     * @param string $value
     * @return SubscriberModel | null
     */
    public function checkSubscriberExist($value){
        $table = TableServices::getTable("SubscriberTableServices");

        $subscriber = $table->getSubscriber(
            array(
                "query" => array(
                    array(
                        "field"    => "id",
                        "operator" => "=",
                        "value"    => $value
                    )
                )
            )
        );

        if(!empty($subscriber)){
            $subscriberModel = new SubscriberModel();
            $subscriberModel->setSubscriber($subscriber);
            return $subscriberModel;
        }

        $this->missingParameters["check_subscriber_exist"] = 1;
        
        return null;
        
    }

    /**
     * 
     * @param array $value
     * @return array
     */
    public function checkSubscribersExist($values){

        foreach($values as $key => $value){
            $values[$key] = $this->checkSubscriberExist($value);
        }

        return $values;
        
    }

 

}

