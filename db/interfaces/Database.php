<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Database
{

  function create( $Fqcn );

  function commit();

  function delete( $Fqcn, Expression $Expression );

  function select( $Fqcn, Expression $Expression, $offset = null, $limit = null );
}
