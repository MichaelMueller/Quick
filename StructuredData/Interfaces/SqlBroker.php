<?php

namespace qck\StructuredData\Interfaces;

use \qck\Sql\Interfaces\Db as SqlDb;

/**
 *
 * @author muellerm
 */
interface SqlBroker
{

  /**
   * 
   * @param \qck\Sql\Interfaces\Schema $Schema
   */
  function addToSchema( \qck\Sql\Interfaces\DbSchema $DbSchema );

  /**
   * 
   * @param SqlDb $Db
   * @param \qck\Data\Interfaces\PersistableNode $Node
   * @return The currently known version in the Database or -1 if it is unknown
   */
  function getVersion( SqlDb $Db, $Id );

  /**
   * Will Update the Version on this Node!
   * @param \qck\Data\Interfaces\PersistableNode $Node
   */
  function insert( SqlDb $Db, \qck\Data\Interfaces\PersistableNode $Node );

  /**
   * 
   * @param \qck\Data\Interfaces\PersistableNode $Node
   */
  function update( SqlDb $Db, \qck\Data\Interfaces\PersistableNode $Node );

  /**
   * 
   * @param SqlDb $Db
   * @param int $Id
   * @param \qck\Data\Interfaces\NodeDb $NodeDb
   */
  function load( SqlDb $Db, $Id, \qck\Data\Interfaces\NodeDb $NodeDb );

  /**
   * 
   * @param \qck\Expressions\Interfaces\Expression $Expression
   */
  function delete( SqlDb $Db, $Id );

  /**
   * 
   * @param \qck\StructuredData\Interfaces\Select $NodeSelect
   * @return array of Ids
   */
  function select( SqlDb $Db, \qck\Expressions\Interfaces\Expression $Expression,
                   $Offset = null, $Limit = null, $OrderCol = null, $Descending = true );
}
