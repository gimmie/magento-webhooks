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

    $applications = Mage::getModel('webhooks/application')->getCollection();
    $applications->addFilter('enable', true);
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
  }

  public function dispatchLoginSuccess(Varien_Event_Observer $observer = null) {
  }

  public function dispatchViewItem(Varien_Event_Observer $observer = null) {
  }

  public function dispatchPurchaseItem(Varien_Event_Observer $observer = null) {
  }

}
?>
