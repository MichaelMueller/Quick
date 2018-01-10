<?php
namespace qck\ext\interfaces;

/**
 *
 * @author muellerm
 */
interface FileTrash 
{
  /**
   * saves a file or directory to be deleted later (e.g. temp files), this will have
   * no influence on rollback behaviour
   * @param type $dirOrFile
   */
  function deleteLater($dirOrFile);
  
  /**
   * execute garbage collection
   */
  function run();
}
