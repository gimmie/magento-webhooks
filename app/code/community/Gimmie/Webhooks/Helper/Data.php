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

  public function prepareProductArray(Mage_Catalog_Model_Product $product) {
    return array(
      "id" => $product->getId(),
      "name" => $product->getName(),
      "url" => $product->getProductUrl(),
      "price" => $product->getFinalPrice(),
      "created_at" => Mage::getModel('core/date')->date(DATE_W3C, $product->getCreatedAt()),
      "updated_at" => Mage::getModel('core/date')->date(DATE_W3C, $product->getUpdatedAt()),
      "isSaleable" => $product->isSaleable(),
      "isInStock" => $product->isInStock()
    );
  }

  public function debug($data, $level=4) {
    static $innerLevel = 1;
    static $tabLevel = 1;
    static $cache = array();
    
    $type       = gettype($data);
    $tabs       = str_repeat('    ', $tabLevel);
    $quoteTabes = str_repeat('    ', $tabLevel - 1);

    $recrusiveType = array('object', 'array');

    // Recrusive
    if (in_array($type, $recrusiveType))
    {
        // If type is object, try to get properties by Reflection.
        if ($type == 'object')
        {
            if (in_array($data, $cache))
            {
                return "\n{$quoteTabes}*RECURSION*\n";
            }
            
            // Cache the data
            $cache[] = $data;
            
            $output     = get_class($data) . ' ' . ucfirst($type);
            $ref        = new \ReflectionObject($data);
            $properties = $ref->getProperties();
            
            $elements = array();
            
            foreach ($properties as $property)
            {
                $property->setAccessible(true);
                
                $pType = $property->getName();
                
                if ($property->isProtected())
                {
                    $pType .= ":protected";
                }
                elseif ($property->isPrivate())
                {
                    $pType .= ":" . $property->class . ":private";
                }
                
                if ($property->isStatic())
                {
                    $pType .= ":static";
                }
                
                $elements[$pType] = $property->getValue($data);
            }
        }
        // If type is array, just retun it's value.
        elseif ($type == 'array')
        {
            $output = ucfirst($type);
            $elements = $data;
        }
        
        // Start dumping datas
        if ($level == 0 || $innerLevel < $level)
        {
            // Start recrusive print
            $output .= "\n{$quoteTabes}(";
            
            foreach ($elements as $key => $element)
            {
                $output .= "\n{$tabs}[{$key}] => ";
                
                // Increment level
                $tabLevel = $tabLevel + 2;
                $innerLevel++;
                
                $output  .= in_array(gettype($element), $recrusiveType) ? $this->debug($element, $level) : $element;
                
                // Decrement level
                $tabLevel = $tabLevel - 2;
                $innerLevel--;
            }
            
            $output .= "\n{$quoteTabes})\n";
        }
        else
        {
            $output .= "\n{$quoteTabes}*MAX LEVEL*\n";
        }
    }
    
    // Clean cache
    if($innerLevel == 1)
    {
        $cache = array();
    }
    
    return $output;
  }

}
?>
