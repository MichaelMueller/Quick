<?php
namespace Qck\Apps\HelloWorld;
/**
 * Description of StartController
 *
 * @author muellerm
 */
class StartController implements \Qck\Interfaces\Controller
{
  //put your code here
  public function run( \Qck\Interfaces\ServiceRepo $ServiceRepo )
  {    
    print "Hello World on .";
    /* @var $ResponseFactory \Qck\Interfaces\ResponseFactory */
    $ResponseFactory = $ServiceRepo->get(\Qck\ResponseFactory::class);    
    return $ResponseFactory->create();
  }
}
