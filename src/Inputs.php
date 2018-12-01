<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Inputs implements \Qck\Interfaces\Inputs
{

  function __construct( $OverwriteParams = null )
  {
    $this->OverwriteParams = $OverwriteParams;
  }

  function setOverwriteParams( $OverwriteParams )
  {
    $this->OverwriteParams = $OverwriteParams;
  }

  public function get( $Name, $Default = null )
  {
    $Params = $this->getParams();
    return isset( $Params[ $Name ] ) ? $Params[ $Name ] : $Default;
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
      {
        $Params[ $match[ 1 ] ] = $match[ 2 ];
      }
      else if ( preg_match( '/^-([^=]+)=(.*)/', $argv[ $i ], $match ) )
      { 
        $Params[ $match[ 1 ] ] = $match[ 2 ];
      }
      else
      {
        $Params[ $pos ] = $argv[ $i ];
        $pos++;
      }
    }
    return $Params;
  }

  public function has( $Name )
  {
    $Params = $this->getParams();
    return isset( $Params[ $Name ] );
  }

  public function getParams()
  {
    if ( !$this->Params )
    {
      $this->Params = isset( $_SERVER[ "argc" ] ) ? $this->getArgvAsParamsArray() : $_REQUEST;

      if ( $this->OverwriteParams )
        $this->Params = array_merge( $this->Params, $this->OverwriteParams );
    }
    return $this->Params;
  }
  

  protected $OverwriteParams;
  protected $Params;

}