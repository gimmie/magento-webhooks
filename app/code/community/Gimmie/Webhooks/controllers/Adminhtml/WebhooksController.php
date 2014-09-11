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

    $block = $this->getLayout()->createBlock(
      "Mage_Core_Block_Template",
      "webhooks_admin",
      array("template" => "gimmie/apps.phtml")
    );
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

    $this->_redirectUrl("$appUrl?magentoreturn_url=$returnUrl&magentoregister_app_url=$registerAppUrl");
  }

  /**
   * Authorize app page on step 12) in #2
   */
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
