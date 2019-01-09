<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileSerializationHelper implements \Qck\Interfaces\FileSerializationHelper, \Qck\Interfaces\ObjectIdGenerator
{

  function __construct( $DataDir, \Qck\Interfaces\FileFactory $FileFactory,
                        \Qck\Interfaces\ArraySerializer $ArraySerializer )
  {
    $this->DataDir         = $DataDir;
    $this->FileFactory     = $FileFactory;
    $this->ArraySerializer = $ArraySerializer;
  }

  /**
   * 
   * @param mixed $Id
   * @return \Qck\Interfaces\File
   */
  public function getFile( $Id )
  {
    static $Zerofill = 15;
    $FileName        = is_int( $Id ) ? str_pad( $Id, $Zerofill, '0', STR_PAD_LEFT ) : $Id;
    $Path            = $this->DataDir . DIRECTORY_SEPARATOR . $FileName . "." . $this->ArraySerializer->getFileExtension();
    return $this->FileFactory->createFileObjectFromPath( $Path );
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
   * @var \Qck\Interfaces\FileFactory
   */
  protected $FileFactory;

  /**
   *
   * @var \Qck\Interfaces\ArraySerializer
   */
  protected $ArraySerializer;

}
