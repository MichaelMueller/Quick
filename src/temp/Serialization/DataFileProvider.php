<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class DataFileProvider implements \Qck\Interfaces\Serialization\DataFileProvider, \Qck\Interfaces\Serialization\ObjectIdGenerator
{

  function __construct( $DataDir, \Qck\Interfaces\PathFactory $PathFactory,
                        \Qck\Interfaces\ArraySerializer $ArraySerializer )
  {
    $this->DataDir         = $DataDir;
    $this->PathFactory     = $PathFactory;
    $this->ArraySerializer = $ArraySerializer;
  }

  /**
   * 
   * @param mixed $Id
   * @return \Qck\Interfaces\Path
   */
  public function getFile( $Id )
  {
    static $Zerofill = 15;
    $FileName        = is_int( $Id ) ? str_pad( $Id, $Zerofill, '0', STR_PAD_LEFT ) : $Id;
    $Path            = $this->DataDir . DIRECTORY_SEPARATOR . $FileName . "." . $this->ArraySerializer->getFileExtension();
    return $this->PathFactory->createPath( $Path );
  }

  public function generateNextId()
  {
    $Id       = 0;
    $DataFile = null;
    do
    {
      ++ $Id;
      $DataFile = $this->getFile( $Id );
    }
    while ( $DataFile->exists() );
    $DataFile->touch();
    return $Id;
  }

  public function getArraySerializer()
  {
    return $this->ArraySerializer;
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

  /**
   *
   * @var \Qck\Interfaces\PathFactory
   */
  protected $PathFactory;

  /**
   *
   * @var \Qck\Interfaces\ArraySerializer
   */
  protected $ArraySerializer;

}
