<?php

namespace Delipress\WordPress\Models\InterfaceModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

interface ProviderImportInterface {
    
    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getEmailSubscriberFromSubscribersResult($subscriber);

    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getFirstNameSubscriberFromSubscribersResult($subscriber);

    /**
     *
     * @param array $subscriber
     * @return string
     */
    public function getLastNameSubscriberFromSubscribersResult($subscriber);
}