<?php

namespace Qck\Interfaces\Expressions;

/**
 * base class for expressions (which is in turn a tree again)
 * @author muellerm
 */
interface Expression
{

    /**
     * 
     * @param \Qck\Interfaces\SqlDialect $SqlDbDialect
     * @param array $Params will hold the params array to be filled if used with \PDO
     * @return string
     */
    function toSql( \Qck\Interfaces\SqlDialect $SqlDbDialect,
                    array &$Params = array () );

    /**
     * @return string
     */
    function __toString();
}
