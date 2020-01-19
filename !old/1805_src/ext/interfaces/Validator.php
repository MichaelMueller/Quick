<?php

namespace qck\ext\interfaces;

/**
 * Basic interface for a logger
 *
 * @author muellerm
 */
interface Validator
{

  /**
   * @return array of errors or empty array
   */
  function validate( array $data );

  function getErrors();

  function hasErrors();
}
