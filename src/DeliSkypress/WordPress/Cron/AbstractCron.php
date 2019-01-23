<?php

namespace DeliSkypress\WordPress\Cron;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ActivationInterface;
use DeliSkypress\Models\DeactivationInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\Models\CronInterface;

use DeliSkypress\WordPress\Actions\AbstractHook;


/**
 * AbstractCron
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractCron extends AbstractHook implements HooksInterface, ActivationInterface, DeactivationInterface, CronInterface{

    protected $name = "cron_name";

    /**
     *
     * @param \DateTime $date
     */
    public function __construct($date = "", $interval = ""){

        if(empty($date)){
            $date = apply_filters("default_date_abstract_cron", new \DateTime("now"));
        }
        
        if(empty($interval)){
            $interval  = apply_filters("default_interval_abstract_cron", "daily");
        }
        
        $this->beginDate = $date;
        $this->interval  = $interval;
    }

    /**
     *
     * @param string $interval
     * @return AbstractCron
     */
    public function setInterval($interval){
        $this->interval = $interval;
        return $this;
    }

    /**
     *
     * @param \DateTime $date
     * @return AbstractCron
     */
    public function setBeginDate(\DateTime $date){
        $this->beginDate = $date;
        return $this;
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        $this->createCron();
        add_action($this->name, array($this, "executeCron"));
    }
    
    /**
     * @see ActivationInterface
     */
    public function activation(){
        $this->createCron();
    }


    /**
     * @see CronInterface
     * @return void
     */
    protected function createCron(){
        if (! wp_next_scheduled ( $this->name )) {
            wp_schedule_event($this->beginDate->getTimestamp(), $this->interval, $this->name);
        }
    }

    /**
     * @see DeactivationInterface
     * @return void
     */
    public function deactivation(){
        wp_clear_scheduled_hook($this->name);
    }

    /**
     * @see CronInterface
     * @return void
     */
    public function executeCron(){}

}
