<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Inputs implements \Qck\Interfaces\Inputs
{

  function __construct( $Params = null )
  {
    $this->Params = $Params;
  }

  function setParams( $Params )
  {
    $this->Params = $Params;
  }

  public function get( $Name, $Default = null )
  {
    $Params = $this->getParams();
    return isset( $Params[ $Name ] ) ? $Params[ $Name ] : $Default;
  }

  protected function getArgvAsParamsArray()
  {
    array_shift( $argv );
    $out = array ();
    foreach ( $argv as $arg )
    {
      // --foo --bar=baz
      if ( substr( $arg, 0, 2 ) == '--' )
      {
        $eqPos = strpos( $arg, '=' );
        // --foo
        if ( $eqPos === false )
        {
          $key         = substr( $arg, 2 );
          $value       = isset( $out[ $key ] ) ? $out[ $key ] : true;
          $out[ $key ] = $value;
        }
        // --bar=baz
        else
        {
          $key         = substr( $arg, 2, $eqPos - 2 );
          $value       = substr( $arg, $eqPos + 1 );
          $out[ $key ] = $value;
        }
      }
      // -k=value -abc
      else if ( substr( $arg, 0, 1 ) == '-' )
      {
        // -k=value
        if ( substr( $arg, 2, 1 ) == '=' )
        {
          $key         = substr( $arg, 1, 1 );
          $value       = substr( $arg, 3 );
          $out[ $key ] = $value;
        }
        // -abc
        else
        {
          $chars = str_split( substr( $arg, 1 ) );
          foreach ( $chars as $char )
          {
            $key         = $char;
            $value       = isset( $out[ $key ] ) ? $out[ $key ] : true;
            $out[ $key ] = $value;
          }
        }
      }
      // plain-arg
      else
      {
        $value = $arg;
        $out[] = $value;
      }
    }

    return $out;
  }

  public function has( $Name )
  {
    $Params = $this->getParams();
    return isset( $Params[ $Name ] );
  }

  public function getParams()
  {
    if ( ! $this->Params )
    {
      $CmdParams     = isset( $_SERVER[ "argc" ] ) ? $this->getArgvAsParamsArray() : [];
      $RequestParams = isset( $_REQUEST ) ? $_REQUEST : [];
      $this->Params  = array_merge( $RequestParams, $CmdParams );
    }
    return $this->Params;
  }

  protected $Params;

}
