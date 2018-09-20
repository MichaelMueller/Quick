<?php

namespace Qck\Ext;

/**
 *
 * @author muellerm
 */
class LanguageDetector implements \Qck\Interfaces\LanguageDetector
{
  function __construct( \Qck\Interfaces\LanguageConfig $LanguageConfig )
  {
    $this->LanguageConfig = $LanguageConfig;
  }

  function detect()
  {
    static $lang = null;
    if ( $lang )
      return $lang;
    $lang = $this->LanguageConfig->getDefaultLanguage();

    $RequestLang = isset( $_REQUEST[ $this->LangKey ] ) ? $_REQUEST[ $this->LangKey ]: null;
    $CookieLang = isset( $_COOKIE[ $this->LangKey ] ) ? $_COOKIE[ $this->LangKey ]: null;
    if ( $RequestLang && $this->LanguageConfig->isSupported($RequestLang)  )
    {
      $this->rememberLangInCookie( $_REQUEST[ $this->LangKey ] );
      $lang = $RequestLang;
    }
    else if ( $CookieLang && $this->LanguageConfig->isSupported($CookieLang) )
    {
      $lang = $CookieLang;
    }
    else
    {
      $lang_parse = null;
      preg_match_all( '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ], $lang_parse );

      if ( count( $lang_parse[ 1 ] ) )
      {
        // create a list like "en" => 0.8
        $langs = array_combine( $lang_parse[ 1 ], $lang_parse[ 4 ] );

        // set default to 1 for any without q factor
        foreach ( $langs as $theLang => $val )
        {
          if ( $val === '' )
            $langs[ $theLang ] = 1;
        }

        // sort list based on value	
        arsort( $langs, SORT_NUMERIC );
      }

      // should be sorted from most wanted to less wanted
      foreach ( $langs as $theLang => $importance )
      {
        if ( $this->LanguageConfig->isSupported($theLang) )
        {
          $lang = $theLang;
          $this->rememberLangInCookie( $theLang );
          break;
        }
      }
    }
    return $lang;
  }

  protected function rememberLangInCookie( $lang )
  {
    if ( $this->UseCookie )
      setcookie( $this->LangKey, $lang, time() + $this->CookieTtlSecs );
  }

  function setLangKey( $LangKey )
  {
    $this->LangKey = $LangKey;
  }

  function setUseCookie( $UseCookie )
  {
    $this->UseCookie = $UseCookie;
  }

  function setCookieTtlSecs( $CookieTtlSecs )
  {
    $this->CookieTtlSecs = $CookieTtlSecs;
  }

  protected $LangKey = "lang";
  protected $UseCookie = true;
  protected $CookieTtlSecs = 15552000; // half a year
  /**
   *
   * @var \Qck\Interfaces\LanguageConfig
   */
  protected $LanguageConfig;

}
