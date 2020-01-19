<?php

namespace Qck\Interfaces;

/**
 * File Services with optional undo
 * @author muellerm
 */
interface FileSystem
{

  /**
   * Get all Files (and subdirectories) in dir
   * @param string $Dir
   * @param bool $Recursive
   */
  function getAllFiles( $Dir, $Recursive = true );

  /**
   * Get specific Files based on the Extension
   * @param string $Dir
   * @param string[] $Extensions a set of extensions. Please DO NOT INCLUDE THE DOT, e.g. ["jpg", "jpeg", "png"] etc.
   * @param bool $Recursive
   */
  function getFiles( $Dir, $Extensions, $Recursive = true );

  /**
   * Get the size of a folder and all containing files (recursively)
   * @param string $dir
   */
  function getFolderSize( $Dir );

  /**
   * Get the contents of a file using file locking
   * @param string $FilePath
   * @return string the data contained or null if file does not exist
   */
  function getContents( $FilePath );

  /**
   * Creates a directory if it not exists (recursively). If it exists nothing will happen. 
   * @param string $Path relative or absolute path
   * @param bool $DeleteIfExists whether to delete the Dir if it exists and create it afterwards
   */
  function createDir( $Path, $DeleteIfExists = false );

  /**
   * Creates an empty random file.
   * @param string $NamePrefix
   * @param string $Ext
   * @param string $Dir if null the sys temp dir will be used. If $Dir does not exist it will be created
   * @return string The File Path
   */
  function createRandomFile( $NamePrefix = null, $Ext = null, $Dir = null );

  /**
   * Creates a a named file
   * @param string $Name
   * @param string $Dir if null the current working dir will be used. If $Dir does not exist it will be created
   * @param bool $DeleteIfExists whether to delete the file if it exists and create it afterwards
   * @return string the file name 
   */
  function createFile( $Name, $Dir = null, $DeleteIfExists = false );

  /**
   * Writes data to a file (overwrite or create) with an exclusive lock
   * @param string $filePath
   * @param string $data
   */
  function writeFile( $filePath, $data );

  /**
   * Removes the file or the complete folder (RECURSIVELY!)
   * @param string $path
   * @return bool false if the path not existed, true otherwise
   */
  function delete( $path );

  /**
   * Delete everything in the folder but not the folder itself
   * @param string $path
   */
  function clearFolder( $path );

  /**
   * Will move a file or folder to another location
   * @param string $Path
   * @param string $NewPath - if the containing folder does not exist it will be created
   * @return bool false if the path not existed, true otherwise
   */
  function move( $Path, $NewPath );

  /**
   * will copy a file or folder to another locations
   * @param string $Path
   * @param string $NewPath - if the containing folder does not exist it will be created
   * @return bool false if the path not existed, true otherwise
   */
  function copy( $Path, $NewPath );
}
