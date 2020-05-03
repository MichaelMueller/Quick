<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface Dbms
{

  /**
   * Connect to a Database. If the Database does not exist, a new one will be created
   * using the DbSchema provided.
   * 
   * @param string $DbName
   * @param Schema $DbSchema
   * @param bool $CheckSchema If true the DBMS will try to check the schema by any means before any operation happens.
   * @throws \InvalidArgumentException if the Schema Check fails
   * @return \Qck\Interfaces\Sql\Db
   */
  function connect( $DbName, Schema $DbSchema, $CheckSchema = true );

  /**
   * @param string $DbName
   * @return bool
   */
  function exists( $DbName );

  /**
   * Will rename the Database. If a Connection is open to the Db it will be closed before.
   * @param Db $Db
   * @param string $NewName
   */
  function rename( Db $Db, $NewName );

  /**
   * Will drop the Database. If a Connection is open to the Db it will be closed before.
   * @param Db $Db
   */
  function drop( Db $Db );

  /**
   * will dump a sql file to the disk. The file will be zipped if $Zip is activated
   * @param string $Name
   * @param string $File
   * @param bool $Zip
   */
  function dump( $Name, $File );
}
