<?php

namespace qck\ext\interfaces;

/**
 * File Services with optional undo
 * @author muellerm
 */
interface FileService extends FileInfoService
{

  /**
   * creates the dir if it not exists (recursively). if it exists nothing will happen. 
   * @param string $path relative or absolute path
   * @return bool false if the path not existed, true otherwise
   */
  function createDir( $path );

  /**
   * create an empty file. if it already exists nothing will happen. if the parent folder does not exist it will be created recursively.
   * @param string $path relative or absolute path
   * @return bool false if the path not existed, true otherwise
   */
  function createFile( $filePath );

  /**
   * writes data to a file (overwrite or create) with an exclusive lock
   * @param string $filePath
   * @param string $data
   */
  function writeFile( $filePath, $data );

  /**
   * removes the file or the complete folder (RECURSIVELY!)
   * @param string $path
   * @return bool false if the path not existed, true otherwise
   */
  function delete( $path );

  /**
   * delete everything in the folder but not the folder itself
   * @param string $path
   */
  function clearFolder( $path );

  /**
   * will move a file or folder to another location
   * @param string $path
   * @param string $newPath
   * @return bool false if the path not existed, true otherwise
   */
  function move( $path, $newPath );

  /**
   * will copy a file or folder to another locations
   * @param string $path
   * @param string $newPath
   * @return bool false if the path not existed, true otherwise
   */
  function copy( $path, $newPath );
}
