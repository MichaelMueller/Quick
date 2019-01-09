<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class DataFileProvider implements \Qck\Interfaces\Serialization\DataFileProvider, \Qck\Interfaces\Serialization\ObjectIdGenerator
{

  function __construct( $DataDir, \Qck\Interfaces\FileFactory $FileFactory, \Qck\Interfaces\Serializer $Serializer )
  {
    $this->DataDir     = $DataDir;
    $this->FileFactory = $FileFactory;
    $this->Serializer  = $Serializer;
  }

  public function getFile( $Id )
  {
    if ( isset( $this->IdToFile[ $Id ] ) )
      return $this->IdToFile[ $Id ];
    $Path                  = $this->DataDir . DIRECTORY_SEPARATOR . $Id . "." . $this->Serializer->getFileExtension();
    $this->IdToFile[ $Id ] = $this->FileFactory->createFileObjectFromPath( $Path );
    return $this->IdToFile[ $Id ];
  }

  public function generateNextId()
  {
    $i        = 1;
    $Zerofill = 15;
    $FilePath = null;
    do
    {
      $FilePath = $this->DataDir . DIRECTORY_SEPARATOR . str_pad( $i, $Zerofill, '0', STR_PAD_LEFT ) . $this->Serializer->getFileExtension();
      ++ $i;
    }
    while ( file_exists( $FilePath ) );
    touch( $FilePath );
    return $i;
  }

  public function getSerializer()
  {
    return $this->Serializer;
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var \Qck\Interfaces\FileFactory
   */
  protected $FileFactory;

  /**
   *
   * @var \Qck\Interfaces\Serializer
   */
  protected $Serializer;

  /**
   *
   * @var array
   */
  protected $IdToFile = [];

}
