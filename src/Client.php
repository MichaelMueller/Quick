<?php

namespace Qck;

/**
 * Description of Env
 *
 * @author muellerm
 */
class Client implements \Qck\Interfaces\Client
{

  function getIp()
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

  public function getBrowser()
  {
    $browser = new \Sinergi\BrowserDetector\Browser();
    $browserId = $browser->getName();

    if ( $browserId == \Sinergi\BrowserDetector\Browser::UNKNOWN )
      return \Qck\Interfaces\Client::BROWSER_UNKNOWN;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::IE )
      return \Qck\Interfaces\Client::BROWSER_IE;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::FIREFOX )
      return \Qck\Interfaces\Client::BROWSER_FIREFOX;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::CHROME )
      return \Qck\Interfaces\Client::BROWSER_CHROME;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::SAFARI )
      return \Qck\Interfaces\Client::BROWSER_SAFARI;
    else
      return \Qck\Interfaces\Client::BROWSER_KNOWN;
  }
}
