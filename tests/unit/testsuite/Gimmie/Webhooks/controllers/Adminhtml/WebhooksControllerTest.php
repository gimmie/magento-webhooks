<?php
require_once('Gimmie/Webhooks/controllers/Adminhtml/WebhooksController.php');
class Gimmie_Webhooks_Adminhtml_WebhooksController_Test extends PHPUnit_Framework_Testcase{
  private $_res;
  public function setUp()
  {
    $this->controller = $this->getMock('Gimmie_Webhooks_Adminhtml_WebhooksController', array('_setActiveMenu', 'getRequest', 'getResponse', '_redirect'));
    $this->controller->expects($this->any())->method('_setActiveMenu')
      ->will($this->returnValue( null ));

    $this->_res = new Mage_Core_Controller_Response_Http;
    $this->controller->expects($this->any())->method('getResponse')
      ->will($this->returnCallback(function(){
        return $this->_res;
      }));
  }
  
  public function setRequestParams($params){
    $req= new Mage_Core_Controller_Request_Http; 
    $req->setParams($params);
    $this->controller->expects($this->any())->method('getRequest')
      ->will($this->returnValue($req));
  }

  public function testAllowAction_ShouldRedirectWhenAppNotFound(){
    $key = "non-existent-key";
    $params = Array('key'=>$key);
    $this->setRequestParams($params);
    $this->controller->expects($this->once())->method('_redirect')
      ->with("/webhooks/index/key/$key");
    $this->controller->allowAction();
  }
}
?>
