<?php 

/**
*  @author TOLGA KARABULUT
*/
class testClass extends PHPUnit_Framework_TestCase{
  public function testIsThereAnySyntaxError(){
  	$e = lspeak\identitycheck\identitycheck::algorithm();
    $this->assertTrue(is_object($e));
  	unset($e);
  }
}