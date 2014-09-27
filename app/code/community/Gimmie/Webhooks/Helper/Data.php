<?php
class Gimmie_Webhooks_Helper_Data extends Mage_Core_Helper_Abstract {

  const DIR_IMAGE = 'image';
  const DIR_CSS = 'css';
  const DIR_JS = 'js';

  public function getExtPubDir($type) {
    return __DIR__.DS.'..'.DS.DS.'public'.DS.$type;
  }

  public function send($url, $jsonArray) {
    Mage::log("Send to $url with ".print_r($jsonArray, true));

    $jsonString = json_encode($jsonArray);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: '.strlen($jsonString)
    ));
    curl_exec($ch);
    curl_close($ch);
  }

}
?>
