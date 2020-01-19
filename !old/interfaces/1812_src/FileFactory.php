<?php

namespace Qck\Interfaces;

/**
 * Interface for creating a file object
 * @author muellerm
 */
interface FileFactory
{

  /**
   * 
   * @param string $DirPath
   * @param string $FileBaseName
   * @return File A File Object
   */
  function createFileObject($DirPath, $FileBaseName);

  /**
   * 
   * @param string $FilePath
   * @return File A File Object
   */
  function createFileObjectFromPath($FilePath);
}
