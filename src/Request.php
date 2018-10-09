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

  public function getIp()
  {

    //Just get the headers if we can or else use the SERVER global
    if ( function_exists( 'apache_request_headers' ) )
    {
      $headers = apache_request_headers();
    }
    else
    {
      $headers = $_SERVER;
    }
    //Get the forwarded IP if it exists
    if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers[ 'X-Forwarded-For' ], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ) )
    {
      $the_ip = $headers[ 'X-Forwarded-For' ];
    }
    elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers[ 'HTTP_X_FORWARDED_FOR' ], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 )
    )
    {
      $the_ip = $headers[ 'HTTP_X_FORWARDED_FOR' ];
    }
    else
    {

      $the_ip = filter_var( $_SERVER[ 'REMOTE_ADDR' ], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 );
    }
    return $the_ip;
  }

  public function getUserAgent()
  {
    return $_SERVER[ "HTTP_USER_AGENT" ];
  }

  public function isKnownUserAgent()
  {
    $browser = new \Sinergi\BrowserDetector\Browser();
    return $browser->getName() != \Sinergi\BrowserDetector\Browser::UNKNOWN;
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
