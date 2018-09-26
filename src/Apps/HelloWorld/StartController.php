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
    /* @var $ResponseFactory \Qck\Interfaces\ResponseFactory */
    $ResponseFactory = $ServiceRepo->get( \Qck\Interfaces\ResponseFactory::class );
    /* @var $AppConfig \Qck\Interfaces\AppConfig */
    $AppConfig = $ServiceRepo->get( \Qck\Interfaces\AppConfig::class );

    print "Hello World on " . $AppConfig->getHostName();
    return $ResponseFactory->create();
  }
}
