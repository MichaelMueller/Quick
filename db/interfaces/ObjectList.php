<?php
namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectList
{

  function add( Object $Object );

  function remove( Object $Object );

  function size();

  function at( $index );
}
