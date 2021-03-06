<?php
/**
 * Register application controller from #2
 *
 */
class Gimmie_Webhooks_Adminhtml_WebhooksController extends Mage_Adminhtml_Controller_Action {

  /**
   * App listing page and input field to submit url
   * to install new app.
   *
   */
  public function indexAction() {
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');

    $applications = Gimmie_Webhooks_Model_Application::getAllCollection();

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/apps.phtml")
    )->setData('apps', $applications);
    $this->_addContent($block);

    $this->renderLayout();
  }

  /**
   * Redirect user to app action on step 5) in #2
   *
   */
  public function redirectToAppAction() {
    $appUrl = $this->getRequest()->getParams()["app"];

    $returnUrl = urlencode(Mage::helper("adminhtml")->getUrl("adminhtml/webhooks/allow"));
    $registerAppUrl = urlencode(Mage::getUrl('webhooks/app/register'));

    $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
    $session->setData("appUrl", $appUrl);

    $this->_redirectUrl("$appUrl?magento[return_url]=$returnUrl&magento[register_app_url]=$registerAppUrl");
  }

  /**
   * Authorize app page on step 12) in #2
   */
  public function allowAction() {
    $key = $this->getRequest()->getParams()["key"];
    
    // Query app from key
    try {
      $application = Gimmie_Webhooks_Model_Application::getBySecret($key);
    } catch (Exception $e){
      //If app is not found, redirect back to add application page.
      $this->_redirect("/webhooks/index/key/$key", array('error' => "Failed to link the application: {$e->getMessage()}"));
      Mage::logException($e);
      return;
    }

    $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
    $session->setData('app', $application);
    
    $this->loadLayout();
    $this->_setActiveMenu('gimmie/webhooks');

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/allow.phtml")
    )->setData('app', $application);
    $this->_addContent($block);

    $this->renderLayout();
  }

  /**
   * User denied installing app on 13) in #2
   *
   */
  public function deniedAppInstallAction() {
    $returnUrl = urlencode(Mage::helper("adminhtml")->getUrl("adminhtml/webhooks/allow"));
    $registerAppUrl = urlencode(Mage::getUrl('webhooks/app/register'));

    $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
    $appUrl = $session->getData("appUrl");
    $app = $session->getData('app');
    $app->delete();

    $session->unsetData('app');
    $session->unsetData('appUrl');

    $this->_redirectUrl("$appUrl?magento[success]=0&magento[return_url]=$returnUrl&magento[register_app_url]=$registerAppUrl");
  }

  /**
   * User grant installing app on 14) in #2
   */
  public function grantAppInstallAction() {
    $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
    $appUrl = $session->getData("appUrl");
    $app = $session->getData('app');
    $app->setEnable(true);
    $app->save();

    $session->unsetData('app');
    $session->unsetData('appUrl');

    $this->_redirectUrl("$appUrl?magento[success]=1");
  }

  protected function _isAllowed() {
    return Mage::getSingleton('admin/session')->isAllowed('gimmie');
  }

}
?>
