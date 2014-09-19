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
