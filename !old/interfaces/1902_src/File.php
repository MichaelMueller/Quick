<?php

namespace Qck\Interfaces;

/**
 * Interface for a file object
 * @author muellerm
 */
interface File
{

  /**
   * @return string the file extension. no dot. if this is a dir null will be returned
   */
  function getExtension();

  /**
   * @return string the file basename, i.e. the filename without extension
   */
  function getBasename();

  /**
   * @return string the file name
   */
  function getFileName();

  /**
   * @return string the file's path
   */
  function getPath();

  /**
   * @return int the size of the file
   */
  function getSize();

  /**
   * @return string the file's path
   */
  function getParentDir();

  /**
   * @return bool if this is a dir
   */
  function isDir();

  /**
   * Get the contents of a file using file locking
   * @param string $FilePath
   * @return string the data contained or null if file does not exist
   */
  function readContents();

  /**
   * Writes data to a file (overwrite or create) with an exclusive lock
   * @param string $filePath
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
