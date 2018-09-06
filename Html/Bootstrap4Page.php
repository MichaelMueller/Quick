<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class Bootstrap4Page extends Page
{

  function __construct( $title, \Qck\Interfaces\Template $contentTemplate=null )
  {
    parent::__construct( $title, $contentTemplate );
    $this->addMetaElement( "viewport", "width=device-width, initial-scale=1, shrink-to-fit=no" );
    $this->addStyleSheet( "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css", "sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm", "anonymous" );
    $this->addScript( "https://code.jquery.com/jquery-3.2.1.slim.min.js", "sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN", "anonymous" );
    $this->addScript( "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js", "sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q", "anonymous" );
    $this->addScript( "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js", "sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl", "anonymous" );
  }
}
