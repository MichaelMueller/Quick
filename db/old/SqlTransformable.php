<?php

namespace qck\db;
/**
 *
 * @author muellerm
 */
interface SqlTransformable
{
  function toSql( SqlDialect $SqlDialect );
}
