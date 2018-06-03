<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Dbms
{

  /**
   * @param string $Name
   */
  function createDatabase( $Name );

  /**
   * 
   * @param string $Name
   * @return Db
   */
  function connectToDatabase( $Name );

  /**
   * 
   * @param Db $Db
   * @param string $NewName
   */
  function renameDatabase( Db $Db, $NewName );

  /**
   * 
   * @param Db $Db
   */
  function dropDatabase( Db $Db );

  /**
   * will dump a sql file to the disk. The file will be zipped if $Zip is activated
   * @param string $Name
   * @param string $File
   * @param bool $Zip
   */
  function dumpDatabase( $Name, $File, $Zip = false );
}
