<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderStatistic;

/**
 * SendinBlueStatistic
 *
 * @author Delipress
 */
class SendinBlueStatistic extends AbstractProviderStatistic {

    protected $provider = Providerhelper::MAILCHIMP;

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getBounced($result){
        return $result["results"][0]["hard_bounce"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalClicked($result){
        return $result["results"][0]["clicked"];
    }
    
    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueClicked($result){
        return $result["results"][0]["clicker"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalEmailSend($result){
        return $result["results"][0]["delivered"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalOpened($result){
        return $result["results"][0]["viewed"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueOpened($result){
        return $result["results"][0]["unique_views"];
    }

    /**
     *
     * @param array $result
     * @return float
     */
    protected function getRateOpened($result){
        $totalSend   = $this->getTotalEmailSend($result);
        if($totalSend == 0){
            return 0;
        }

        $percent = ($this->getUniqueOpened($result) * 100 ) / $totalSend;
        if(is_float($percent)){
            return number_format($percent, 2, ".", " ");
        }

        return $percent;
    }

    /**
     *
     * @param array $result
     * @return float
     */
    protected function getRateClicked($result){
        $totalSend   = $this->getTotalEmailSend($result);
        if($totalSend == 0){
            return 0;
        }

        $percent = ($this->getUniqueClicked($result) * 100 ) / $totalSend;
        if(is_float($percent)){
            return number_format($percent, 2, ".", " ");
        }

        return $percent;
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUnsubscribed($result){
        return $result["results"][0]["unsub"];
    }

    /**
     *
     * @param array $result
     * @return array
     */
    protected function generateChart($result){
        return array();
    }

    

}
