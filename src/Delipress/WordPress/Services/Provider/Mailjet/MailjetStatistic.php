<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderStatistic;

/**
 * MailjetStatistic
 *
 * @author Delipress
 */
class MailjetStatistic extends AbstractProviderStatistic {

    protected $provider = Providerhelper::MAILJET;

    /**
     * @see AbstractProviderStatistic
     * 
     * @param int $campaignId
     * @return array
     */
    public function getStatisticsGeneral($campaignId){
        $response = $this->providerApi->getNewsletterInformation($campaignId);

        if($response["success"]){
            $this->campaignId = $response["results"][0]["ID"];
            return parent::getStatisticsGeneral($response["results"][0]["ID"]);
        }

        return array();

    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getBounced($result){
        return $result["results"][0]["BouncedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getBlocked($result){
        return $result["results"][0]["BlockedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalClicked($result){
        return $result["results"][0]["ClickedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueClicked($result){
        return $result["results"][0]["ClickedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
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
    protected function getTotalEmailSend($result){
        return $result["results"][0]["DeliveredCount"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getTotalOpened($result){
        return $result["results"][0]["OpenedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
     */
    protected function getUniqueOpened($result){
        return $result["results"][0]["OpenedCount"];
    }

    /**
     *
     * @param array $result
     * @return int
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
     * @return int
     */
    protected function getUnsubscribed($result){
        return $result["results"][0]["UnsubscribedCount"];
    }
    
    /**
     *
     * @param array $result
     * @return array
     */
    protected function generateChart($result){

        $opens  = $this->providerApi->getCampaignOpenStatistics($this->campaignId);
        $clicks = $this->providerApi->getCampaignClickStatistics($this->campaignId);
        
        $nbLastDays = 1;
        $minDate    = new \DateTime("now");
        $minDate->sub(new \DateInterval("P{$nbLastDays}D"));

        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($minDate, $interval ,new \DateTime("tomorrow"));

        $timezone    = get_option('timezone_string');
        if(empty($timezone)){
            $timezone = new \DateTimeZone("UTC");
        }
        else{
            $timezone = new \DateTimeZone($timezone);
        }



        $datas      = array();
        $labels     = array();
        foreach($daterange as $key => $date){

            $start = $date->format("Y-m-d ") . "00:00";
            $end   = $date->format("Y-m-d ") . "23:00";

            $tStart = strtotime($start);
            $tEnd   = strtotime($end);
            $tNow   = $tStart;

            while($tNow <= $tEnd){

                $tNow = strtotime('+60 minutes',$tNow);
                
                $datas[date("Y-m-d-H",$tNow)] = array(
                    "total_click" => 0,
                    "total_open"  => 0
                );
                
                $dateLabel = date("Y-m-d H:i:s", $tNow);
                switch(get_locale()){
                    case "fr_FR":
                        $dateLabel = date("d-m-Y H:i:s", $tNow);
                        break;
                }
                
                $labels[] = $dateLabel;
            }

        }
    
        if($opens["success"]){
            foreach($opens["results"] as $key => $open){
                $date     = new \DateTime($open["OpenedAt"], new \DateTimeZone("UTC"));
                $date->setTimezone($timezone);

                $keyDate  = $date->format("Y-m-d-H");
                if(array_key_exists($keyDate, $datas)){
                    $datas[$keyDate]["total_open"]  += 1;
                }

            }
        }

        if($clicks["success"]){
            foreach($clicks["results"] as $key => $click){
                $date     = new \DateTime($open["OpenedAt"], new \DateTimeZone("UTC"));
                $date->setTimezone($timezone);

                $keyDate  = $date->format("Y-m-d-H");
                if(array_key_exists($keyDate, $datas)){
                    $datas[$keyDate]["total_click"]  += 1;
                }

            }
        }



        foreach($datas as $key => $value){
            $datas["chart_click"][] = $value["total_click"];
            $datas["chart_open"][]  = $value["total_open"];
        }

        return array(
            "labels" => $labels,
            "datas"  => $datas
        );

    }
    

}
