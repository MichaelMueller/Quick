<?php
namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface FileInfoService
{
  /**
   * create a unique name for a file in this directory. this will not create a file but just a "free" name!
   * @param type $dir
   * @param type $ext
   * @param type $prefix
   */
  function createUniqueFileName($dir=null, $ext=null, $prefix=null);
  
  /**
   * get all files in dir. gives full paths
   * @param string $Dir
   * @param bool $Recursive
   * @param bool $OnlyFiles will skip directories in result list
   */
  function getFiles( $Dir, $Recursive=false, $OnlyFiles=false );
  
  
  /**
   * get the size of a folder and all containing files (recursively)
   * @param type $dir
   */
  function getFolderSize($dir);
  
  /**
   * get the contents of a file using file locking
   * @param string $filePath
   * @return string the data contained or null if file does not exist
   */
  function getContents($filePath);
}
