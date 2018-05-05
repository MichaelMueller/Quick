<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Object
{

  function addObserver( ObjectObserver $Observer );

  function setData( array $Data );

  function getData();
}
