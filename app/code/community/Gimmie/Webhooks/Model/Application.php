<?php
class Gimmie_Webhooks_Model_Application extends Mage_Core_Model_Abstract {
  public function _construct() {
    parent::_construct();
    $this->_init('webhooks/application');
  }

  /*
   * @return Gimmie_Webhooks_Model_Application
   */
  public static function getBySecret($secret) {
    $applications = Mage::getModel('webhooks/application')->getCollection();
    $applications->addFilter('secret', $secret);
    if($applications->getFirstItem()->getData()==null){
      throw new Exception("Application with key $secret does not exist.");
    }else{
      return $applications->getFirstItem();
    }
  }

  public static function getAllCollection(){
    return Mage::getModel('webhooks/application')->getCollection();
  }
}
?>
