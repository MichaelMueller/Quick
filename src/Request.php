<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Request implements \Qck\Interfaces\Request
{

  function __construct( $OverwriteParams = null )
  {
    $this->OverwriteParams = $OverwriteParams;
  }

  function setOverwriteParams( $OverwriteParams )
  {
    $this->OverwriteParams = $OverwriteParams;
  }

  function isCli()
  {
    return isset( $_SERVER[ 'argc' ] );
  }

  public function get( $Name, $Default = null )
  {
    if ( !$this->Params )
    {
      $this->Params = isset( $_SERVER[ "argc" ] ) ? $this->getArgvAsParamsArray() : $_REQUEST;

      if ( $this->OverwriteParams )
        $this->Params = array_merge( $this->Params, $this->OverwriteParams );
    }
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

  public function getIp()
  {
    throw new \BadMethodCallException();
  }

  public function getUserAgent()
  {
    throw new \BadMethodCallException();
  }

  public function isKnownUserAgent()
  {
    throw new \BadMethodCallException();
  }

  protected $OverwriteParams;
  protected $Params;

}
