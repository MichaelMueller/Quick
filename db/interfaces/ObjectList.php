<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectList extends Object
{

  function addObjectListObserver( ObjectListObserver $ListObserver );
}
