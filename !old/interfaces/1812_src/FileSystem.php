<?php

namespace Qck\Interfaces;

/**
 * File Services with optional undo
 * @author muellerm
 */
interface FileSystem
{

  // constants for getFiles
  const FILES_AND_DIRECTORIES = 0;
  const ONLY_FILES            = 1;
  const ONLY_DIRECTORIES      = 2;

  /**
   * 
   * @param string $BasePath
   * @param string $FileName
   */
  function join( $BasePath, $FileName );

  /**
   * 
   * @param string $Dir
   * @param \Qck\Interfaces\FileFactory $FileFactory
   * @param int $Mode: 0 means files and folders, 1=only files, 2=only dirs
   * @param bool $Recursive
   * @param mixed $Extensions array or string or null of file extensions without dot. has no effect if mode is 2
   * @param int $MaxFiles if not null, the function will stop after finding $MaxFiles
   * @return File[] File array
   */
  function getFiles( $Dir, $Mode = FileSystem ::FILES_AND_DIRECTORIES, $Recursive = true, $Extensions = null, $MaxFiles = null );

  /**
   * @return FileFactory
   */
  function getFileFactory();

  /**
   * Get the size of a folder and all containing files (recursively)
   * @param string $dir
   */
  function getFolderSize( $Dir );

  /**
   * Creates a directory if it not exists (recursively). If it exists nothing will happen. 
   * @param string $Path relative or absolute path
   * @param bool $DeleteIfExists whether to delete the Dir if it exists and create it afterwards
   */
  function createDir( $Path, $DeleteIfExists = false );

  /**
   * Creates an empty file with an arbitrary name.
   * @param string $NamePrefix
   * @param string $Ext
   * @param string $Dir if null the sys temp dir will be used. If $Dir does not exist it will be created
   * @return File
   */
  function createRandomFile( $NamePrefix = null, $Ext = null, $Dir = null );

  /**
   * Creates a a named file
   * @param string $Name
   * @param string $Dir if null the current working dir will be used. If $Dir does not exist it will be created
   * @param bool $DeleteIfExists whether to delete the file if it exists and create it afterwards
   * @return File
   */
  function createFile( $Name, $Dir = null, $DeleteIfExists = false );

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

  /**
   * writes to file assures parent directory is created
   * @param string $FilePath
   * @param string $Data
   */
  function writeToFile( $FilePath, $Data );
}
