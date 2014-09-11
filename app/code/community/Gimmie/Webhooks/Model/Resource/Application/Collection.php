<?php
class Gimmie_Webhooks_Model_Resource_Application_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
  public function _construct() {
    $this->_init('webhooks/application');
  }
}
?>
