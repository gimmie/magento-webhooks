<?php
class Gimmie_Webhooks_Model_Application extends Mage_Core_Model_Abstract {
  public function _construct() {
    parent::_construct();
    $this->_init('webhooks/application');
  }

  /*
   * @return Gimmie_Webhooks_Model_Application
   */
  public static function getByKey($key) {
    $applications = Mage::getModel('webhooks/application')->getCollection();
    $applications->addFilter('secret', $key);
    return $applications->getFirstItem();
  }
}
?>
