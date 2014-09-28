<?php
class Gimmie_Webhooks_AppController extends Mage_Core_Controller_Front_Action {
  public function getJsonData(){
     return json_decode(file_get_contents('php://input'), true);
  }
  public function saveApplication($value){ 
    // TODO: If domain is already exists, what to do next?
    Mage::log("Saving " . $value['app']['domain']);
    $application = Mage::getModel('webhooks/application');
    $application->setDomain($value['app']['domain']);
    $application->setTitle($value['app']['title']);
    $application->setDescription($value['app']['description']);
    $application->setLogo($value['app']['logo']);
    $application->setEvents(json_encode($value['events']));
    $application->setScripts(json_encode($value['scripts']));
    $application->setSecret($value['secret']);
    $application->save();
  }

  public function getApplication($secret){
    return $app = Gimmie_Webhooks_Model_Application::getBySecret($secret);
  }

  public function removeAction(){
    $key= $this->getRequest()->getParams()['key'];
    $newParams= array('key'=>$key);

    //remove app by secret
    $secret = $this->getRequest()->getParams()['secret'];
    try {
      $app = $this->getApplication($secret);
      Mage::log(print_r($app->getEventsObject(), true));
      $events = $app->getEventsObject();
      if (array_key_exists("uninstall", $events)) {
        $helper = Mage::helper('gimmie_webhooks');
        $helper->send($events["uninstall"], array(
          "app" => array(
            "domain" => $app->getDomain()
          )
        ));
      }
      $app->delete();
    } catch (Exception $e){
      $newParams['error'] = "Error removing application: {$e->getMessage()}";
      Mage::logException($e);
    }
    $AppListUrl = Mage::getUrl("adminhtml/webhooks/index", $newParams);
    $this->_redirectUrl($AppListUrl); 
  }

  public function registerAction() {
    $this->getResponse()->setHeader('Content-type', 'application/json');
    
    $params = $this->getRequest()->getParams();

    if (!array_key_exists('secret_url', $params) || $params["secret_url"] =="") {
      $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
        "success" => false,
        "error" => array("message" => "Missing Magento url secret keys.")
      )));
      return;
    }

    $secretUrl = $params["secret_url"];
    $value = $this->getJsonData();
    if($value==NULL){
      $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
        "success" => false,
        "error" => array("message" => "POST data could not be decoded. Please make sure json is valid.")
      )));
      return;
    }

    $matches = array();
    preg_match('/key\/([0-9a-f]+)\//', $secretUrl, $matches);
    $secret = $matches[1];

    $value["secret"] = $secret;

    $this->saveApplication($value);
    
    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
      "success" => true
    )));
  }

}
?>
