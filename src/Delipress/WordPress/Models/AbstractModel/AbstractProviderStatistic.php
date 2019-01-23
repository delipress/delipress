<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\AbstractModel\AbstractProviderSettings;
use Delipress\WordPress\Models\ListModel;


/**
 * AbstractProviderStatistic
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractProviderStatistic extends AbstractProviderSettings {

    abstract protected function getBounced($result);
    
    abstract protected function getTotalClicked($result);
    abstract protected function getUniqueClicked($result);
    abstract protected function getRateClicked($result);

    abstract protected function getTotalEmailSend($result);
    abstract protected function getUnsubscribed($result);

    abstract protected function getTotalOpened($result);
    abstract protected function getUniqueOpened($result);
    abstract protected function getRateOpened($result);

    abstract protected function generateChart($result);



    /**
     *
     * @param int $campaignId
     * @return array
     */
    public function getStatisticsGeneral($campaignId){
        
        $result = $this->providerApi->getCampaignStatistics($campaignId);

        if(!$result["success"]){
            return null;
        }

        $totalEmailSend = $this->getTotalEmailSend($result);

        $totalOpened    = $this->getTotalOpened($result);
        $uniqueOpened   = $this->getUniqueOpened($result);

        $percentOpened = $this->getRateOpened($result);


        $totalClicked   = $this->getTotalClicked($result);
        $uniqueClicked  = $this->getUniqueClicked($result);

        $percentClicked = $this->getRateClicked($result);

        return array(
            "bounced"               => $this->getBounced($result),
            "total_clicked"         => $totalClicked,
            "percent_clicked"       => $percentClicked,
            "unique_clicked"        => $uniqueClicked,
            'total_email_send'      => $totalEmailSend,
            "total_opened"          => $totalOpened,
            "unique_opened"         => $uniqueOpened,
            "percent_opened"        => $percentOpened,
            "unsubscribed"          => $this->getUnsubscribed($result),
            "chart"                 => $this->generateChart($result)
        );
    }

}


