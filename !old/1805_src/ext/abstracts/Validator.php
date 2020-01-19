<?php

namespace qck\ext\abstracts;

/**
 * a DataObject 
 */
abstract class Validator implements \qck\ext\interfaces\Validator
{

  abstract protected function validateImpl();

  function validate( array $data )
  {
    $this->errors = [];

    $this->data = $data;
    $this->validateImpl();

    return $this->hasErrors() === false;
  }

  function has( $key )
  {
    return isset( $this->data[ $key ] );
  }

  function at( $index )
  {
    if ( !$this->values )
      $this->values = array_values( $this->data );
    return isset( $this->values[ $index ] ) ? $this->values[ $index ] : null;
  }

  function get( $key )
  {
    if ( $this->has( $key ) )
    {
      return $this->data[ $key ];
    }
    return null;
  }

  function getErrors()
  {
    return $this->errors;
  }

  function getErrorString( $glue = ", " )
  {
    return implode( $glue, $this->errors );
  }

  function hasErrors()
  {
    return count( $this->errors ) > 0;
  }

  protected function addError( $error )
  {
    $this->errors[] = $error;
  }

  protected function setError( $key, $error )
  {
    $this->errors[ $key ] = $error;
  }

  private $errors = [];
  private $data = [];
  private $values = null;

}
