<?php

namespace qck\ext\interfaces;

/**
 * Basic interface for a logger
 *
 * @author muellerm
 */
interface Zip
{

  /**
   * 
   * unzip to a temp directory. Zip garantuees the directory to be deleted later (using FileTrash or similar)
   * @param string $zipFile
   * @param string $tempDir if null the sys temp dir is used
   * @return string the directory containing the zip contents
   */
  public function extract( $zipFile, $tempDir = null );
}
