<?php
require_once('code/community/Gimmie/Webhooks/controllers/AppController.php');
class Gimmie_Webhooks_AppController_Spec extends PHPUnit_Framework_Testcase{
  public function setUp()
  {
    $this->controller = $this ->getMock('Gimmie_Webhooks_AppController', array('getRequest', 'getResponse', 'saveApplication', 'getJsonData'));

    $res = new Mage_Core_Controller_Response_Http; 
    $this->controller->expects($this->any())->method('getResponse')
      ->will($this->returnValue($res));
    $this->controller->expects($this->any())->method('saveApplication')
      ->will($this->returnValue("Saved"));
  }
  
  public function setJSONDataFixture($json){
    $this->controller->expects($this->any())->method('getJsonData')
      ->will($this->returnValue(json_decode($json, true)));
  }

  public function setRequestParams($params){
    $req= new Mage_Core_Controller_Request_Http; 
    $req->setParams($params);
    $this->controller->expects($this->any())->method('getRequest')
      ->will($this->returnValue($req));
  }

  public function testRegisterValidJson(){
    //Everything valid:
    $data='
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
    $this->setJSONDataFixture($data);
    $params = Array('secret_url'=>'SECRET');
    $this->setRequestParams($params);
    $this->controller->registerAction();
//    assert "success"=>"true" 
  }
}
?>
