<?php

namespace qck\ext;

/**
 * Description of Env
 *
 * @author muellerm
 */
class ClientInfo implements interfaces\ClientInfo
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
      return interfaces\ClientInfo::BROWSER_UNKNOWN;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::IE )
      return interfaces\ClientInfo::BROWSER_IE;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::FIREFOX )
      return interfaces\ClientInfo::BROWSER_FIREFOX;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::CHROME )
      return interfaces\ClientInfo::BROWSER_CHROME;
    else if ( $browserId == \Sinergi\BrowserDetector\Browser::SAFARI )
      return interfaces\ClientInfo::BROWSER_SAFARI;
    else
      return interfaces\ClientInfo::BROWSER_KNOWN;
  }
}
