<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ScalarProperty implements Interfaces\Property
{

  function __construct( $Name, $PublicAccess = false )
  {
    $this->Name         = $Name;
    $this->PublicAccess = $PublicAccess;
  }

  public function isWeakReference()
  {
    return false;
  }

  public function setScalar( $Object, $Scalar, Interfaces\ObjectSource $Source,
                             $Reload = false )
  {
    $this->setValue( $Object, $Scalar );
  }

  public function getScalar( $Object, Interfaces\ObjectIdProvider $ObjectIdProvider )
  {
    return $this->getValue( $Object );
  }

  public function getValue( $Object )
  {
    if ( $this->PublicAccess )
    {
      $PropName = $this->Name;
      return $Object->$PropName;
    }
    else
    {
      $FuncName = $this->GetterFuncNamePrefix . $this->Name;
      return call_user_func( array ( $Object, $FuncName ) );
    }
  }

  public function setValue( $Object, $Value )
  {
    if ( $this->PublicAccess )
    {
      $PropName          = $this->Name;
      $Object->$PropName = $Value;
    }
    else
    {
      $FuncName = $this->SetterFuncNamePrefix . $this->Name;
      call_user_func_array( array ( $Object, $FuncName ), [ $Value ] );
    }
  }

  function setGetterFuncNamePrefix( $GetterFuncNamePrefix )
  {
    $this->GetterFuncNamePrefix = $GetterFuncNamePrefix;
  }

  function setSetterFuncNamePrefix( $SetterFuncNamePrefix )
  {
    $this->SetterFuncNamePrefix = $SetterFuncNamePrefix;
  }

  function setPublicAccess( $PublicAccess )
  {
    $this->PublicAccess = $PublicAccess;
  }

  protected $Name;
  protected $GetterFuncNamePrefix = "get";
  protected $SetterFuncNamePrefix = "set";
  protected $PublicAccess         = false;

}
