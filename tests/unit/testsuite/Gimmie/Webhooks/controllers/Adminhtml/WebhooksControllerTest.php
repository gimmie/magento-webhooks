<?php
class Gimmie_Webhooks_Adminhtml_WebhooksController_Test extends Zend_Test_PHPUnit_ControllerTestCase{
  public function setUp()
  {
    $this->bootstrap = array($this, 'appBootstrap');
    parent::setUp();
  }
  
  public function appBoostrap(){
    Mage::app()->getFrontController()->getRequest()
      ->setModuleName('admin')
      ->setControllerName('webhooks')
      ->setActionName('allow');
  }

  public function testFailure(){
    ob_start();
    try{
      Mage::app()->getFrontController()->dispatch();
      $contents = ob_get_clean();
      $this->assertTrue(False);
    } catch (Exception $e){
      ob_get_clean();
      throw $e;
    }
    
  }
}
?>
