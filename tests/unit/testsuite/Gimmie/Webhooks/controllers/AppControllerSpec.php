<?php
require_once('code/community/Gimmie/Webhooks/controllers/AppController.php');
class Gimmie_Webhooks_AppController_Spec extends PHPUnit_Framework_Testcase{
  public function setUp()
  {
    $this->controller = $this ->getMock('Gimmie_Webhooks_AppController', array('getRequest', 'getResponse', 'getJsonData'));

    $req= new Mage_Core_Controller_Request_Http; 
    $req->setParams(Array('secret_url'=>'SECRET'));
    $this->controller->expects($this->any())->method('getRequest')
      ->will($this->returnValue($req));

    
    $res = $this->getMock('Mage_Core_Controller_Response_Http', array('setHeader', 'setBody'));
    $this->controller->expects($this->any())->method('getResponse')
      ->will($this->returnValue($res));
  }
  
  public function setValidFixture(){
    //Everything valid:
    $json='
      {
        "app":{
          "domain": "myapp.com",
          "title": "My Magento-friendly App",
          "description": "Lorem ipsum.. ",
          "logo": "http://placehold.it/350x150&text=My+App"
        },
        "events": {
          "login": "url",
          "register": "url"
        },
        "scripts": [ "url" ]
      }
      ';
    $this->controller->expects($this->any())->method('getJsonData')
      ->will($this->returnValue(json_decode($json, true)));
  }

  public function testRegisterAction(){
    $this->setValidFixture();
    $this->controller->registerAction();
  }
}
?>
