<?php
class Gimmie_Webhooks_AppController extends Mage_Core_Controller_Front_Action {

  public function registerAction() {
    $appUrl = $this->getRequest()->getParams()["secret_url"];
    $value = json_decode(file_get_contents('php://input'));

    // Secret Url - http://magento.llun.in/index.php/admin/webhooks/allow/key/5d2e72d06eb11266457188c2c6f9fcd4/
    // Value from #6 - {
    //    app: {
    //    },
    //    events: {
    //    },
    //    scripts: []
    // }
    // Extract key from the url and put to database with value, if success return json in #6
    //
    $this->getResponse()->setHeader('Content-type', 'application/json');
    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
      "success" => true
    )));
  }

}
?>
