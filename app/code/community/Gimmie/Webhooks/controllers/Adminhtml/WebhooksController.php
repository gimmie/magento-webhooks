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

    $this->_redirectUrl("$appUrl?magento=$allowUrl");
  }

  public function allowAction() {
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');

    $key = $this->getRequest()->getParams()["key"];
    // Query app from key
    $app = array(
      "domain" => "gimmie.io",
      "title" => "Loyalty app for your ecommerce site",
      "description" => "Long long text field in database",
      "logo" => "http://placehold.it/350x150&text=My+App",
      "events" => array(
        "register" => "http://gimmie.io/trigger/register",
        "login" => "http://gimmie.io/trigger/login"
      ),
      "scripts" => array("http://gimmie.io/embed/1.js", "http://gimmie.io/embed/2.js")
    );

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/allow.phtml")
    )->setData('app', $app);
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
