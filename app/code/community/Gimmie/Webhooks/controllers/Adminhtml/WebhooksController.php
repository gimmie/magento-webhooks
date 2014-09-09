<?php
class Gimmie_Webhooks_Adminhtml_WebhooksController extends Mage_Adminhtml_Controller_Action {

  public function indexAction() {
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');
    $this->renderLayout();
  }

  protected function _isAllowed() {
    return Mage::getSingleton('admin/session')->isAllowed('gimmie');
  }

}
?>
