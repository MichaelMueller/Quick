<?php

namespace Qck\Expression\Tests;

class DbDialect implements \Qck\Interfaces\Sql\DbDialect
{

  public function getAutoincrementAttribute()
  {
    return "auto increment";
  }

  public function getBoolDatatype()
  {
    return "bool";
  }

  public function getFalseLiteral()
  {
    return "0";
  }

  public function getFloatDatatype()
  {
    return "float";
  }

  public function getIntDatatype()
  {
    return "int";
  }

  public function getPrimaryKeyAttribute()
  {
    return "primary key";
  }

  public function getRegExpOperator()
  {
    return "regexp";
  }

  public function getStringDatatype( $MinLength = 0, $MaxLength = 255, $Blob = false )
  {
    return "string(" . $MinLength . ", " . $MaxLength . ", " . $Blob . ")";
  }

  public function getStrlenFunctionName()
  {
    return "strlen";
  }

  public function getTrueLiteral()
  {
    return "1";
  }

  public function getAndOperator()
  {
    return "and";
  }

  public function getOrOperator()
  {
    return "or";
  }

  public function createPdo( $DbName )
  {
    return null;
  }

  public function getForeignKeyConstraint( $ColName, $RefTableName, $RefColName )
  {
    return sprintf( "FOREIGN KEY(%s) REFERENCES %s(%s)", $ColName, $RefTableName, $RefColName );
  }
}
