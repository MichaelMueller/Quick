<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlDialect
{

    /**
     * @return \PDO
     */
    function createPdo( $DbName );

    /**
     * @return string
     */
    function getBoolDatatype();

    /**
     * @return string
     */
    function getIntDatatype();

    /**
     * @return string
     */
    function getPrimaryKeyAttribute();

    /**
     * @return string
     */
    function getAutoincrementAttribute();

    /**
     * @return string
     */
    function getForeignKeyConstraint( $ColName, $RefTableName, $RefColName );

    /**
     * @return string
     */
    function getStringDatatype( $MinLength = 0, $MaxLength = 255, $Blob = false );

    /**
     * @return string
     */
    function getFloatDatatype();

    /**
     * @return string
     */
    function getTrueLiteral();

    /**
     * @return string
     */
    function getFalseLiteral();

    /**
     * @return string
     */
    function getRegExpOperator();

    /**
     * @return string
     */
    function getAndOperator();

    /**
     * @return string
     */
    function getOrOperator();

    /**
     * @return string
     */
    function getStrlenFunctionName();
}
