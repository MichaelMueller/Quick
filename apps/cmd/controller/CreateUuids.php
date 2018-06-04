<?php

namespace qck\apps\cmd\controller;

/**
 * Description of HelloWorld
 *
 * @author muellerm
 */
class CreateUuids implements \qck\core\interfaces\Controller
{

  //put your code here
  public function run( \qck\core\interfaces\AppConfig $config )
  {
    $Argv = $config->getControllerFactory()->getArgv();
    $count = 10;
    if ( count( $Argv ) > 2 )
      $count = intval( $Argv[ 2 ] );

    for ( $i = 0; $i < $count; $i++ )
      print \Ramsey\Uuid\Uuid::uuid4()->toString().PHP_EOL;
  }
}
