<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectListDescriptor extends ObjectDescriptor
{

  function getFqcnOfContainedObjects();
}
