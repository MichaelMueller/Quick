<?php
namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectListObserver
{

  function onObjectAdded( ObjectList $ObjectList, Object $Object );

  function onObjectRemoved( ObjectList $ObjectList, Object $Object );
}
