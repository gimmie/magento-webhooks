<?php
class Gimmie_Webhooks_Model_ApplicationTest extends PHPUnit_Framework_Testcase{
  public function setUp(){
  }

  public function testGetByKey_shouldReturnAnEmptyApplication(){
    $key = "non_existent_key";
    $this->setExpectedException('Exception', "Application with key $key does not exist.");
    $app = Gimmie_Webhooks_Model_Application::getByKey($key);
  }

}
?>
