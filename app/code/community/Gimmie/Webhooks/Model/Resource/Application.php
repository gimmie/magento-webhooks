<?php
class Gimmie_Webhooks_Model_Resource_Application extends Mage_Core_Model_Mysql4_Abstract {

  protected function _construct() {
    $this->_init('webhooks/application', 'application_id');
  }

}
?>
