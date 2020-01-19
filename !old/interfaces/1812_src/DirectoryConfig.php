<?php

namespace Qck\Interfaces;

/**
 * An interface providing important Project Directories
 * 
 * @author muellerm
 */
interface DirectoryConfig
{

  /**
   * 
   * @param string $SubFilePath
   * @return The Project Dir with a sub file path
   */
  function getProjectDir( $SubFilePath = null );

  /**
   * 
   * @param string $SubFilePath A path of a file within that dir
   * @param string $createIfNotExists if true the file will be created if it does not exist
   * @param string $IsDir If true a directory file will be created instead of a file
   * @return string the Data Dir (appended with SubFilePath) 
   */
  function getDataDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true );

  /**
   * 
   * @param string $SubFilePath A path of a file within that dir
   * @param string $createIfNotExists if true the file will be created if it does not exist
   * @param string $IsDir If true a directory file will be created instead of a file
   * @return string the Data Dir (appended with SubFilePath) 
   */
  function getLocalDataDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true );

  /**
   * 
   * @param string $SubFilePath A path of a file within that dir
   * @param string $createIfNotExists if true the file will be created if it does not exist
   * @param string $IsDir If true a directory file will be created instead of a file
   * @return string the Data Dir (appended with SubFilePath) 
   */
  function getTmpDir( $SubFilePath = null, $createIfNotExists = true, $IsDir = true );
}
