<?php
class Gimmie_Webhooks_Model_Hooks {

  /**
   * Query all apps in database and get header block from app too
   * append to head block
   *
   */
  public function injectScripts(Varien_Event_Observer $observer = null) {
    $layout = $observer->getEvent()->getLayout();
    $head = $layout->getBlock("head");

    $applications = $this->getEnabledApps();
    foreach($applications as $application) {
      $scripts = $application->getScripts();
      $block = $layout->createBlock(
        "Mage_Core_Block_Template",
        "gimmie_scripts",
        array("template" => "gimmie/scripts.phtml")
      )->setData('scripts', $scripts);
      $head->append($block);
    }
  }

  public function dispatchRegisterSuccess(Varien_Event_Observer $observer = null) {
    $data = $this->getBaseData($observer);
    Mage::log("JSON: ".json_encode($data));
  }

  public function dispatchLoginSuccess(Varien_Event_Observer $observer = null) {
    $data = $this->getBaseData($observer);
    Mage::log("JSON: ".json_encode($data));
  }

  public function dispatchViewItem(Varien_Event_Observer $observer = null) {
    $data = $this->getBaseData($observer);
    Mage::log("JSON: ".json_encode($data));
  }

  public function dispatchPurchaseItem(Varien_Event_Observer $observer = null) {
    $this->debug($observer);
  }

  private function getBaseData($observer) {
    $session = Mage::getSingleton('customer/session');
    $base = array( "session" => $session->getSessionId() );

    if ($session->isLoggedIn()) {
      $customer = $session->getCustomer();
      $user = array(
        "id" => $customer->getId(),
        "name" => $customer->getName(),
        "email" => $customer["email"]
      );
      $base["user"] = $user;
    }
    return $base;
  }

  private function debug($observer) {
    Mage::log(print_r($observer->getCustomer(), true));

    $session = Mage::getSingleton('customer/session');
    Mage::log($session->getSessionId());

    if ($session->isLoggedIn()) {
      $customer = $session->getCustomer();
      Mage::log(print_r($customer, true));
    }
  }

  private function getEnabledApps() {
    $applications = Mage::getModel('webhooks/application')->getCollection();
    $applications->addFilter('enable', true);
    return $applications;
  }

}
?>
