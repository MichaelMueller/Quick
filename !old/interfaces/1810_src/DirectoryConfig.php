<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface DirectoryConfig extends LocalDataDirProvider
{

  /**
   * @return string
   */
  function getBaseDir();

  /**
   * @return string
   */
  function getDataDir( $createIfNotExists = true );


  /**
   * @return string
   */
  function getLocalDataDir( $createIfNotExists = true );

  /**
   * @return string
   */
  function getTmpDir( $createIfNotExists = true );

  /**
   * @return string
   */
  function getTmpSubDir( $DirName, $createIfNotExists = true );
}
