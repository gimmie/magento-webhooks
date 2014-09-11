<?php
class Gimmie_Webhooks_AppController extends Mage_Core_Controller_Front_Action {

  public function registerAction() {
    $params = $this->getRequest()->getParams();

    if (!array_key_exists('secret_url', $params)) {
      $this->getResponse()->setHeader('Content-type', 'application/json');
      $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
        "success" => false 
      )));
      return;
    }

    $secretUrl = $params["secret_url"];
    $value = json_decode(file_get_contents('php://input'), true);

    $matches = array();
    preg_match('/key\/([0-9a-f]+)\//', $secretUrl, $matches);

    $key = $matches[1];

    // TODO: If key or domain is already exists, what to do next?
    $application = Mage::getModel('webhooks/application');
    $application->setDomain($value['app']['domain']);
    $application->setTitle($value['app']['title']);
    $application->setDescription($value['app']['description']);
    $application->setLogo($value['app']['logo']);
    $application->setEvents(json_encode($value['events']));
    $application->setScripts(json_encode($value['scripts']));
    $application->setSecret($key);
    $application->save();

    $this->getResponse()->setHeader('Content-type', 'application/json');
    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
      "success" => true
    )));
  }

}
?>
