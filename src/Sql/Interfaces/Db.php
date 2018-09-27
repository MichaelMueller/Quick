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
   * @return void
   */
  function query( Convertable $Sql );

  /**
   * @return mixed Will return last id for inserts, affected rows for updates and deletes, data rows for select
   */
  function execute( Query $Query );

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
