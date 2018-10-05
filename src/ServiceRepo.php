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
   * Singleton implementation
   * @return ServiceRepo
   */
  static function getInstance()
  {
    if ( is_null( self::$Instance ) )
      self::$Instance = new ServiceRepo ( );
    return self::$Instance;
  }

  /**
   * Add a concrete Instance
   * @param object $Service the Service Class Instance
   */
  function addService( $Service )
  {
    $this->addInstanceOrFactory( get_class( $Service ), $Service );
  }

  /**
   * Add a Factory Closure
   * @param string $Fqcn the fqcn of the service
   * @param string $Factory a functiont that creates the class (or null if not possible) or an instance of that class 
   */
  function addServiceFactory( $Fqcn, callable $Factory )
  {
    $this->addInstanceOrFactory( $Fqcn, $Factory );
  }

  /**
   * @see Interfaces\ServiceRepo::getOptional($Fqin, $Fqcn)
   * @param string $Fqin
   * @param string $Fqcn
   * @return mixed
   */
  public function getOptional( $Fqin, $Fqcn = null )
  {
    return $this->getOrCreate( $Fqin, $Fqcn, false );
  }

  function get( $Fqin, $Fqcn = null )
  {
    $Service = $this->getOptional( $Fqin, $Fqcn );
    if ( is_null( $Service ) )
      throw new \InvalidArgumentException( sprintf( "Could not locate an Instance for interface %s (requested class: %s)", $Fqin, $Fqcn ? $Fqcn : "unspecified"  ) );
    return $Service;
  }

  public function create( $Fqin, $Fqcn = null )
  {
    $Service = $this->createOptional( $Fqin, $Fqcn );
    if ( is_null( $Service ) )
      throw new \InvalidArgumentException( sprintf( "Could not create an Instance for interface %s (requested class: %s)", $Fqin, $Fqcn ? $Fqcn : "unspecified"  ) );
    return $Service;
  }

  public function createOptional( $Fqin, $Fqcn = null )
  {
    return $this->getOrCreate( $Fqin, $Fqcn, true );
  }

  function getAll( $Fqin )
  {
    $Services = [];
    if ( isset( $this->Services[ $Fqin ] ) )
    {
      foreach ( array_keys( $this->Services[ $Fqin ] ) as $Fqcn )
      {
        $Service = $this->getOptional( $Fqin, $Fqcn );
        if ( $Service )
          $Services[] = $Service;
      }
    }
    return $Services;
  }

  protected function addInstanceOrFactory( $Fqcn, $InstanceOrFactory )
  {
    $Fqins = class_implements( $Fqcn );
    foreach ( $Fqins as $Fqin )
    {
      if ( !isset( $this->Services[ $Fqin ] ) )
      {
        $this->Services[ $Fqin ] = [];
        //$this->FirstServices[ $Fqin ] = $Fqcn;
      }
      $this->Services[ $Fqin ][ $Fqcn ] = $InstanceOrFactory;
      $this->LatestServices[ $Fqin ] = $Fqcn;
    }
  }

  protected function getOrCreate( $Fqin, $Fqcn = null, $create = false )
  {
    if ( is_null( $Fqcn ) && isset( $this->LatestServices[ $Fqin ] ) )
    {
      $Fqcn = $this->LatestServices[ $Fqin ];
      return $this->getOrCreate( $Fqin, $Fqcn, $create );
    }
    else if ( isset( $this->Services[ $Fqin ][ $Fqcn ] ) )
    {
      if ( $create == false && isset( $this->Instances[ $Fqin ][ $Fqcn ] ) )
        return $this->Instances[ $Fqin ][ $Fqcn ];

      $Factory = $this->Services[ $Fqin ][ $Fqcn ];
      $Instance = call_user_func( $Factory );
      if ( $Instance !== null )
        $this->Instances[ $Fqin ][ $Fqcn ] = $Instance;
      return $Instance;
    }
    return null;
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
  protected $Instances = [];

  /**
   *
   * @var array
   */
  protected $LatestServices = [];

}
