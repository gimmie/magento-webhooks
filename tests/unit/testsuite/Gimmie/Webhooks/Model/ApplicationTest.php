<?php
class Gimmie_Webhooks_Model_ApplicationTest extends PHPUnit_Framework_Testcase{
  public function setUp(){
  }

  public function testGetByKey_shouldReturnAnEmptyApplication(){
    $app = Gimmie_Webhooks_Model_Application::getByKey("non_existent_key");
    $this->assertEmpty($app->getData());
  }

}
?>
