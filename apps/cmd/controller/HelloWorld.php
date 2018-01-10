<?php
namespace qck\apps\cmd\controller;
/**
 * Description of HelloWorld
 *
 * @author muellerm
 */
class HelloWorld implements \qck\core\interfaces\Controller
{
  //put your code here
  public function run( \qck\core\interfaces\AppConfig $config )
  {
    print "Hello World";
  }

}
