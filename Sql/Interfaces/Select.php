<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Select
{

  function toSql( DbDictionary $DbDictionary, &$Params = [] );
}
