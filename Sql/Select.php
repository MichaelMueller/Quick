<?php

namespace qck\Sql;

/**
 *
 * @author muellerm
 */
class Select implements \qck\Sql\Interfaces\Select
{

  function __construct( $TableName,  \Qck\Interfaces\Expression $Expression )
  {
    $this->TableName = $TableName;
    $this->Expression = $Expression;
  }

  function setOrderParams( $OrderCol, $Descending = true )
  {
    $this->OrderCol = $OrderCol;
    $this->Descending = $Descending;
  }

  function setColumns( $Columns )
  {
    $this->Columns = $Columns;
  }

  function setOffset( $Offset )
  {
    $this->Offset = $Offset;
  }

  function setLimit( $Limit )
  {
    $this->Limit = $Limit;
  }

  public function toSql( \Qck\Interfaces\DbDictionary $DbDictionary,
                         &$Params = array () )
  {
    $Columns = count( $this->Columns ) == 0 ? "*" : implode( ", ", $this->Columns );
    $Sql = "SELECT " . $Columns . " FROM " . $this->TableName . " WHERE " . $this->Expression->toSql( $DbDictionary, $Params );
    if ( $this->OrderCol )
      $Sql .= " ORDER BY " . $this->OrderCol . ($this->Descending ? " DESC" : " ASC");
    if ( !is_null( $this->Limit ) )
      $Sql .= " LIMIT " . intval( $this->Limit );
    if ( !is_null( $this->Offset ) )
      $Sql .= " LIMIT " . intval( $this->Offset );
    return $Sql;
  }

  /**
   *
   * @var string
   */
  protected $TableName;

  /**
   *
   * @var array
   */
  protected $Columns = [];

  /**
   *
   * @var  \Qck\Interfaces\Expression
   */
  protected $Expression;

  /**
   *
   * @var int
   */
  protected $Offset;

  /**
   *
   * @var int
   */
  protected $Limit;

  /**
   *
   * @var string
   */
  protected $OrderCol = null;

  /**
   *
   * @var bool
   */
  protected $Descending = true;

}
