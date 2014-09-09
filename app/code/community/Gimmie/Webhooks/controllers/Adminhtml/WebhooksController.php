<?php
class Gimmie_Webhooks_Adminhtml_WebhooksController extends Mage_Adminhtml_Controller_Action {

  public function indexAction() {
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/apps.phtml")
    );
    $this->_addContent($block);

    $this->renderLayout();
  }

  public function redirectToAppAction() {
    $appUrl = $this->getRequest()->getParams()["app"];
    $allowUrl = urlencode(Mage::helper("adminhtml")->getUrl("adminhtml/webhooks/allow"));

    $this->_redirectUrl("$appUrl?site=$allowUrl");
  }

  public function allowAction() {
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/allow.phtml")
    );
    $this->_addContent($block);

    $this->renderLayout();
  }

  public function addAppAction() {
    echo "Grant";
  }

  protected function _isAllowed() {
    return Mage::getSingleton('admin/session')->isAllowed('gimmie');
  }

}
?>
