<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface Path
{

  /**
   * @return string the Path extension. no dot. if this is a dir null will be returned
   */
  function getExtension();

  /**
   * @return string the Path basename, i.e. the Pathname without extension
   */
  function getBasename();

  /**
   * @return string the Path name
   */
  function getFileName();

  /**
   * @return string the Path's path
   */
  function getPath();

  /**
   * @return int the size of the Path
   */
  function getSize();

  /**
   * @return string the Path's path
   */
  function getParentDir();

  /**
   * @return bool if this is a dir
   */
  function isDir();

  /**
   * Get the contents of a Path using Path locking
   * @param string $FilePath
   * @return string the data contained or null if Path does not exist
   */
  function readContents();

  /**
   * Writes data to a Path (overwrite or create) with an exclusive lock
   * @param string $data
   */
  function writeContents( $data );

  /**
   * @return bool
   */
  function exists();

  /**
   * @return void
   */
  function deleteIfExists();

  /**
   * @return void
   */
  function touch();
}
