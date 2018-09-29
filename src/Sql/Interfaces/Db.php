<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Db
{

  const LOG_NO_QUERIES = 0;
  const LOG_ALL_QUERIES = 1;
  const LOG_WRITE_QUERIES = 2;

  /**
   * @return void
   */
  function beginTransaction();

  /**
   * @return DbDialect
   */
  function getDialect();

  /**
   * @return void
   */
  function isInTransaction();

  /**
   * Execute multiple queries
   * @param Query[] $Queries
   * @param bool $MakeTransaction if true beginTransaction() and commit() will be used
   * @return array an array of values coming from execute()
   */
  function executeMultiple( array $Queries, $MakeTransaction = true );

  /**
   * @return mixed Will return last id for inserts, affected rows for updates and deletes, data rows for select
   */
  function execute( Query $Query );

  /**
   * @return int the last inserted id
   */
  function getLastInsertedId( $TableName );

  /**
   * @return callable A function that will be invoked to get the last inserted id for tablename
   */
  function createLastInsertedIdWatch( Table $Table );

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

  /**
   * @return string[]
   */
  function getLoggedStatements();

  /**
   * @return void
   */
  function setLogQueries( $LogStatements = self::LOG_WRITE_QUERIES );
}
