<?php

namespace Qck\Ext;

/**
 * Description of QckPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig extends \Qck\Core\AppConfig
{

  /**
   * @return \Qck\Interfaces\Session
   */
  function getSession()
  {
    return $this->getSingleton( "Session", function()
        {
          return new \Qck\Ext\Session( $this->getClientInfo(), $this->getCacheSubDir( "sessions" ) );
        } );
  }

  /**
   * @return \Qck\Interfaces\ClientInfo
   */
  function getClientInfo()
  {
    return $this->getSingleton( "ClientInfo", function()
        {
          return new \Qck\Ext\ClientInfo();
        } );
  }

  /**
   * @return \Qck\Interfaces\FileService
   */
  function getFileService()
  {
    return $this->getSingleton( "FileService", function()
        {
          return new \Qck\Ext\FileService();
        } );
  }
}
