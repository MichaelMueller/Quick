<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Db
{

  /**
   * @return void
   */
  function beginTransaction();

  /**
   * @return void
   */
  function isInTransaction();

  /**
   * @return mixed Will return last id for inserts, affected rows for updates and deletes, data rows for select
   */
  function execute( Convertable $Sql, &$LastInsertedId = null );

  /**
   * @return void
   */
  function commit();

  /**
   * @return void
   */
  function closeConnection();

  /**
   * @return string
   */
  function getName();
}
