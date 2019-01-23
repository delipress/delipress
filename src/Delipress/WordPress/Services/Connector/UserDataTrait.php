<?php

namespace Delipress\WordPress\Services\Connector;


defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait UserDataTrait {

    
    /**
     *
     * @param stdObject $subscriber
     * @return string
     */
    public function getEmailSubscriberFromSubscribersResult($subscriber){ 
        return $subscriber->user_email;
    }

    /**
     *
     * @param stdObject $subscriber
     * @return string
     */
    public function getFirstNameSubscriberFromSubscribersResult($subscriber){
        try{
            return $subscriber->first_name;
        }
        catch(\Exception $e){
            return "";
        }
    }

    /**
     *
     * @param stdObject $subscriber
     * @return string
     */
    public function getLastNameSubscriberFromSubscribersResult($subscriber){ 
        try{
            return $subscriber->last_name;
        }
        catch(\Exception $e){
            return "";
        }
    }


    /**
     *
     * @param stdObject $subscriber
     * @return DateTime
     */
    public function getCreatedAtSubscriberFromSubscribersResult($subscriber){
        return new \DateTime($subscriber->data->user_registered);
    }

}

