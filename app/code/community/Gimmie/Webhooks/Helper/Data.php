<?php
class Gimmie_Webhooks_Helper_Data extends Mage_Core_Helper_Abstract {

  const DIR_IMAGE = 'image';
  const DIR_CSS = 'css';
  const DIR_JS = 'js';

  public function getExtPubDir($type) {
    return __DIR__.DS.'..'.DS.DS.'public'.DS.$type;
  }

}
?>
