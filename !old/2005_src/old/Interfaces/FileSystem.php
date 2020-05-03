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
   * @param \Qck\Interfaces\PathFactory $PathFactory
   * @param int $Mode: 0 means files and folders, 1=only files, 2=only dirs
   * @param bool $Recursive
   * @param mixed $Extensions array or string or null of file extensions without dot. has no effect if mode is 2
   * @param int $MaxFiles if not null, the function will stop after finding $MaxFiles
   * @return Path[] Path array
   */
  function getFiles( $Dir, $Mode = FileSystem ::FILES_AND_DIRECTORIES, $Recursive = true, $Extensions = null, $MaxFiles = null );

  /**
   * @return PathFactory
   */
  function getPathFactory();

  /**
   * Get the size of a folder and all containing files (recursively)
   * @param string $dir
   */
  function getFolderSize( $Dir );

  /**
   * Delete everything in the folder but not the folder itself
   * @param string $path
   */
  function clearFolder( $path );

}
