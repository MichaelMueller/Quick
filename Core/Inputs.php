<?php

namespace Qck\Core;

/**
 *
 * @author muellerm
 */
class Inputs implements \Qck\Interfaces\Inputs
{

  public function get( $Name, $Default = null )
  {
    if ( !$this->Params )
      $this->Params = isset( $_SERVER[ "argc" ] ) ? $this->getArgvAsParamsArray() : $_REQUEST;
    return isset( $this->Params[ $Name ] ) ? $this->Params[ $Name ] : $Default;
  }

  protected function getArgvAsParamsArray()
  {
    $Params = [];

    $argv = $_SERVER[ "argv" ];
    $pos = 1;
    for ( $i = 1; $i < count( $argv ); $i++ )
    {
      $match = [];
      if ( preg_match( '/^--([^=]+)=(.*)/', $argv[ $i ], $match ) )
        $Params[ $match[ 1 ] ] = $match[ 2 ];
      else if ( preg_match( '/^-([^=]+)=(.*)/', $argv[ $i ], $match ) )
        $Params[ $match[ 1 ] ] = $match[ 2 ];
      else
      {
        $Params[ $pos ] = $argv[ $i ];
        $pos++;
      }
    }
    return $Params;
  }

  protected $Params;

}
