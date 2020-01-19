<?php

namespace qck\ext\abstracts;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig extends \qck\core\abstracts\AppConfig
{

  /**
   * @return \qck\ext\interfaces\Session
   */
  function getSession()
  {
    return $this->getSingleton( "Session", function()
        {
          return new \qck\ext\Session( $this->getClientInfo(), $this->getCacheSubDir( "sessions" ) );
        } );
  }

  /**
   * @return \qck\ext\interfaces\ClientInfo
   */
  function getClientInfo()
  {
    return $this->getSingleton( "ClientInfo", function()
        {
          return new \qck\ext\ClientInfo();
        } );
  }

  /**
   * @return \qck\ext\interfaces\FileService
   */
  function getFileService()
  {
    return $this->getSingleton( "FileService", function()
        {
          return new \qck\ext\FileService();
        } );
  }
}
