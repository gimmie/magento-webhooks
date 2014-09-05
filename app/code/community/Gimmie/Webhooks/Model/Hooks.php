<?php
class Gimmie_Webhooks_Model_Hooks {

  public function injectScripts(Varien_Event_Observer $observer = null) {
    $layout = $observer->getEvent()->getLayout();
    $block = $layout->createBlock(
      "Mage_Core_Block_Template",
      "gimmie_scripts",
      array("template" => "gimmie/scripts.phtml")
    );

    $head = $layout->getBlock("head");
    $head->append($block);
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
