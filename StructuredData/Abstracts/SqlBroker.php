<?php

namespace qck\StructuredData\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class SqlBroker implements \qck\StructuredData\Interfaces\SqlBroker
{

  abstract protected function getFqcn();

  protected function getTableName()
  {
    return str_replace( "\\", "_", $this->getFqcn() );
  }

  function delete( \qck\Sql\Interfaces\Db $Db, $Id )
  {
    $Db->delete( $this->getTableName(), new \qck\Expressions\IdEquals( $Id ) );
  }
}
