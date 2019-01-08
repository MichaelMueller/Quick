<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ActiveRecordFile implements Interfaces\ActiveRecord
{

  function __construct( $DataDir, $DataObjectSchema, $Id = null, $Data = [] )
  {
    $this->DataDir          = $DataDir;
    $this->DataObjectSchema = $DataObjectSchema;
    $this->Id               = $Id;
    $this->Data             = $Data;
  }

  public function delete()
  {
    
  }

  public function get( $Name, $Default = null )
  {
    if ( ! isset( $this->Data[ $Name ] ) )
      $this->set( is_callable( $Default ) ? call_user_func( $Default ) : $Default  );
    return $this->Data[ $Name ];
  }

  public function getData()
  {
    return $this->Data;
  }

  public function save()
  {
    $DataToBeSerialized = [];
    foreach ( $this->Data as $Name => $Value )
    {
      if ( $this->DataObjectSchema->isReference( $Name ) ) // WeakRef?
      {
        $ActiveRecord = $Value->getActiveRecord();
        if ( $Value->getSchema()->isWeakReference( $Name ) == false )
        {
          if ( ! $ActiveRecord )
          {
            $ActiveRecord = new ActiveRecordFile( $this->DataDir, get_class( $Value ) );
            $Value->setActiveRecord( $ActiveRecord );
          }
          $ActiveRecord->save();
        }
        $Value                       = $ActiveRecord->getId();
        $DataToBeSerialized[ $Name ] = $Value;
      }
    }

    $this->Serializer->serialize( $DataToBeSerialized );
    $this->Changed = false;
  }

  public function set( $Name, $Value )
  {
    $this->Data[ $Name ] = $Value;
    $this->Changed       = true;
  }

  public function getId()
  {
    if ( ! $this->Id )
      return $this->Id;

    $this->Id = 1;
    $FilePath = null;
    do
    {
      ++ $this->Id;
      $FilePath = $this->getDataFile();
    }
    while ( file_exists( $FilePath ) );
    touch( $FilePath );
    return $this->Id;
  }

  protected function getDataFile()
  {
    static $BaseDir = null;
    if ( ! $BaseDir )
    {
      $BaseDir = $this->DataDir . "/" . $this->DataObjectSchema->getFqcn();
      $BaseDir = str_replace( "\\", "/", $BaseDir );
    }
    $FilePath = $BaseDir . "/" . $this->Id . "." . $this->Serializer->getFileExtension();
    return $FilePath;
  }

  /**
   *
   * @var string 
   */
  protected $DataDir;

  /**
   *
   * @var Interfaces\DataObjectSchema 
   */
  protected $DataObjectSchema;

  /**
   *
   * @var Interfaces\Serializer 
   */
  protected $Serializer;

  /**
   *
   * @var string 
   */
  protected $Id;

  /**
   *
   * @var array 
   */
  protected $Data = [];

  /**
   *
   * @var bool 
   */
  protected $Changed = false;

}
