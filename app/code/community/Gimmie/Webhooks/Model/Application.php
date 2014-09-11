<?php
class Gimmie_Webhooks_Model_Application extends Mage_Core_Model_Abstract {
  public function _construct() {
    parent::_construct();
    $this->_init('webhooks/application');
  }
}
?>
