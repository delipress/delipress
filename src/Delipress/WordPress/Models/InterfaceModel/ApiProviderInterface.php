<?php 

namespace Delipress\WordPress\Models\InterfaceModel;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\InterfaceModelListInterface;

interface ApiProviderInterface {

    /**
     * @return array
     */
    public function getUser();

    /**
     * Send a email
     * 
     * @param array $params
     * @return array
     */
    public function sendEmail($params);

    /**
     * @return array
     */
    public function testConnexion();

    /** 
     * @return array
     */
    public function createClientFromOptionServices();
 
    /**
     * @return array
     */
    public function getLists();


    /**
     * 
     * @param int $idList
     * @param array $params
     * @return array
     */
    public function deleteSubscriberOnList($idList, $params);

    /**
     * 
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function deleteSubscribersOnList($idList, $params);

    /**
     * 
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function createSubscriberOnList($idList, $params);

    /**
     * 
     * @param integer $idList
     * @param array $params
     * @return array
     */
    public function createSubscribersOnList($idList, $params);
    
    /**
     * 
     * @param integer $idList
     * @param integer $idSubscriber
     * @param array $params
     * @return array
     */
    public function editSubscriberOnList($idList, $idSubscriber, $params);

    /**
     * 
     * @param ListInterface $list
     * @return array
     */
    public function createList(ListInterface $list);

    /**
     * 
     * @param integer $id
     * @return array
     */
    public function deleteList($id);


    /**
     * 
     * @param array $params
     * @param array $metas
     * @return array
     */
    public function createDraftCampaign($params, $metas);

    /**
     * 
     * @param CampaignModel $campaign
     * @return array
     */
    public function sendCampaign(CampaignModel $campaign);

    
    public function getList($listIdProvider);
    
    public function getListContacts($listIdProvider);
}