<?php

namespace Qck;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class ServiceRepo implements Interfaces\ServiceRepo
{

  /**
   * 
   * @return ServiceRepo
   */
  static function getInstance()
  {
    if ( is_null( self::$Instance ) )
      self::$Instance = new ServiceRepo ( );
    return self::$Instance;
  }

  /**
   * the only singelton in the world
   */
  protected function __construct()
  {
    // pass
  }

  /**
   * 
   * @param string $Service the Service Class Instance
   */
  function addService( $Service )
  {
    $this->addInstanceOrFactory(get_class($Service), $Service);
  }
  
  /**
   * 
   * @param string $Fqcn the fqcn of the service
   * @param string $Factory a functiont that creates the class (or null if not possible) or an instance of that class 
   */
  function addServiceFactory( $Fqcn, callable $Factory )
  {
    $this->addInstanceOrFactory($Fqcn, $Factory);
  }
  
  protected function addInstanceOrFactory($Fqcn, $InstanceOrFactory)
  {
    $Fqins = class_implements( $Fqcn );
    foreach ( $Fqins as $Fqin )
    {
      if ( !isset( $this->Services[ $Fqin ] ) )
      {
        $this->Services[ $Fqin ] = [];
        $this->FirstServices[ $Fqin ] = $Fqcn;
      }
      $this->Services[ $Fqin ][ $Fqcn ] = $InstanceOrFactory;
    }    
  }
  
  public function getOptional( $Fqin, $Fqcn = null )
  {
    if ( is_null( $Fqcn ) && isset( $this->FirstServices[ $Fqin ] ) )
    {
      $Fqcn = $this->FirstServices[ $Fqin ];
      return $this->getOptional( $Fqin, $Fqcn );
    }
    else if ( isset( $this->Services[ $Fqin ][ $Fqcn ] ) )
    {
      $InstanceOrFactory = $this->Services[ $Fqin ][ $Fqcn ];
      if ( is_callable( $InstanceOrFactory ) )
      {
        $InstanceOrFactory = call_user_func( $InstanceOrFactory );
        if ( $InstanceOrFactory !== null )
          $this->Services[ $Fqin ][ $Fqcn ] = $InstanceOrFactory;
      }
      return $InstanceOrFactory;
    }
    return null;
  }

  function get( $Fqin, $Fqcn = null )
  {
    $Service = $this->getOptional( $Fqin, $Fqcn );
    if ( is_null( $Service ) )
      throw new \InvalidArgumentException( sprintf( "Could not locate a Service Instance for interface %s (requested class: %s)", $Fqin, $Fqcn ? $Fqcn : "unspecified"  ) );
    return $Service;
  }

  /**
   *
   * @var Interfaces\ServiceRepo 
   */
  protected static $Instance;

  /**
   *
   * @var array
   */
  protected $Services = [];

  /**
   *
   * @var array
   */
  protected $FirstServices = [];

}
