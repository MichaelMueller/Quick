<?php

namespace Qck\Core;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class CoreController implements \Qck\Interfaces\Controller
{

  abstract protected function proxyRun();

  function run( Qck\Interfaces\AppConfig $config )
  {
    $this->AppConfig = $config;
    return $this->proxyRun();
  }

  function getParam($NameOrIndex, $Default=null)
  {
    $Params = [];
    if($this->getAppConfig()->isCli())
    {
      // first arg: name of script, second arg: name of controller
      for ( $i = 1; $i < count( $this->Argv ); $i++ )
      {
        if ( isset( $this->Argv[ $i ][ 0 ] ) && $this->Argv[ $i ][ 0 ] == "-" )
          $i = $i + 2;
        else
        {
          $Query = $this->Argv[ $i ];
          break;
        }
      }
      
    }
  }
  
  function redirect( $ControllerName, $args = array () )
  {
    header( "Location: " . $this->getAppConfig()->getRouter()->getLink( $ControllerName, $args ) );
    return null;
  }

  /**
   * @return Qck\Interfaces\AppConfig
   */
  function getAppConfig()
  {
    return $this->AppConfig;
  }

  /**
   * @var Qck\Interfaces\AppConfig
   */
  protected $AppConfig;

}
