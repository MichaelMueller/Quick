<?php
namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectListObserver
{

  function onObjectAdded( ObjectList $List, Object $Object );

  function onObjectRemoved( ObjectList $List, Object $Object );
}
