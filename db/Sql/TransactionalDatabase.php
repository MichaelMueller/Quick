<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface TransactionalDatabase
{
  function beginTransaction();
  function commit();
}
