<?php

namespace DeliSkypress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

interface CronInterface {
    public function setBeginDate(\DateTime $date);
    public function setInterval($interval);
    public function executeCron();
}