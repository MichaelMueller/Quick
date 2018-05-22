<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectObserver
{

  function onObjectModified( Object $Object );
}
