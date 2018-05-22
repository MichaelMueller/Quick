<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Object
{

  function addObserver( ObjectObserver $Observer );

  function setId( $Id );

  function getId();

  function setData( array $Data );

  function getData();
}
