<?php

namespace Delipress\WordPress\Services\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\OptinModel;

use Delipress\WordPress\Traits\Optin\OptinTrait;

use Delipress\WordPress\Helpers\OptinHelper;

/**
 * OptinStatsServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class OptinStatsServices implements MediatorServicesInterface, ServiceInterface {

    use OptinTrait;

    protected $missingParameters = array();

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        
        $this->optinStatsTableServices   = $services["OptinStatsTableServices"];

    }

    /**
     *
     * @param int $optinId
     * @param string $meta
     * @return void
     */
    protected function incrementMeta($optinId, $meta){
       
        $timezoneWP = get_option('timezone_string');
        if(empty($timezoneWP)){
            $timezoneWP = new \DateTimeZone("UTC");
        }
        else{
            $timezoneWP = new \DateTimeZone($timezoneWP);
        }

        $now        = new \DateTime("now", $timezoneWP);
        
        $this->optinStatsTableServices->insertOptinStat(
            array(
                "optin_id"   => $optinId,
                "type"       => $meta,
                "created_at" => $now->format("Y-m-d H:i:s")
            )
        );

        return;
    }

    /**
     *
     * @param int $optinId
     * @return array
     */
    public function incrementView($optinId){

        $optin = $this->checkOptinExist($optinId);

        if(!empty($this->missingParameters) ){
            return array(
                "success" => false
            );
        }

       $this->incrementMeta($optin->getId(), OptinHelper::COUNTER_VIEW);

        return array(
            "success" => true
        );
    }

    /**
     *
     * @param int $optinId
     * @return void
     */
    public function incrementConvert($optinId){
        $optin = $this->checkOptinExist($optinId);

        if(!empty($this->missingParameters) ){
            return array(
                "success" => false
            );
        }

       $this->incrementMeta($optin->getId(), OptinHelper::COUNTER_CONVERT);

        return array(
            "success" => true
        );
    }

    /**
     *
     * @param int $optinId
     * @param array $args
     * @return array
     */
    public function getStatsTimeseries($optinId, $args = array()){

        $keyDateFormat     = $this->getKeyDateFormat($args["range"]);

        $minDate    = new \DateTime("now");
        switch($args["range"]){
            case "day":
                $minDate->sub(new \DateInterval("P{$args["last_days"]}D"));
                $interval  = new \DateInterval('P1D');
                break;
        }
        
        $argsDefault = array(
            "start" => $minDate->format("Y-m-d H:i:s"),
        );

        $args       = array_merge($argsDefault, $args);

        $timeseries = $this->optinStatsTableServices->getStatsTimeseries($optinId, $args);

        if(empty($timeseries)){
            return array();
        }

        $daterange = new \DatePeriod($minDate, $interval ,new \DateTime("tomorrow"));
        foreach($daterange as $key => $date){
            $keyDateF           = $date->format($keyDateFormat);
            $values[$keyDateF]  = 0;
        }

        foreach($timeseries as $key => $timeserie){
            $keyDate = $this->getKeyDateTimseriesWithRange($timeserie, $args["range"]);
            $values[$keyDate] = (int) $timeserie["nb"];
        }

        return $values;
    }

    /**
     * @param array $args
     * @return args
     */
    public function getLabelTimeseries($args){
        $format            = $this->getFormatDateTimeseriesView($args["range"]);

        $minDate    = new \DateTime("now");
        switch($args["range"]){
            case "day":
                $minDate->sub(new \DateInterval("P{$args["last_days"]}D"));
                $interval  = new \DateInterval('P1D');
                break;
        }
        
        $labels    = array();
        $daterange = new \DatePeriod($minDate, $interval ,new \DateTime("tomorrow"));
        foreach($daterange as $key => $date){
            $labels[]           = $date->format($format);
        }

        return $labels;
    }

    /**
     * @param  string $range
     * @return string
     */
    protected function getFormatDateTimeseriesView($range){
        $locale = get_locale();

        switch($range){
            case "day":
                switch($locale){
                    case "fr_FR":
                        return "d-m-Y";
                        break;
                    default:
                        return "Y-m-d";
                        break;
                }
                break;

        }
    }

    /**
     * @param  string $range
     * @return string
     */
    protected function getKeyDateFormat($range){
        $locale = get_locale();

        switch($range){
            case "day":
            default:
                return "Y-m-d";
        }
    }

    protected function getKeyDateTimseriesWithRange($timeserie, $range){
        switch($range){
            case "day":
                return sprintf(
                    "%s-%s-%s",
                    $timeserie["year"],
                    $timeserie["month"],
                    $timeserie["day"]
                ) ;
            break;
        }
    }


}









