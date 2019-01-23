<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderStatistic;

/**
 * MailchimpStatistic
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class MailchimpStatistic extends AbstractProviderStatistic {

    protected $provider = Providerhelper::MAILCHIMP;

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getBounced($result){
        return $result["results"]["bounces"]["hard_bounces"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalClicked($result){
        return $result["results"]["clicks"]["clicks_total"];
    }
    
    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueClicked($result){
        return $result["results"]["clicks"]["unique_clicks"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalEmailSend($result){
        return $result["results"]["emails_sent"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalOpened($result){
        return $result["results"]["opens"]["opens_total"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueOpened($result){
        return $result["results"]["opens"]["unique_opens"];
    }

    /**
     *
     * @param array $result
     * @return float
     */
    protected function getRateOpened($result){
        $percent = $result["results"]["opens"]["open_rate"] * 100;
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
        $percent = $result["results"]["clicks"]["click_rate"] * 100;
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
        return $result["results"]["unsubscribed"];
    }

    /**
     *
     * @param array $result
     * @return array
     */
    protected function generateChart($result){
        return array();

        // FAIL STATS FOR MAILCHIMP 

        // $nbLastDays = 1;
        // $minDate    = new \DateTime("now");
        // $minDate->sub(new \DateInterval("P{$nbLastDays}D"));

        // $interval  = new \DateInterval('P1D');
        // $daterange = new \DatePeriod($minDate, $interval ,new \DateTime("tomorrow"));

        // $datas      = array();
        // $labels     = array();
        // foreach($daterange as $key => $date){

        //     $start = $date->format("Y-m-d ") . "00:00";
        //     $end   = $date->format("Y-m-d ") . "23:00";

        //     $tStart = strtotime($start);
        //     $tEnd   = strtotime($end);
        //     $tNow   = $tStart;

        //     while($tNow <= $tEnd){

        //         $tNow = strtotime('+60 minutes',$tNow);
                
        //         $datas[date("Y-m-d-H",$tNow)] = array(
        //             "total_click" => 0,
        //             "total_open"  => 0
        //         );
                
        //         $dateLabel = date("Y-m-d H:i:s", $tNow);
        //         switch(get_locale()){
        //             case "fr_FR":
        //                 $dateLabel = date("d-m-Y H:i:s", $tNow);
        //                 break;
        //         }
                
        //         $labels[] = $dateLabel;
        //     }

        // }
        

        // foreach($result["results"]["timeseries"] as $key => $timeserie){
        //     $date     = new \DateTime($timeserie["timestamp"]);
        //     $keyDate  = $date->format("Y-m-d-H");

        //     if(array_key_exists($keyDate, $datas)){
        //         $datas[$keyDate]["total_click"] += $timeserie["recipients_clicks"];
        //         $datas[$keyDate]["total_open"]  += $timeserie["unique_opens"];
        //     }
        // }

        // foreach($datas as $key => $value){
        //     $datas["chart_click"][] = $value["total_click"];
        //     $datas["chart_open"][]  = $value["total_open"];
        // }

        // return array(
        //     "labels" => $labels,
        //     "datas"  => $datas
        // );

    }

    

}
