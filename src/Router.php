<?php

namespace Qck;

/**
 * The Router gets the currently selected controller of the application
 *
 * @author muellerm
 */
class Router implements \Qck\Interfaces\Router
{

  const DEFAULT_QUERY_KEY = "q";

  function __construct(\Qck\Interfaces\Inputs $Inputs)
  {
    $this->Inputs   = $Inputs;
    $this->QueryKey = self::DEFAULT_QUERY_KEY;
  }

  function setQueryKey($QueryKey)
  {
    $this->QueryKey = $QueryKey;
  }

  public function getLink($Route, $args = array())
  {

    $link = "?" . $this->QueryKey . "=" . $Route;

    if (is_array($args))
    {
      foreach ($args as $key => $value)
        $link .= "&" . $key . "=" . (urlencode($value));
    }
    return $link;
  }

  public function redirect($Route, $args = array())
  {
    $Link = $this->getLink($Route, $args);
    header("Location: " . $Link);
  }

  public function getCurrentRoute()
  {
    static $CurrentRoute = null;
    if (!$CurrentRoute)
      $CurrentRoute        = $this->Inputs->get($this->QueryKey, $this->DefaultRoute);
    return $CurrentRoute;
  }

  function setDefaultRoute($DefaultRoute)
  {
    $this->DefaultRoute = $DefaultRoute;
  }

  /**
   *
   * @var \Qck\Interfaces\Inputs
   */
  protected $Inputs;

  /**
   *
   * @var string
   */
  protected $QueryKey = "q";

  /**
   *
   * @var string
   */
  protected $DefaultRoute = "Start";

}