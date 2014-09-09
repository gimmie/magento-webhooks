<?php
class Gimmie_Webhooks_StaticController extends Mage_Core_Controller_Front_Action {

  public function getAction() {

    $type = $this->getRequest()->getParam('t');
    switch($type) {
    case 'css':
    case 'image':
    case 'js':
      $file = $this->getRequest()->getParam('f');
      if (preg_match('/^\w+\.\w+$/', $file)) {
        $this->_render($type, $file);
        break;
      }
    default:
      $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
      $this->getResponse()->setHeader('Stauts', '404 File not found');
      break;
    }

  }

  private function _render($type, $file) {
    switch($type) {
    case 'css':
      $this->_css($file);
      break;
    case 'image':
      $this->_image($file);
      break;
    case 'js':
      $this->_js($file);
      break;
    }
  }

  private function _css($file) {
    $helper = Mage::helper('gimmie_webhooks');

    $response = $this->getResponse();
    $response->setHeader('Content-Type', 'text/css');
    $response->setBody(file_get_contents($helper->getExtPubDir($helper::DIR_CSS).DS.$file));
  }

  private function _image($file) {
    $helper = Mage::helper('gimmie_webhooks');

    $icon = new Varien_Image($helper->getExtPubDir($helper::DIR_IMAGE).DS.$file);
    $response = $this->getResponse();
    $response->setHeader('Content-Type', $icon->getMimeType());
    $response->setBody($icon->display());
  }

  private function _js($file) {
    $helper = Mage::helper('gimmie_webhooks');

    $response = $this->getResponse();
    $response->setHeader('Content-Type', 'application/javascript');
    $response->setBody(file_get_content($helper->getExtPubDir($helper::DIR_JS).DS.$file));
  }

}
?>
