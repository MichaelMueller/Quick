<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlColumn
{

  function getDatatype();

  function canBeNull();

  function getDefaultValue();
}
