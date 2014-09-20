<?php
require_once('Gimmie/Webhooks/controllers/AppController.php');
class Gimmie_Webhooks_AppController_Test extends PHPUnit_Framework_Testcase{
  /*
   * class variables used to store header and body that is written by controller.
   * assert the contents of response using $this->header and $this->body.
   */
  private $header;
  private $body;

  public function setUp()
  {
    //any functions expected to be called by the controller must be added to the array
    $this->controller = $this ->getMock('Gimmie_Webhooks_AppController',
      array('getRequest', 'getResponse', 'saveApplication', 'getJsonData', 'getApplication', '_redirectUrl'));

    //Mock response to write to local variables instead
    
    $res = $this->getMock('Mage_Core_Controller_Response_Http');//, array('setHeader', 'setBody')); 
    $res->expects($this->any())->method('setHeader')
      ->will($this->returnCallback(function($key, $value){
        $this->header[$key] = $value;
      }));
    $res->expects($this->any())->method('setBody')
      ->will($this->returnCallback(function($body){
        $this->body = $body;
      }));

    $this->controller->expects($this->any())->method('getResponse')
      ->will($this->returnValue($res));

    //Stub to avoid writing to database during test

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
    $params = Array('secret_url' => 'SECRET');
    $this->setRequestParams($params);
    $this->controller->registerAction();
    $this->assertEquals(array("Content-type" => "application/json"), $this->header);
    $this->assertEquals(Mage::helper('core')->jsonEncode(array("success" => true)), $this->body);
  }

  public function testRegisterInvalidJson(){
    //Invalid json:
    $invalid_data = json_decode("{'app':'test'}{}", true);
    $this->setJSONDataFixture($invalid_data);
    $params = Array('secret_url' => 'SECRET');
    $this->setRequestParams($params);
    $this->controller->registerAction();
    $this->assertEquals(array("Content-type" => "application/json"), $this->header);
    $this->assertEquals(Mage::helper('core')->jsonEncode(array("success" => false,
      "error" => array("message" => "POST data could not be decoded. Please make sure json is valid."))), $this->body);
  }

  public function testRegisterMissingSecret(){
    //Invalid json:
    $data = json_decode("{'app':'test'}", true);
    $this->setJSONDataFixture($data);
    $params = Array('secret_url' => '');
    $this->setRequestParams($params);
    $this->controller->registerAction();
    $this->assertEquals(array("Content-type" => "application/json"), $this->header);
    $this->assertEquals(Mage::helper('core')->jsonEncode(array("success" => false, 
    "error" => array("message" => "Missing Magento url secret keys."))), $this->body);
  }

  public function stubDeletingApplication(){
    $hookApp = $this->getMock('Gimmie_Webhooks_Model_Application', array('delete')); 
    $hookApp->expects($this->once())->method('delete')
      ->will($this->returnValue("Deleted"));
    $this->controller->expects($this->any())->method('getApplication')
      ->will($this->returnValue($hookApp));
  }

  public function stubMissingApplication(){
    $this->controller->expects($this->any())->method('getApplication')
      ->will($this->returnCallback( function($secret){
          return $app = Gimmie_Webhooks_Model_Application::getBySecret($secret);
      }));
  }

  public function testRemoveActionShouldRedirectToAppListIndex(){
    $params = Array('secret' => 'stubbed_deletable_app', 'key' => 'magento_key');
    $this->setRequestParams($params);
    $this->stubDeletingApplication();
    $expectedRedirectParams = Array('key'=>'magento_key');
    $this->controller->expects($this->once())->method('_redirectUrl')
      ->with(Mage::getUrl("adminhtml/webhooks/index", $expectedRedirectParams));
    $this->controller->removeAction();
  }
  public function testRemoveActionShouldRedirectToAppListIndexWithError(){
    $params = Array('secret' => 'Non-existing-app', 'key' => 'magento_key');
    $this->setRequestParams($params);
    $this->stubMissingApplication();
    $expectedRedirectParams = Array('key' => 'magento_key', 
      'error' => 'Error removing application: Application with key Non-existing-app does not exist.');
    $this->controller->expects($this->once())->method('_redirectUrl')
      ->with(Mage::getUrl("adminhtml/webhooks/index", $expectedRedirectParams));
    $this->controller->removeAction();
  }
}
?>
